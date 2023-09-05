<?php

declare(strict_types=1);

namespace app\components\Offer\Domain\Service\OfferSale;

use app\components\Offer\Domain\Aggregate\Offer;
use app\components\Offer\Domain\Contract\OfferItemCollection;
use app\components\Offer\Domain\Contract\OfferSaleInterface;
use app\components\Offer\Domain\Contract\SalePersonalBrandCategoryRepositoryInterface;
use app\components\Shared\Domain\ValueObject\Money;
use app\components\Shared\Domain\ValueObject\MoneyСurrency;
use app\components\Offer\Domain\ValueObject\OfferItem;
use app\components\Sale\Domain\ValueObject\SaleTypeId;

class TotalCostLevelUp implements OfferSaleInterface
{
    private SaleTypeId $saleTypeId;
    private int $totalCostFractionalCountOver = 10000; // over 1000$
    private int $saleFractionalCount = 50000; // -500$

    public function __construct()
    {
        $this->saleTypeId = SaleTypeId::TotalCostLevelUp;
    }

    public function getOffer(Offer $offer): Offer
    {
        $totalCost = $offer->getTotalCost();
        $saleFractionalCountRest = $this->saleFractionalCount;
        if ($totalCost->getFractionalCount() >= $this->totalCostFractionalCountOver) {
            $offerItems = $offer->getItems();
            foreach ($offerItems as $i => $offerItem) {
                /** @var OfferItem $offerItem */
                if ($saleFractionalCountRest <= 0) {
                    break;
                }
                $deltaForOneMax = intval(floor($saleFractionalCountRest / $offerItem->getProductQuantity()->getQuantity()));
                $itemPrice = $offerItem->getPriceFinal()->getFractionalCount();
                $deltaForOne = ($deltaForOneMax >= $itemPrice) ? ($itemPrice - 1) : $deltaForOneMax;
                $priceFinal = new Money(($itemPrice - $deltaForOne), MoneyСurrency::RUB);
                $saleFractionalCountRest -= $deltaForOne * $offerItem->getProductQuantity()->getQuantity();

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
