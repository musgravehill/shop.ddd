<?php

namespace app\components\Offer\Bootstrap;

use app\components\Offer\Domain\Service\OfferFactory;
use app\components\Offer\Domain\Service\OfferSale as OfferSale;
use app\components\Offer\Domain\Service\OfferService;
use app\components\User\Domain\ValueObject\UserId;
use app\components\Offer\Infrastructure\OfferRepository;
use app\components\Offer\Infrastructure\SalePersonalBrandCategoryRepository;

use Yii;
use yii\base\BootstrapInterface;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app): void
    {
        $container = Yii::$container;          

        $container->set(OfferFactory::class, function ($container, $params, $config) {
            $offerService = new OfferService();
            $offerFactory = $offerService->getOfferFactory($params['userId']);
            return $offerFactory;
        });
    }
}

//  ** @var \app\components\Offer\Domain\Service\OfferFactory $offerFactory */
//  $offerFactory = Yii::$container->get(\app\components\Offer\Domain\Service\OfferFactory::class, ['userId'=>1]);
