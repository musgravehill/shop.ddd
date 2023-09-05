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

$lifetimeLogin = 60; //60s

$config = [
    'controllerNamespace' => 'app\commands',
    'id' => 'sdbn2console',
    'basePath' => dirname(__DIR__),
    'language' => 'ru-RU',
    'sourceLanguage' => 'en-US',
    'timeZone' => 'Europe/Moscow',
    'aliases' => [
        '@webroot' => dirname(dirname(__FILE__)) . '/web',
    ],
    'components' => [
        'urlManager' => [
            'hostInfo' => 'http://.com', /////console
            'baseUrl' => 'http://.com/', ////console
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [
                '/' => 'site/index',
            ],
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
        'assetManager' => $assetManager,
        'cache' => $cache,
        'db' => $db,
        'i18n' => $i18n,
        'log' => $log,
        'mailer' => $mailer,
    ],
    'params' => $params,
];

$config['params']['lifetimeLogin'] = $lifetimeLogin;

$config['bootstrap'] = $bootstrap;

return $config;
