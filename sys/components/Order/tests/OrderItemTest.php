<?php

declare(strict_types=1);

namespace app\components\Order\tests;

use app\components\Order\Domain\Contract\AppliedSaleTypeIdCollection;
use app\components\Order\Domain\Entity\OrderItem;
use app\components\Order\Domain\ValueObject\OrderItemId;
use app\components\Product\Domain\ValueObject\ProductId;
use app\components\Shared\Domain\ValueObject\QuantityPositive;
use app\components\Shared\Domain\ValueObject\Money;
use app\components\Shared\Domain\ValueObject\MoneyСurrency;
use PHPUnit\Framework\TestCase;

final class OrderItemTest extends TestCase
{
    public function testCreation(): void
    {
        $orderItem = OrderItem::initNew(
            id: OrderItemId::fromString('00ccebbc-13e0-7000-8b18-6150ad2d0c05'),
            productId: ProductId::fromString('3461'),
            quantity: new QuantityPositive(1),
            priceInitial: new Money(fractionalCount: 999, currency: MoneyСurrency::RUB),
            priceFinal: new Money(fractionalCount: 888, currency: MoneyСurrency::RUB),
            appliedSaleTypeIds: new AppliedSaleTypeIdCollection()
        );
        $this->assertInstanceOf(
            expected: OrderItem::class,
            actual: $orderItem
        );
    }
}
