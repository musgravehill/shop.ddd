<?php

declare(strict_types=1);

namespace app\components\Cart\Infrastructure;

use app\components\Cart\Domain\Contract\CartRepositoryInterface;
use app\components\Cart\Domain\Contract\CartItemCollection;

use Yii;
use yii\helpers\Json;
use yii\web\Cookie;

class CartMemRepository implements CartRepositoryInterface
{
    private array $items=[];

    public function getAll(): CartItemCollection
    {
        return new CartItemCollection($this->items);
    }


    public function saveAll(CartItemCollection $items): void
    {
        $this->items = $items->toArray();
    }
}
