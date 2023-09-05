<?php

namespace app\controllers;

use app\components\HelperCache;
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
use app\components\Message\Domain\Entity\Message;
use app\components\Message\Domain\ValueObject\Phone;
use app\components\Message\Domain\ValueObject\Txt;
use app\components\Message\Domain\ValueObject\Url as ValueObjectUrl;
use app\components\Message\Domain\ValueObject\Username;
use app\components\Message\Infrastructure\MessagePushService;
use app\components\Shared\Domain\ValueObject\CountOnPage;
use app\components\Shared\Domain\ValueObject\Email;
use app\components\Shared\Domain\ValueObject\PageNumber;
use app\components\User\Domain\Contract\UserRepositoryInterface;
//

use DateTimeImmutable;

class MessageController extends \yii\web\Controller
{
    private MessagePushService $messagePushService;
    private UserRepositoryInterface $userRepository;

    public function __construct(
        $id,
        $module,
        MessagePushService $messagePushService,
        UserRepositoryInterface $userRepository,
        $config = []
    ) {
        $this->messagePushService = $messagePushService;
        $this->userRepository = $userRepository;
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
                'rules' => [
                    [
                        'actions' => ['send'],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                ],
            ],
        ];
    }



    public function actionSend()
    {
        if (Yii::$app->request->post()) {
            if (IdentityService::userIsGuest()) {
                $username = new Username(Username::prepare(HelperY::getPost('username', '-')));
                $email = new Email(HelperY::getPost('email', '-'));
                $phone = new Phone(Phone::prepare(HelperY::getPost('phone', '-')));
            } else {
                $user = $this->userRepository->getById(IdentityService::userId());
                $username = new Username($user->getUsername()->getUsername());
                $email = new Email($user->getEmail()->getEmail());
                $phone = new Phone($user->getPhone()->getPhone());
            }
            $txt = new Txt(Txt::prepare(HelperY::getPost('txt', '-')));
            $url = new ValueObjectUrl(ValueObjectUrl::prepare(Yii::$app->request->referrer));

            $message = Message::new(
                username: $username,
                email: $email,
                phone: $phone,
                txt: $txt,
                createdAt: new DateTimeImmutable(),
                url: $url,
            );

            $message = $this->messagePushService->pushToCrm($message);

            if (!is_null($message->getId()->getId())) {
                Yii::$app->session->addFlash('success', 'Готово! Сообщение №' . $message->getId()->getId() . ' принято.');
            } else {
                Yii::$app->session->addFlash('danger', 'Отправить не получается!');
            }
        }

        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }
}
