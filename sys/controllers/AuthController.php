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
use app\components\IAA\Authentication\AuthEmailPassword;
use app\components\IAA\AccessRecovery\AccessRecoveryEmail;
use app\components\IAA\AccessRecovery\AccessRecoveryToken;
use app\components\IAA\AccessRecovery\Form\FormAccessRecovery;
use app\components\IAA\AccessRecovery\Form\FormAccessRecoveryInit;
use app\components\IAA\Authentication\BanEmail;
use app\components\IAA\Authentication\Model\Contract\IdentityRepositoryInterface;
use app\components\IAA\Authentication\Model\ValueObject\IdentityId;
use app\components\IAA\Identification\Form\FormIn;
use app\components\User\Domain\Contract\UserRepositoryInterface;
use app\components\Shared\Domain\ValueObject\Email;

class AuthController extends Controller
{
    private UserRepositoryInterface $userRepository;
    private IdentityRepositoryInterface $identityRepository;

    public function __construct(
        $id,
        $module,
        UserRepositoryInterface $userRepository,
        IdentityRepositoryInterface $identityRepository,
        $config = []
    ) {
        $this->userRepository = $userRepository;
        $this->identityRepository = $identityRepository;
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
                        'actions' => ['captcha', 'accessrecoveryinit', 'accessrecovery',],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                    [
                        'actions' => ['in',],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout',],
                        'allow' => true,
                        'roles' => ['@'],
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

    public function actions()
    {
        return [
            'error' => [ //for config ErrorHandler 'site/error'
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength' => 3,
                'maxLength' => 4,
                'offset' => 4,
            ],
        ];
    }

    public function actionAccessrecovery()
    {
        $identityIdString = HelperY::getGet('identityId', null);
        $identityIdString = mb_substr($identityIdString, 0, 36, "UTF-8");

        $tokenString = HelperY::getGet('token', null);
        $tokenString = mb_substr($tokenString, 0, 36, "UTF-8");

        $formAccessRecovery = new FormAccessRecovery();
        if ($formAccessRecovery->load(Yii::$app->request->post()) && $formAccessRecovery->validate()) {
            $identityId = IdentityId::fromString($identityIdString);
            $accessRecoveryToken = AccessRecoveryToken::fromString($tokenString);
            $accessRecoveryEmail = Yii::$container->get(AccessRecoveryEmail::class);
            $accessRecoveryEmail->restoreAccess($identityId, $accessRecoveryToken, $formAccessRecovery->password);

            Yii::$app->session->addFlash('success', 'Пароль сохранен!');
            return $this->redirect(Url::to(['auth/in']));
        }

        return $this->render('accessrecovery', [
            'formAccessRecovery' => $formAccessRecovery,
        ]);
    }

    public function actionAccessrecoveryinit()
    {
        $formAccessRecoveryInit = new FormAccessRecoveryInit();
        if ($formAccessRecoveryInit->load(Yii::$app->request->post()) && $formAccessRecoveryInit->validate()) {

            $email = new Email($formAccessRecoveryInit->email);

            $ban = Yii::$container->get(BanEmail::class, ['email' => $email]);
            $bannedUntil = $ban->getBannedUntil();
            if (!is_null($bannedUntil)) {
                if ($bannedUntil->getTimestamp() - time()  > 0) {
                    Yii::$app->session->addFlash('danger', ' Доступ закрыт до ' . $bannedUntil->format('d-m-Y H:i') . '.');
                    return $this->goHome();
                }
            }

            $ban->onViolation();

            $accessRecoveryEmail = Yii::$container->get(AccessRecoveryEmail::class);
            $accessRecoveryEmail->initAccessRecovery($email);

            Yii::$app->session->addFlash('success', 'На указанный email отправлено письмо с ссылкой. Прочитайте письмо.');
            return $this->goHome();
        }
        return $this->render('accessrecoveryinit', [
            'formAccessRecoveryInit' => $formAccessRecoveryInit,
        ]);
    }

    public function actionIn()
    {
        $formIn = new FormIn();
        if ($formIn->load(Yii::$app->request->post()) && $formIn->validate()) {

            $email = new Email($formIn->email);

            $ban = Yii::$container->get(BanEmail::class, ['email' => $email]);
            $bannedUntil = $ban->getBannedUntil();
            if (!is_null($bannedUntil)) {
                if ($bannedUntil->getTimestamp() - time()  > 0) {
                    Yii::$app->session->addFlash('danger', ' Доступ закрыт до ' . $bannedUntil->format('d-m-Y H:i') . '.');
                    return $this->goHome();
                }
            }

            $authEmailPass  = Yii::$container->get(AuthEmailPassword::class, ['email' => $email, 'password' => $formIn->password]);
            if (!$authEmailPass->enter()) {
                $ban->onViolation();
                Yii::$app->session->addFlash('danger', 'Ошибка. Попробуйте еще раз или свяжитесь с нами.');
            } else {
                return $this->goHome();
            }
        }
        return $this->render('in', [
            'formIn' => $formIn,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}
