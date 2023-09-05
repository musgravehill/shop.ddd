<?php

declare(strict_types=1);

namespace app\components\Offer\Domain\Service;

use app\components\Offer\Domain\Aggregate\Offer;
use app\components\Offer\Domain\Contract\OfferRepositoryInterface;
use app\components\Offer\Domain\Contract\OfferItemCollection;
use app\components\Offer\Domain\Contract\OfferSaleInterface;
use app\components\Product\Domain\ValueObject\ProductId;
use app\components\Shared\Domain\ValueObject\QuantityPositive;
use app\components\User\Domain\ValueObject\UserId;
use InvalidArgumentException;

class OfferFactory
{
    private array $mutators = [];
    private OfferItemCollection $items;

    public function __construct(
        private UserId $userId,
        private OfferRepositoryInterface $offerRepository
    ) {
        $this->items = new OfferItemCollection();
    }

    public function addItem(ProductId $productId, QuantityPositive $productQuantity): void
    {        
        $offerItem = $this->offerRepository->getOfferItem($productId, $productQuantity);
        if (is_null($offerItem)) {
            return;
        }
        $this->items[] = $offerItem;
    }

    public function addMutator(OfferSaleInterface $mutator)
    {
        $this->mutators[] = $mutator;
    }

    public function getOffer(): Offer
    {
        $offer = Offer::create(
            userId: $this->userId,
            items: $this->items
        );           

        foreach ($this->mutators as $mutator) {
            $offer = $offer->applySale($mutator);
        }

        return $offer;
    }
}
