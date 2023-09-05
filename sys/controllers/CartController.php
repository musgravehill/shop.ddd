<?php

namespace app\controllers;

use app\components\Cart\Domain\Aggregate\Cart;
use app\components\Cart\Domain\ValueObject\CartItem;
use app\components\Cart\Infrastructure\CartQuery;
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
use app\components\Offer\Domain\Service\OfferFactory;
use app\components\Offer\Domain\ValueObject\OfferItem;
use app\components\Order\Domain\Aggregate\Order;
use app\components\Product\Domain\Contract\ProductRepositoryInterface;
use app\components\Product\Domain\ValueObject\ProductId;
use app\components\Product\Infrastructure\ProductImgService;
use app\components\Shared\Domain\ValueObject\CountOnPage;
use app\components\Shared\Domain\ValueObject\PageNumber;
use app\components\Shared\Domain\ValueObject\QuantityPositive;
use app\components\Shared\Domain\ValueObject\QuantityZeroPositive;
use app\components\User\Domain\Contract\UserRepositoryInterface;
use app\components\UserCompany\Domain\Contract\UserCompanyRepositoryInterface;

//

class CartController extends \yii\web\Controller
{
    private ProductRepositoryInterface $productRepository;
    private ProductImgService $productImgService;

    private UserRepositoryInterface $userRepository;
    private UserCompanyRepositoryInterface $userCompanyRepository;

    public function __construct(
        $id,
        $module,
        ProductRepositoryInterface $productRepository,
        ProductImgService $productImgService,
        UserRepositoryInterface $userRepository,
        UserCompanyRepositoryInterface $userCompanyRepository,
        $config = []
    ) {
        $this->productRepository = $productRepository;
        $this->productImgService = $productImgService;
        $this->userRepository = $userRepository;
        $this->userCompanyRepository = $userCompanyRepository;
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        //https://thecodeninja.net/2014/12/simpler-role-based-authorization-in-yii-2-0/
        return [
            'access' => [
                'class' => AccessControl::class,
                // We will override the default rule config with the new AccessRule class
                'ruleConfig' => [
                    'class' => AccessRule::class,
                ],
                //'only' => ['my', 'create', 'edit'],
                'rules' => [
                    [
                        'actions' => ['index', 'set', 'clean', 'save', 'getdata'],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                    [
                        'actions' => ['abandoned',],
                        'allow' => true,
                        'roles' => [RoleTypeId::Admin],
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

    public function actionAbandoned()
    {
        $pageRaw = PageNumber::prepare(HelperY::getGet('page', 1));
        $countOnPageRaw = CountOnPage::prepare(HelperY::getGet('countOnPage', 30));

        $page = new PageNumber($pageRaw);
        $countOnPage = new CountOnPage($countOnPageRaw);

        $cartQuery = new CartQuery;
        $items = $cartQuery->list(
            page: $page,
            cop: $countOnPage,
        );

        $urlParams = [];
        $urlParams[0] = 'cart/abandoned'; //for URL create
        $urlParams['page'] = $pageRaw;

        return $this->render('abandoned', [
            'items' => $items,
            'urlParams' => $urlParams,
        ]);
    }

    public function actionIndex()
    {
        /** @var Cart $cart */
        $cart = Yii::$container->get(Cart::class);
        $cartItems = $cart->getItems();

        $cartItemsData = [];
        foreach ($cartItems as $cartItem) {
            /** @var CartItem $cartItem */
            $product = $this->productRepository->getById($cartItem->getProductId());
            if (is_null($product)) {
                $cart->removeItem($cartItem);
                continue;
            }
            $imgUrls = $this->productImgService->getImgUrls($product->getId());
            $cartItemsData[] = [
                'product' => $product,
                'quantity' => $cartItem->getProductQuantity()->getQuantity(),
                'imgUrls' => $imgUrls,
            ];
        }

        $userId = IdentityService::userId();
        $user = $this->userRepository->getById($userId);
        $userCompany = $this->userCompanyRepository->getByUserId($userId);

        $canUserPlaceOrder = Order::canUserPlaceOrder(
            user: $user,
            userCompany: $userCompany
        );

        return $this->render('index', array(
            'cartItemsData' => $cartItemsData,
            'user' => $user,
            'userCompany' => $userCompany,
            'canUserPlaceOrder' => $canUserPlaceOrder,
        ));
    }

    public function actionSet()
    {
        $quantityRaw = QuantityZeroPositive::prepare(HelperY::getPost('quantity', 0));
        $productIdRaw = ProductId::prepare(HelperY::getPost('productId', null));

        $productId = ProductId::fromString($productIdRaw);
        $product = $this->productRepository->getById($productId);
        if (is_null($product)) { // ufu==ufu
            throw new HttpException(404, 'Not found');
        }

        /** @var Cart $cart */
        $cart = Yii::$container->get(Cart::class);
        if ($quantityRaw > 0) {
            $item = new CartItem(
                productId: $productId,
                productQuantity: new QuantityPositive($quantityRaw)
            );
            $cart->setItem($item);
        } else {
            $item = new CartItem(
                productId: $productId,
                productQuantity: new QuantityPositive(1)
            );
            $cart->removeItem($item);
        }

        return $this->asJson(1);
    }

    public function actionGetdata()
    {
        /** @var Cart $cart */
        $cart = Yii::$container->get(Cart::class);

        /** @var OfferFactory $offerFactory */
        $userId = IdentityService::userId();
        $offerFactory = Yii::$container->get(OfferFactory::class, ['userId' => $userId]);
        /** @var CartItem $item */
        foreach ($cart->getItems() as $item) {
            $offerFactory->addItem(
                productId: ProductId::fromString($item->getProductId()->getId()),
                productQuantity: new QuantityPositive($item->getProductQuantity()->getQuantity())
            );
        }
        $offer = $offerFactory->getOffer();

        $items = array_map(
            function ($offerItem) {
                /** @var OfferItem $offerItem */
                return [
                    'productId' => $offerItem->getProductId()->getId(),
                    'quantity' => $offerItem->getProductQuantity()->getQuantity(),
                    'priceInitial' => $offerItem->getPriceInitial()->getFractionalCount(),
                    'priceFinal' => $offerItem->getPriceFinal()->getFractionalCount(),
                ];
            },
            $offer->getItems()->toArray()
        );
        $data = [
            'items' => $items,
            'quantity' => $offer->getTotalQuantity(),
            'cost' => $offer->getTotalCost()->getFractionalCount()
        ];

        return $this->asJson($data);
    }
}
