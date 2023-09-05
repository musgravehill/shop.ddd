<?php

namespace app\components\User\Bootstrap;

use app\components\User\Domain\Contract\UserRepositoryInterface;
use app\components\User\Infrastructure\UserRepository;
use Yii;
use yii\base\BootstrapInterface;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app): void
    {
        $container = Yii::$container;
        $container->set(UserRepositoryInterface::class, UserRepository::class);
    }
}
 
