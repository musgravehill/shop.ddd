<?php

declare(strict_types=1);

namespace app\components\Offer\Domain\Service\OfferSale;

use app\components\Offer\Domain\Aggregate\Offer;
use app\components\Offer\Domain\Contract\OfferItemCollection;
use app\components\Offer\Domain\Contract\OfferSaleInterface;

class Simple implements OfferSaleInterface
{
    public function getOffer(Offer $offer): Offer
    {
        //immutable, not mutate, return new
        return Offer::create(
            userId: $offer->getUserId(),
            items: $offer->getItems()
        );
    }
}
