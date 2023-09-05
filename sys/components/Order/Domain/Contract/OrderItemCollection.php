<?php

declare(strict_types=1);

namespace app\components\Order\Domain\Contract;

use app\components\Order\Domain\Entity\OrderItem;
use app\components\Shared\Domain\Contract\ItemCollection;
use InvalidArgumentException;

final class OrderItemCollection extends ItemCollection
{
    protected function validate($value): void
    {
        if (!($value instanceof OrderItem)) {
            throw new InvalidArgumentException('Not an instanse of ...');
        }
    }    
}
