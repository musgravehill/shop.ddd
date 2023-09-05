<?php

declare(strict_types=1);

namespace app\components\Offer\Domain\Contract;

use app\components\Offer\Domain\Aggregate\Offer;

interface OfferSaleInterface
{
    public function getOffer(Offer $offer): Offer;
}
