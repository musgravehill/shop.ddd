<?php

declare(strict_types=1);

namespace app\components\Shared\tests;

use app\components\Shared\Domain\ValueObject\Identifier\IdUUIDv7;
use app\components\Shared\Domain\ValueObject\Money;
use app\components\Shared\Domain\ValueObject\MoneyСurrency;
use app\components\Shared\Domain\ValueObject\QuantityPositive;
use PHPUnit\Framework\TestCase;

final class QuantityPositiveTest extends TestCase
{
    public function testNormal(): void
    {
        $id = new QuantityPositive(99);
        $this->assertInstanceOf(
            expected: QuantityPositive::class,
            actual: $id
        );
    }
    public function testZero(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $id = new QuantityPositive(0);
    }
    public function testMinus(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $id = new QuantityPositive(-999);
    }

    public function testEquals(): void
    {
        $id1 = new QuantityPositive(99);
        $id1_1 = new QuantityPositive(99);
        $id2 = new QuantityPositive(1);
        $this->assertTrue($id1->isEqualsTo($id1_1));
        $this->assertFalse($id1->isEqualsTo($id2));
    }

    public function testEqualsForeign(): void
    {
        $id1 = new QuantityPositive(99);
        $id2 = new Money(
            fractionalCount: 999,
            currency: MoneyСurrency::RUB
        );
        $this->expectException(\InvalidArgumentException::class);
        $id1->isEqualsTo($id2);
    }
    
}
