<?php

declare(strict_types=1);

namespace app\components\Order\Domain\Aggregate;

use app\components\Order\Domain\Contract\OrderItemCollection;
use app\components\Order\Domain\Entity\OrderItem;
use app\components\User\Domain\ValueObject\UserId;
use app\components\Order\Domain\ValueObject\DeliveryParams;
use app\components\Order\Domain\ValueObject\OrderComment;
use app\components\Order\Domain\ValueObject\OrderId;
use app\components\Order\Domain\ValueObject\OrderUserFriendlyId;
use app\components\Shared\Domain\ValueObject\Money;
use app\components\Shared\Domain\ValueObject\MoneyСurrency;
use app\components\User\Domain\Entity\User;
use app\components\User\Domain\ValueObject\Phone;
use app\components\User\Domain\ValueObject\Username;
use app\components\UserCompany\Domain\Entity\UserCompany;
use DateTimeImmutable;
use InvalidArgumentException;

class Order
{
    private function __construct(
        private readonly OrderId $id,
        private readonly OrderUserFriendlyId $orderUserFriendlyId,
        private readonly UserId $userId,
        private readonly DeliveryParams $deliveryParams,
        private readonly OrderItemCollection $items,
        private readonly OrderComment $orderComment,
        private readonly DateTimeImmutable $createdAt
    ) {
    }

    public function getPriceTotal(): Money
    {
        $priceTotal = new Money(
            fractionalCount: 0,
            currency: MoneyСurrency::RUB
        );
        foreach ($this->getItems() as $orderItem) {
            /** @var OrderItem  $orderItem*/
            $qty = $orderItem->getQuantity()->getQuantity();
            $priceFinal = $orderItem->getPriceFinal();
            $priceSubTotal = $priceFinal->multiplyBy($qty);
            $priceTotal = $priceTotal->getSumWith($priceSubTotal);
        }
        return $priceTotal;
    }

    /* Always-Valid Domain Model or Fast Fail */
    /* Explicit method name in ubiquitous language */
    public static function initNew(
        OrderId $id,
        OrderUserFriendlyId $orderUserFriendlyId,
        UserId $userId,
        DeliveryParams $deliveryParams,
        OrderItemCollection $items,
        OrderComment $orderComment,
        DateTimeImmutable $createdAt
    ): self {
        $order = new self(
            id: $id,
            orderUserFriendlyId: $orderUserFriendlyId,
            userId: $userId,
            deliveryParams: $deliveryParams,
            items: $items,
            orderComment: $orderComment,
            createdAt: $createdAt
        );
        return $order;
    }

    /* Always-Valid Domain Model or Fast Fail */
    /* Explicit method name in ubiquitous language */
    public static function hydrateExisting(
        OrderId $id,
        OrderUserFriendlyId $orderUserFriendlyId,
        UserId $userId,
        DeliveryParams $deliveryParams,
        OrderItemCollection $items,
        OrderComment $orderComment,
        DateTimeImmutable $createdAt
    ): self {

        if (is_null($id->getId())) {
            throw new InvalidArgumentException(' Rule: id not null. ');
        }

        if (is_null($orderUserFriendlyId->getId())) {
            throw new InvalidArgumentException(' Rule: orderUserFriendlyId not null. ');
        }

        $order = new self(
            id: $id,
            orderUserFriendlyId: $orderUserFriendlyId,
            userId: $userId,
            deliveryParams: $deliveryParams,
            items: $items,
            orderComment: $orderComment,
            createdAt: $createdAt
        );
        return $order;
    }

    public static function canUserPlaceOrder(?User $user, ?UserCompany $userCompany): bool
    {
        if (is_null($user) || is_null($userCompany)) {
            return false;
        }

        // $user->getEmail()
        if ($user->getPhone()->isEqualsTo(Phone::dummy())) {
            return false;
        }
        if ($user->getUsername()->isEqualsTo(Username::dummy())) {
            return false;
        }

        // $userCompany->getKpp()->getKpp();
        if (
            !$userCompany->getBik()->getBik() ||
            !$userCompany->getInn()->getInn() ||
            !$userCompany->getName()->getName() ||
            !$userCompany->getRs()->getRs()
        ) {
            return false;
        }

        return true;
    }

    public function getId(): OrderId
    {
        return $this->id;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getDeliveryParams(): DeliveryParams
    {
        return $this->deliveryParams;
    }

    public function getOrderComment(): OrderComment
    {
        return $this->orderComment;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getItems(): OrderItemCollection
    {
        return $this->items;
    }

    public function getOrderUserFriendlyId(): OrderUserFriendlyId
    {
        return $this->orderUserFriendlyId;
    }
}
