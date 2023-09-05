<?php

declare(strict_types=1);

namespace app\components\Offer\tests;


use PHPUnit\Framework\TestCase;
use app\components\Offer\Domain\Aggregate\Offer;
use app\components\Offer\Domain\Contract\OfferItemCollection;
use app\components\Offer\Domain\Service\OfferFactory;
use app\components\User\Domain\ValueObject\UserId;
use app\components\Offer\Infrastructure\OfferRepository;

final class OfferFactoryTest extends TestCase
{

    public function testGuestNoItems(): void
    {
        $userId = UserId::fromString(null);
        $productRepository = new OfferRepository();
        $offerFactory = new OfferFactory(
            userId: $userId,
            productRepository: $productRepository
        );
        $offer = $offerFactory->getOffer();
        $this->assertInstanceOf(
            expected: Offer::class,
            actual: $offer
        );
    }

    public function testGuest0(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $userId = UserId::fromString('0');
        $productRepository = new OfferRepository();
        $offerFactory = new OfferFactory(
            userId: $userId,
            productRepository: $productRepository
        );
    }
}
