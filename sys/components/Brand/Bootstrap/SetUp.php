<?php

namespace app\components\Brand\Bootstrap;

use app\components\Brand\Domain\Contract\BrandRepositoryInterface;
use app\components\Brand\Infrastructure\BrandRepository;
use Yii;
use yii\base\BootstrapInterface;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app): void
    {
        $container = Yii::$container;
        $container->set(BrandRepositoryInterface::class, BrandRepository::class);
    }
}
 
