<?php

declare(strict_types=1);

namespace app\components\Shared\tests;

use app\components\Shared\Domain\ValueObject\Money;
use app\components\Shared\Domain\ValueObject\MoneyСurrency;
use PHPUnit\Framework\TestCase;

final class MoneyTest extends TestCase
{
    public function testNormal(): void
    {
        $id = new Money(
            fractionalCount: 999,
            currency: MoneyСurrency::RUB
        );
        $this->assertInstanceOf(
            expected: Money::class,
            actual: $id
        );
    }
    public function testZero(): void
    {
        $id = new Money(
            fractionalCount: 0,
            currency: MoneyСurrency::RUB
        );
        $this->assertInstanceOf(
            expected: Money::class,
            actual: $id
        );
    }
    public function testMinus(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $id = new Money(
            fractionalCount: -999,
            currency: MoneyСurrency::RUB
        );
    }

    public function testEquals(): void
    {
        $id1 = new Money(
            fractionalCount: 999,
            currency: MoneyСurrency::RUB
        );
        $id1_1 = new Money(
            fractionalCount: 999,
            currency: MoneyСurrency::RUB
        );
        $id2 = new Money(
            fractionalCount: 1,
            currency: MoneyСurrency::RUB
        );
        $this->assertTrue($id1->isEqualsTo($id1_1));
        $this->assertFalse($id1->isEqualsTo($id2));
    }    

    public function testSumWith(): void
    {
        $id1 = new Money(
            fractionalCount: 999,
            currency: MoneyСurrency::RUB
        );
        $id2 = new Money(
            fractionalCount: 1,
            currency: MoneyСurrency::RUB
        );
        $idSum = $id1->getSumWith($id2);
        $this->assertEquals(
            expected: 1000,
            actual: $idSum->getFractionalCount()
        );

        //currency
        $id1 = new Money(
            fractionalCount: 999,
            currency: MoneyСurrency::USD
        );
        $id2 = new Money(
            fractionalCount: 1,
            currency: MoneyСurrency::RUB
        );
        $this->expectException(\InvalidArgumentException::class);
        $idSum = $id1->getSumWith($id2);
    }
}
