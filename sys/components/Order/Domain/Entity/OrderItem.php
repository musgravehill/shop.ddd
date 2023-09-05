<?php

declare(strict_types=1);

namespace app\components\Order\Domain\Entity;

use app\components\Order\Domain\Contract\AppliedSaleTypeIdCollection;
use app\components\Order\Domain\ValueObject\OrderItemId;
use app\components\Product\Domain\ValueObject\ProductId;
use app\components\Shared\Domain\ValueObject\QuantityPositive;
use app\components\Shared\Domain\ValueObject\Money;
use InvalidArgumentException;

/** @psalm-immutable */
final class OrderItem
{
    //self-validation
    private function __construct(
        private readonly OrderItemId $id,
        private readonly ProductId $productId,
        private readonly QuantityPositive $quantity,
        private readonly Money $priceInitial,
        private readonly Money $priceFinal,
        private readonly AppliedSaleTypeIdCollection $appliedSaleTypeIds
    ) {
        if ($this->priceInitial->getFractionalCount() <= 0 || $this->priceFinal->getFractionalCount() <= 0) {
            throw new InvalidArgumentException('Rule: price > 0.');
        }
    }

    public static function initNew(
        OrderItemId $id,
        ProductId $productId,
        QuantityPositive $quantity,
        Money $priceInitial,
        Money $priceFinal,
        AppliedSaleTypeIdCollection $appliedSaleTypeIds
    ): self {
        return new self(
            id: $id,
            productId: $productId,
            quantity: $quantity,
            priceInitial: $priceInitial,
            priceFinal: $priceFinal,
            appliedSaleTypeIds: $appliedSaleTypeIds
        );
    }

    public static function initExisting(
        OrderItemId $id,
        ProductId $productId,
        QuantityPositive $quantity,
        Money $priceInitial,
        Money $priceFinal,
        AppliedSaleTypeIdCollection $appliedSaleTypeIds
    ): self {
        if (is_null($id->getId())) {
            throw new InvalidArgumentException('Rule: id not null.');
        }
        return new self(
            id: $id,
            productId: $productId,
            quantity: $quantity,
            priceInitial: $priceInitial,
            priceFinal: $priceFinal,
            appliedSaleTypeIds: $appliedSaleTypeIds
        );
    }

    public function getId(): OrderItemId
    {
        return $this->id;
    }

    public function getProductId(): ProductId
    {
        return $this->productId;
    }

    public function getQuantity(): QuantityPositive
    {
        return $this->quantity;
    }

    public function getPriceInitial(): Money
    {
        return $this->priceInitial;
    }

    public function getPriceFinal(): Money
    {
        return $this->priceFinal;
    }

    public function getAppliedSaleTypeIds(): AppliedSaleTypeIdCollection
    {
        return $this->appliedSaleTypeIds;
    }
}
