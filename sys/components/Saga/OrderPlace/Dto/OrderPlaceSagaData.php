<?php

declare(strict_types=1);

namespace app\components\Saga\OrderPlace\Dto;

use app\components\Offer\Domain\Aggregate\Offer;
use app\components\Order\Domain\Aggregate\Order;
use app\components\Order\Domain\ValueObject\DeliveryParams;
use app\components\Order\Domain\ValueObject\OrderComment;
use app\components\Saga\Contract\SagaDataInterface;
use app\components\User\Domain\ValueObject\UserId;

class OrderPlaceSagaData implements SagaDataInterface
{
    private ?Offer $offer = null;
    private ?Order $order = null;
    private ?string $info = null;

    public function __construct(
        private readonly UserId $userId,
        /** @var ItemRaw[] $itemsRaw */
        private readonly array $itemsRaw,
        private readonly DeliveryParams $deliveryParams,
        private readonly OrderComment $orderComment
    ) {
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }
    public function getItemsRaw(): array
    {
        return $this->itemsRaw;
    }
    public function getDeliveryParams(): DeliveryParams
    {
        return $this->deliveryParams;
    }
    public function getOrderComment(): OrderComment
    {
        return $this->orderComment;
    }



    public function setOffer(?Offer $offer): void
    {
        $this->offer = $offer;
    }
    public function getOffer(): ?Offer
    {
        return $this->offer;
    }
    public function setOrder(?Order $order): void
    {
        $this->order = $order;
    }
    public function getOrder(): ?Order
    {
        return $this->order;
    }
    public function setInfo(?string $info): void
    {
        $this->info = $info;
    }
    public function getInfo(): ?string
    {
        return $this->info;
    }
}
