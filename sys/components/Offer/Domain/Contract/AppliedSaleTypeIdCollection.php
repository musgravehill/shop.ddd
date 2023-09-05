<?php

declare(strict_types=1);

namespace app\components\Offer\Domain\Contract;

use app\components\Sale\Domain\ValueObject\SaleTypeId;
use app\components\Shared\Domain\Contract\ItemCollection;
use InvalidArgumentException;

final class AppliedSaleTypeIdCollection extends ItemCollection
{
    protected function validate($value): void
    {
        if (!($value instanceof SaleTypeId)) {
            throw new InvalidArgumentException('Not an instanse of ...');
        }
    }
}
