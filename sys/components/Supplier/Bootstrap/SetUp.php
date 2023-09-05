<?php

namespace app\components\Supplier\Bootstrap;

use app\components\Supplier\Domain\Contract\SupplierRepositoryInterface;
use app\components\Supplier\Infrastructure\SupplierRepository;
use Yii;
use yii\base\BootstrapInterface;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app): void
    {
        $container = Yii::$container;
        $container->set(SupplierRepositoryInterface::class, SupplierRepository::class);
    }
}
 
