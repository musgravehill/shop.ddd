<?php

declare(strict_types=1);

namespace app\components\Order\App\DTO;

use app\components\Order\App\DTO\OrderItemResponce;
use DateTimeImmutable;

class OrderResponce
{
    public function __construct(
        private readonly string $orderId,
        private readonly string $userFriendlyOrderId,
        private readonly string $deliveryTypeId,
        private readonly string $deliveryCityName,
        private readonly string $orderComment,
        private readonly DateTimeImmutable $createdAt,
        private readonly int $priceTotalFractionalCount,

        private readonly string $userId,
        private readonly string $customerName,
        private readonly string $customerEmail,
        private readonly string $customerPhone
    ) {
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function getUserFriendlyOrderId(): string
    {
        return $this->userFriendlyOrderId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getDeliveryTypeId(): string
    {
        return $this->deliveryTypeId;
    }

    public function getDeliveryCityName(): string
    {
        return $this->deliveryCityName;
    }

    public function getOrderComment(): string
    {
        return $this->orderComment;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getPriceTotalFractionalCount(): int
    {
        return $this->priceTotalFractionalCount;
    }

    public function getCustomerName(): string
    {
        return $this->customerName;
    }

    public function getCustomerEmail(): string
    {
        return $this->customerEmail;
    }

    public function getCustomerPhone(): string
    {
        return $this->customerPhone;
    }
}
