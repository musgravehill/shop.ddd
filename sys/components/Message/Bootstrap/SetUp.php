<?php

namespace app\components\Message\Bootstrap;

use app\components\Message\Domain\Contract\MessageRepositoryInterface;
use app\components\Message\Infrastructure\MessageRepository;
use Yii;
use yii\base\BootstrapInterface;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app): void
    {
        $container = Yii::$container;
        $container->set(MessageRepositoryInterface::class, MessageRepository::class);
    }
}
 
