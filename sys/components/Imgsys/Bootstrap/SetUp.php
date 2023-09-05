<?php

namespace app\components\Imgsys\Bootstrap;

use app\components\Imgsys\Domain\Contract\ImgsysRepositoryInterface;
use app\components\Imgsys\Infrastructure\ImgsysRepository;
use Yii;
use yii\base\BootstrapInterface;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app): void
    {
        $container = Yii::$container;          
        $container->set(ImgsysRepositoryInterface::class, ImgsysRepository::class);
        
    }
}
 