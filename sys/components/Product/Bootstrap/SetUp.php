<?php

namespace app\components\Product\Bootstrap;

use app\components\Product\Domain\Contract\ProductRepositoryInterface;
use app\components\Product\Infrastructure\ProductRepository;
use Yii;
use yii\base\BootstrapInterface;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app): void
    {
        $container = Yii::$container;
        $container->set(ProductRepositoryInterface::class, ProductRepository::class);
    }
}
 
