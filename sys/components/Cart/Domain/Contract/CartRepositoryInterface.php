<?php

declare(strict_types=1);

namespace app\components\Cart\Domain\Contract;

use app\components\Cart\Domain\Contract\CartItemCollection;
use app\components\Cart\Domain\ValueObject\CartItem;

interface CartRepositoryInterface
{    
    public function getAll(): CartItemCollection;
    
    public function saveAll(CartItemCollection $items): void;    
}
