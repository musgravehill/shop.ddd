<?php

namespace app\components\Order\Bootstrap;

use app\components\Order\Infrastructure\OrderQueryRepository;
use app\components\Order\Infrastructure\OrderQueryRepositoryInterface;
use Yii;
use yii\base\BootstrapInterface;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app): void
    {
        $container = Yii::$container;
        $container->set(OrderQueryRepositoryInterface::class, OrderQueryRepository::class);
    }
}
