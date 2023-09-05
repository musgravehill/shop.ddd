<?php

declare(strict_types=1);

namespace app\components\Offer\Domain\Service\OfferSale;

use app\components\HelperY;
use app\components\Offer\Domain\Aggregate\Offer;
use app\components\Offer\Domain\Contract\OfferItemCollection;
use app\components\Offer\Domain\Contract\OfferSaleInterface;
use app\components\Shared\Domain\ValueObject\Money;
use app\components\Shared\Domain\ValueObject\MoneyСurrency;
use app\components\Offer\Domain\ValueObject\OfferItem;
use app\components\Sale\Domain\ValueObject\SaleTypeId;
use app\components\SalePersonalBrandCategory\Domain\Contract\SalePersonalBrandCategoryRepositoryInterface;
use app\components\Supplier\Domain\ValueObject\SupplierId;

class PersonalBrandCategory implements OfferSaleInterface
{
    private SaleTypeId $saleTypeId;

    public function __construct(
        private SalePersonalBrandCategoryRepositoryInterface $salePersonalBrandCategoryRepository
    ) {
        $this->saleTypeId = SaleTypeId::PersonalBrandCategory;
    }

    public function getOffer(Offer $offer): Offer
    {
        $seoSupplierId = SupplierId::fromString(HelperY::params('seoSupplierId'));
        $offerItems = $offer->getItems();
        foreach ($offerItems as $i => $offerItem) {
            /** @var OfferItem $offerItem */
            if (!$offerItem->getSupplierId()->isEqualsTo($seoSupplierId)) {
                continue;
            }

            $percent = $this->salePersonalBrandCategoryRepository->getPercent(
                userId: $offer->getUserId(),
                brandId: $offerItem->getBrandId(),
                brandCategoryId: $offerItem->getBrandCategoryId()
            );

            if ($percent > 0 && $percent < 100) {
                $fc = intval((100.0 - $percent) * ($offerItem->getPriceFinal()->getFractionalCount()) / 100);
                if ($fc < 1) {
                    $fc = 1;
                }
                $priceFinal = new Money($fc, MoneyСurrency::RUB);

                $offerItems[$i] = $offerItem->applySale(
                    priceFinal: $priceFinal,
                    saleTypeId: $this->saleTypeId
                );
            }
        }

        //immutable, not mutate, return new
        return Offer::create(
            userId: $offer->getUserId(),
            items: $offerItems
        );
    }
}
