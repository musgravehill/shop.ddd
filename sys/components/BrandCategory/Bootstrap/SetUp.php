<?php

namespace app\components\BrandCategory\Bootstrap;

use app\components\BrandCategory\Domain\Contract\BrandCategoryRepositoryInterface;
use app\components\BrandCategory\Infrastructure\BrandCategoryRepository;
use Yii;
use yii\base\BootstrapInterface;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app): void
    {
        $container = Yii::$container;
        $container->set(BrandCategoryRepositoryInterface::class, BrandCategoryRepository::class);
    }
}
 
