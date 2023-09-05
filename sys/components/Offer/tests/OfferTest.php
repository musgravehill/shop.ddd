<?php

declare(strict_types=1);

namespace app\components\Offer\tests;

use PHPUnit\Framework\TestCase;
use app\components\Offer\Domain\Aggregate\Offer;
use app\components\Offer\Domain\Service\OfferSale as OfferSale;
use app\components\Offer\Domain\Service\OfferFactory;
use app\components\User\Domain\ValueObject\UserId;
use app\components\Product\Domain\ValueObject\ProductId;
use app\components\Shared\Domain\ValueObject\QuantityPositive;
use app\components\Offer\Infrastructure\OfferRepositoryMock;

final class OfferTest extends TestCase
{
    public function testMutator(): void
    {
        $userId = UserId::fromString(null);
        $productRepository = new OfferRepositoryMock();
        $offerFactory = new OfferFactory(
            userId: $userId,
            productRepository: $productRepository
        );
        $simple = new OfferSale\Simple();
        $offerFactory->addMutator($simple);

        $offerFactory->addItem(ProductId::fromString('1'), new QuantityPositive(1));
        $offerFactory->addItem(ProductId::fromString('2'), new QuantityPositive(2));
        $offer = $offerFactory->getOffer();

        $this->assertInstanceOf(
            expected: Offer::class,
            actual: $offer
        );
        $this->assertEquals(
            expected: 1 * 10 + 2 * 20,
            actual: $offer->getTotalCost()->getFractionalCount()
        );
    }

    public function testTrue()
    {
        $this->assertTrue(true);
    }
}
