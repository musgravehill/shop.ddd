<?php

declare(strict_types=1);

namespace app\components\Cart\Domain\Contract;

use app\components\Cart\Domain\ValueObject\CartItem;
use app\components\Shared\Domain\Contract\ItemCollection;
use InvalidArgumentException;

final class CartItemCollection extends ItemCollection
{
    protected function validate($value): void
    {
        if (!($value instanceof CartItem)) {
            throw new InvalidArgumentException('Not an instanse of ...');
        }
    }    
}
