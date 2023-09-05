<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\components\IAA\Authorization\AccessRule;
use app\components\IAA\Authentication\Model\ValueObject\RoleTypeId;
use app\components\IAA\Authentication\Service\IdentityService;
//
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;
//
use app\components\Delivery\Domain\ValueObject\DeliveryTypeId;
use app\components\Order\Domain\ValueObject\DeliveryParams;
use app\components\Order\Domain\ValueObject\OrderComment;
use app\components\Order\Domain\ValueObject\OrderId;
use app\components\Order\Infrastructure\OrderQueryRepositoryInterface;
use app\components\Saga\OrderPlace\Dto\OrderPlaceSagaData;
use app\components\Saga\OrderPlace\OrderPlaceSaga;
use app\components\Shared\Domain\ValueObject\PageNumber;
use app\components\User\Domain\ValueObject\UserId;

class OrderController extends \yii\web\Controller
{
    private OrderQueryRepositoryInterface $orderQueryRepository;

    public function __construct(
        $id,
        $module,
        OrderQueryRepositoryInterface $orderQueryRepository,
        $config = []
    ) {
        $this->orderQueryRepository = $orderQueryRepository;
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' =>
                [
                    'class' => AccessRule::class,
                ],
                'rules' =>
                [
                    [
                        'actions' => ['place', 'list_client', 'view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['list_adm', 'manage'],
                        'allow' => true,
                        'roles' => [RoleTypeId::Admin,],
                    ],
                ],
            ],
            /* 'verbs' => [
                  'class' => VerbFilter::className(),
                  'actions' => [
                  'logout' => ['post'],
                  ],
                  ], */
        ];
    }

    public function actionPlace()
    {
        $deliveryTypeIdRaw = HelperY::getPost('deliveryTypeId', null);
        $cityNameRaw = DeliveryParams::prepareСityName(HelperY::getPost('cityName', ''));
        $commentRaw = OrderComment::prepare(HelperY::getPost('comment', ''));

        $deliveryParams = new DeliveryParams(
            deliveryTypeId: DeliveryTypeId::tryFrom($deliveryTypeIdRaw),
            cityName: $cityNameRaw
        );

        $orderComment = new OrderComment($commentRaw);

        /** @var \app\components\Cart\Domain\Aggregate\Cart $cart */
        $cart = Yii::$container->get(\app\components\Cart\Domain\Aggregate\Cart::class);
        $cartItems = $cart->getItems();

        //raw data    
        $itemsRaw = [];
        foreach ($cartItems as $cartItem) {
            /** @var \app\components\Cart\Domain\ValueObject\CartItem $cartItem */
            $itemsRaw[] = new \app\components\Saga\OrderPlace\Dto\ItemRaw(
                productId: $cartItem->getProductId()->getId(),
                quantity: $cartItem->getProductQuantity()->getQuantity()
            );
        }

        //make data for saga
        $sagaData = new OrderPlaceSagaData(
            userId: IdentityService::userId(),
            itemsRaw: $itemsRaw,
            deliveryParams: $deliveryParams,
            orderComment: $orderComment
        );
        $orderPlaceSaga = new OrderPlaceSaga($sagaData);
        $orderPlaceSaga->process();

        /** @var OrderPlaceSagaData $orderPlaceSagaData */
        $orderPlaceSagaData = $orderPlaceSaga->getSagaData();
        //print_r($orderPlaceSagaData->getInfo());
        //print_r($orderPlaceSaga->isSuccess() ? 'ok' : 'fail');        

        if ($orderPlaceSaga->isSuccess()) {
            Yii::$app->session->addFlash('success', ' Ваш заказ принят. Скоро вам перезвонят. ');
        } else {
            Yii::$app->session->addFlash('danger', ' Сбой в работе магазина. Внизу сайта есть наши контакты. Пожалуйста, свяжитесь с нами. ');
        }

        return $this->redirect(Url::to(['order/list_client']));
    }


    public function actionList_client()
    {
        $pageRaw = PageNumber::prepare(HelperY::getGet('page', 1));

        $page = new PageNumber($pageRaw);
        $orders = $this->orderQueryRepository->getOrders(
            userId: IdentityService::userId(),
            page: $page
        );

        $urlParams = [];
        $urlParams[0] = 'order/list_client'; //for URL create
        $urlParams['page'] = $pageRaw;

        return $this->render('list_client', [
            'orders' => $orders,
            'urlParams' => $urlParams,
        ]);
    }

    public function actionView($id)
    {
        $orderIdRaw = OrderId::prepare($id);

        $orderId = OrderId::fromString($orderIdRaw);

        $orderDto = $this->orderQueryRepository->getOrder(
            orderId: $orderId
        );
        if (is_null($orderDto)) {
            throw new HttpException(404, 'Not found');
        }

        if (!IdentityService::userId()->isEqualsTo(UserId::fromString($orderDto->getUserId()))) {
            throw new HttpException(404, 'Not found');
        }

        $itemsDto = $this->orderQueryRepository->getOrderItems(
            orderId: $orderId
        );

        return $this->render('view', [
            'order' => $orderDto,
            'items' => $itemsDto,
        ]);
    }

    public function actionList_adm()
    {
        $pageRaw = PageNumber::prepare(HelperY::getGet('page', 1));

        $page = new PageNumber($pageRaw);
        $orders = $this->orderQueryRepository->getOrders(
            userId: UserId::fromString(null),
            page: $page
        );

        $urlParams = [];
        $urlParams[0] = 'order/list_adm'; //for URL create
        $urlParams['page'] = $pageRaw;

        return $this->render('list_adm', [
            'orders' => $orders,
            'urlParams' => $urlParams,
        ]);
    }

    public function actionManage($id)
    {
        $orderIdRaw = OrderId::prepare($id);

        $orderId = OrderId::fromString($orderIdRaw);
        $orderDto = $this->orderQueryRepository->getOrder(
            orderId: $orderId
        );
        $itemsDto = $this->orderQueryRepository->getOrderItems(
            orderId: $orderId
        );

        if (is_null($orderDto)) {
            throw new HttpException(404, 'Not found');
        }

        return $this->render('manage', [
            'order' => $orderDto,
            'items' => $itemsDto,
        ]);
    }
}
