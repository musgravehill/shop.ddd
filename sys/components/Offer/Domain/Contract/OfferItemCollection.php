<?php

declare(strict_types=1);

namespace app\components\Offer\Domain\Contract;

use app\components\Offer\Domain\ValueObject\OfferItem;
use app\components\Shared\Domain\Contract\ItemCollection;
use InvalidArgumentException;

final class OfferItemCollection extends ItemCollection
{
    protected function validate($value): void
    {
        if (!($value instanceof OfferItem)) {
            throw new InvalidArgumentException('Not an instanse of ...');
        }
    }    
}
