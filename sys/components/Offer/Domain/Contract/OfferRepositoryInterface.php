<?php

declare(strict_types=1);

namespace app\components\Offer\Domain\Contract;

use app\components\Offer\Domain\ValueObject\OfferItem;
use app\components\Product\Domain\ValueObject\ProductId;
use app\components\Shared\Domain\ValueObject\QuantityPositive;

interface OfferRepositoryInterface
{
    public function getOfferItem(ProductId $productId, QuantityPositive $productQuantity): ?OfferItem;
}
