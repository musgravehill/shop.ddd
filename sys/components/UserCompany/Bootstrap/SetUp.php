<?php

namespace app\components\UserCompany\Bootstrap;

use app\components\UserCompany\Domain\Contract\UserCompanyRepositoryInterface;
use app\components\UserCompany\Infrastructure\UserCompanyRepository;
use Yii;
use yii\base\BootstrapInterface;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app): void
    {
        $container = Yii::$container;
        $container->set(UserCompanyRepositoryInterface::class, UserCompanyRepository::class);
    }
}
