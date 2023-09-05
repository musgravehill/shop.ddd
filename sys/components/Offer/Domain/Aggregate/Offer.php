<?php

declare(strict_types=1);

namespace app\components\Offer\Domain\Aggregate;

use app\components\Offer\Domain\Contract\OfferItemCollection;
use app\components\Offer\Domain\Contract\OfferSaleInterface;
use app\components\User\Domain\ValueObject\UserId;
use app\components\Offer\Domain\ValueObject\OfferItem;
use app\components\Shared\Domain\ValueObject\Money;
use app\components\Shared\Domain\ValueObject\MoneyСurrency;
use InvalidArgumentException;

class Offer
{
    private function __construct(
        private UserId $userId,
        private OfferItemCollection $items
    ) {
    }

    public static function create(
        UserId $userId,
        OfferItemCollection $items
    ): self {
        return new self(
            userId: $userId,
            items: $items
        );
    }

    public function getItems(): OfferItemCollection
    {
        return $this->items;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getTotalCost(): Money
    {
        if (empty($this->items)) {
            return new Money(fractionalCount: 0, currency: MoneyСurrency::RUB);
        }

        $fractionalCount = 0;
        foreach ($this->items as $item) {
            /** @var OfferItem $item */
            $fractionalCount += $item->getProductQuantity()->getQuantity() * $item->getPriceFinal()->getFractionalCount();
        }

        return new Money(fractionalCount: $fractionalCount, currency: MoneyСurrency::RUB);
    }

    public function getTotalQuantity(): int
    {
        /** @var OfferItem $item */
        return array_reduce(
            $this->items->toArray(),
            function ($accumulator, $item) {
                $accumulator += $item->getProductQuantity()->getQuantity();
                return $accumulator;
            }
        ) ?? 0;
    }

    //immutable
    public function applySale(OfferSaleInterface $mutator): self
    {
        return $mutator->getOffer($this);
    }
}
