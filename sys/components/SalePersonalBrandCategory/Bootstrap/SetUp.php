<?php

namespace app\components\SalePersonalBrandCategory\Bootstrap;

use app\components\SalePersonalBrandCategory\Domain\Contract\SalePersonalBrandCategoryRepositoryInterface;
use app\components\SalePersonalBrandCategory\Infrastructure\SalePersonalBrandCategoryRepository;
use Yii;
use yii\base\BootstrapInterface;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app): void
    {
        $container = Yii::$container;
        $container->set(SalePersonalBrandCategoryRepositoryInterface::class, SalePersonalBrandCategoryRepository::class);
    }
}
 
