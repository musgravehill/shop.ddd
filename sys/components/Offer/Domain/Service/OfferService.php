<?php

declare(strict_types=1);

namespace app\components\Offer\Domain\Service;

use app\components\Offer\Domain\Contract\OfferRepositoryInterface;
use app\components\User\Domain\ValueObject\UserId;
use app\components\Offer\Domain\Service\OfferFactory;
use app\components\Offer\Domain\Service\OfferSale as OfferSale;
use app\components\Offer\Infrastructure\OfferRepository;
use app\components\SalePersonalBrandCategory\Infrastructure\SalePersonalBrandCategoryRepository;
use InvalidArgumentException;

class OfferService
{
    private readonly OfferRepositoryInterface $offerRepository;

    public function __construct()
    {
        $this->offerRepository = new OfferRepository();
    }

    public function getOfferFactory(UserId $userId): OfferFactory
    {
        //$simple = new OfferSale\Simple();
        $salePersonalBrandCategoryRepository = new SalePersonalBrandCategoryRepository();
        $personalBrandCategory = new OfferSale\PersonalBrandCategory($salePersonalBrandCategoryRepository);
        //$totalCostLevelUp = new OfferSale\TotalCostLevelUp();

        $offerFactory = new OfferFactory(
            userId: $userId,
            offerRepository: $this->offerRepository
        );

        //$offerFactory->addMutator($simple);
        $offerFactory->addMutator($personalBrandCategory);
        //$offerFactory->addMutator($totalCostLevelUp);

        return $offerFactory;
    }
}
