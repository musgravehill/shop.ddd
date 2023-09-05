<?php

namespace app\components\IAA\Bootstrap;

use app\components\IAA\AccessRecovery\AccessRecoveryTokenRepository;
use app\components\IAA\AccessRecovery\AccessRecoveryTokenRepositoryInterface;
use app\components\IAA\Authentication\Model\Contract\IdentityRepositoryInterface;
use app\components\IAA\Infrastructure\IdentityRepository;
use Yii;
use yii\base\BootstrapInterface;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app): void
    {
        $container = Yii::$container;
        $container->set(IdentityRepositoryInterface::class, IdentityRepository::class);

        $container->set(AccessRecoveryTokenRepositoryInterface::class, AccessRecoveryTokenRepository::class);
        $container->set(\yii\mail\MailerInterface::class, function ($container, $params, $config) {            
            return Yii::$app->mailer;
        });
    }
}
 
