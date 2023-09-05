<?php

declare(strict_types=1);

namespace app\components\Order\App\DTO;

class OrderItemResponce
{
    public function __construct(
        private readonly string $orderItemId,
        private readonly string $productId,
        private readonly int $quantity,
        private readonly int $priceInitialFractionalCount,
        private readonly int $priceFinalFractionalCount,
        private readonly array $appliedSaleTypeIds,
        private readonly string $productName,
        private readonly string $productUfu,
    ) {
    }

    public function getOrderItemId()
    {
        return $this->orderItemId;
    }


    public function getProductId()
    {
        return $this->productId;
    }


    public function getQuantity()
    {
        return $this->quantity;
    }


    public function getPriceInitialFractionalCount()
    {
        return $this->priceInitialFractionalCount;
    }


    public function getPriceFinalFractionalCount()
    {
        return $this->priceFinalFractionalCount;
    }


    public function getAppliedSaleTypeIds()
    {
        return $this->appliedSaleTypeIds;
    }

    public function getProductName()
    {
        return $this->productName;
    }

    public function getProductUfu()
    {
        return $this->productUfu;
    }
}
