<?php

namespace app\controllers;

use app\components\Brand\Domain\Contract\BrandRepositoryInterface;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\HttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\components\IAA\Authorization\AccessRule;
//
use app\components\HelperY;
use app\components\Shared\Domain\ValueObject\CountOnPage;
use yii\helpers\Url;
use yii\helpers\Html;


class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                // We will override the default rule config with the new AccessRule class
                'ruleConfig' => [
                    'class' => AccessRule::class,
                ],
                //'only' => ['register', 'login', 'logout', 'confirmphoneinit', 'confirmphone', 'confirmemailinit', 'confirmemail'],
                'rules' => [
                    [
                        'actions' => ['index', 'error'],
                        'allow' => true,
                        'roles' => ['?', '@'],
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

    public function actionError()
    {
        return $this->render('error');
    }

    public function actionIndex()
    {
        return $this->render('/site/index', []);
    }
}
