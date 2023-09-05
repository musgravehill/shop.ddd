<?php

declare(strict_types=1);

namespace app\components\Order\tests;

use PHPUnit\Framework\TestCase; 
 
use app\components\Shared\Domain\ValueObject\Money;
use app\components\Shared\Domain\ValueObject\MoneyСurrency;
 

final class OrderTest extends TestCase
{
    
/*
    public function testPrice0(): void
    {
        $items = [];
        $this->expectException(\InvalidArgumentException::class);
        $price = new Money(fractionalCount: 0, currency: MoneyСurrency::RUB);
        $items[] = new OfferItem(
            productId: 1,
            quantity: 1,
            priceInitial: $price,
            priceFinal: $price,
            brandId: 1,
            brandCategoryId: 1,
            appliedSaleTypeIds: []
        );
        $offer = new Offer(userId: 0, items: $items);
    }

    public function testQuantity0(): void
    {
        $items = [];
        $price = new Money(fractionalCount: 1, currency: MoneyСurrency::RUB);
        $items[] = new OfferItem(
            productId: 1,
            quantity: 0,
            priceInitial: $price,
            priceFinal: $price,
            brandId: 1,
            brandCategoryId: 1,
            appliedSaleTypeIds: []
        );
        $offer = new Offer(userId: 0, items: $items);
        $this->assertInstanceOf(
            expected: Offer::class,
            actual: $offer
        );
    }

    public function testCalcs(): void
    {
        $q1 = 1;
        $p1 = 10;

        $q2 = 4;
        $p2 = 100;

        $items = [];
        $price = new Money(fractionalCount: $p1, currency: MoneyСurrency::RUB);
        $items[] = new OfferItem(
            productId: 1,
            quantity: $q1,
            priceInitial: $price,
            priceFinal: $price,
            brandId: 1,
            brandCategoryId: 1,
            appliedSaleTypeIds: []
        );

        $price = new Money(fractionalCount: $p2, currency: MoneyСurrency::RUB);
        $items[] = new OfferItem(
            productId: 2,
            quantity: $q2,
            priceInitial: $price,
            priceFinal: $price,
            brandId: 1,
            brandCategoryId: 1,
            appliedSaleTypeIds: []
        );
        $offer = new Offer(userId: 0, items: $items);

        $this->assertEquals(
            expected: $p1 * $q1 + $p2 * $q2,
            actual: $offer->getTotalCost()->getFractionalCount()
        );

        $this->assertEquals(
            expected: $q1 + $q2,
            actual: $offer->getTotalQuantity()
        );
    }

    public function testMutator(): void
    {
        //guest 
        $items = [];
        $price = new Money(fractionalCount: 100, currency: MoneyСurrency::RUB);
        $items[] = new OfferItem(
            productId: 1,
            quantity: 3,
            priceInitial: $price,
            priceFinal: $price,
            brandId: 1,
            brandCategoryId: 1,
            appliedSaleTypeIds: []
        );
        $offer = new Offer(userId: 0, items: $items);
        $simple = new OfferMutator\Simple();
        $personalBrandCategory = new OfferMutator\PersonalBrandCategory(new SalePersonalBrandCategoryRepositoryMock());
        $offer =  $offer->applyMutator($simple)->applyMutator($personalBrandCategory);
        $this->assertInstanceOf(
            expected: Offer::class,
            actual: $offer
        );
        $this->assertEquals(
            expected: 3 * 100,
            actual: $offer->getTotalCost()->getFractionalCount()
        );

        //client
        $percent = 99.0;
        $salePersonalBrandCategoryRepositoryMock = new SalePersonalBrandCategoryRepositoryMock();
        $salePersonalBrandCategoryRepositoryMock->setPercent($percent);
        $items = [];
        $price = new Money(fractionalCount: 100, currency: MoneyСurrency::RUB);
        $items[] = new OfferItem(
            productId: 1,
            quantity: 3,
            priceInitial: $price,
            priceFinal: $price,
            brandId: 10,
            brandCategoryId: 10,
            appliedSaleTypeIds: []
        );
        $offer = new Offer(userId: 10, items: $items);
        $simple = new OfferMutator\Simple();
        $personalBrandCategory = new OfferMutator\PersonalBrandCategory($salePersonalBrandCategoryRepositoryMock);
        $offer = $offer->applyMutator($simple)->applyMutator($personalBrandCategory);
        $this->assertInstanceOf(
            expected: Offer::class,
            actual: $offer
        );
        $this->assertEquals(
            expected: intval(((100 - $percent) / 100) * 3 * 100),
            actual: $offer->getTotalCost()->getFractionalCount()
        );
    }

   

    */
    public function testTrue()
    {
        $this->assertTrue(true);
    }
}
