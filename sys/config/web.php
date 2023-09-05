<?php

if (defined('ENV_DOCKER')) {
    $db = require(__DIR__ . '/_db_docker.php');
    $sph = require(__DIR__ . '/_sphinx_docker.php');
    $log = require(__DIR__ . '/_log_docker.php');
    $cache = require(__DIR__ . '/_cache_docker.php');
} else {
    $db = require(__DIR__ . '/_db.php');
    $sph = require(__DIR__ . '/_sphinx.php');
    $log = require(__DIR__ . '/_log.php');
    $cache = require(__DIR__ . '/_cache.php');
}

$assetManager = require(__DIR__ . '/_assetManager.php');
$mailer = require(__DIR__ . '/_mailer.php');
$params = require(__DIR__ . '/_params.php');
$i18n = require(__DIR__ . '/_i18n.php');
$bootstrap = require(__DIR__ . '/_bootstrap.php');

$lifetimeLogin = 3600 * 24 * 14; //14 days

$config = [
    'id' => 'sdbn2',
    'basePath' => dirname(__DIR__),
    'language' => 'ru-RU',
    'sourceLanguage' => 'en-US',
    'timeZone' => 'Europe/Moscow',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'request' => [
            'cookieValidationKey' => 'NGTcVa18-6E5SWAapL7oHtTF_HJUxYPj',
            'enableCsrfValidation' => true,
        ],
        'session' => [
            'class' => 'yii\web\Session',
            'name' => 'bzn',
            'timeout' => $lifetimeLogin,
            'cookieParams' => [
                'httponly' => true,
                'lifetime' => $lifetimeLogin,
            ],
            'useCookies' => true,
            //'savePath' => __DIR__ . '/../tmp',
        ],
        'user' => [
            'identityClass' => \app\components\IAA\Authentication\Model\Identity::class,
            'enableAutoLogin' => true,
            'authTimeout' => $lifetimeLogin,
            'loginUrl' => ['auth/in',],
        ],
        'urlManager' => [
            //'hostInfo' => 'http://.com', /////console
            //'baseUrl' => 'http://.com/', ////console
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [
                '/' => 'site/index',
                //0188351a-02ba-73c9-8ece-75801419f67e   
                '<controller:[\w\-_]+>/<action:[\w\-_]+>/<id:[0-9a-z]{8}\-[0-9a-z]{4}\-[0-9a-z]{4}\-[0-9a-z]{4}\-[0-9a-z]{12}>' => '<controller>/<action>',
                'b/<id:\d+>-<ufu:[0-9a-zA-Z\-]+>.html' => 'brand/view',
                'bc/<id:\d+>-<ufu:[0-9a-zA-Z\-]+>.html' => 'brandcategory/view',
                'p/<id:\d+>-<ufu:[0-9a-zA-Z\-]+>.html' => 'product/view',
                'page/<id:\d+>.html' => 'page/view',
                '<controller:[\w\-_]+>/<action:[\w\-_]+>/<id:\d+>' => '<controller>/<action>',
                '<controller:[\w\-_]+>/<action:[\w\-_]+>' => '<controller>/<action>',
            ],
        ],
        'assetManager' => $assetManager,
        'cache' => $cache,
        'db' => $db,
        'i18n' => $i18n,
        'log' => $log,
        'mailer' => $mailer,
        'sphinx' => $sph,
    ],
    'params' => $params,
];

$config['params']['lifetimeLogin'] = $lifetimeLogin;

$config['bootstrap'] = $bootstrap;

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['*'],
    ];
}

return $config;
