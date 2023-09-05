<?php

declare(strict_types=1);

namespace app\components\Order\Domain\Service;

use app\components\Order\Domain\Aggregate\Order;
use app\components\Order\Domain\Contract\OrderItemCollection;
use app\components\Order\Domain\Contract\OrderCommandRepositoryInterface;
use app\components\Order\Domain\Contract\OrderUserFriendlyIdGeneratorInterface;
use app\components\User\Domain\ValueObject\UserId;
use app\components\Order\Domain\ValueObject\DeliveryParams;
use app\components\Order\Domain\ValueObject\OrderComment;
use app\components\Order\Infrastructure\OrderCommandRepository;
use app\components\Order\Infrastructure\Service\OrderUserFriendlyIdGenerator;
use DateTimeImmutable;

class PlaceOrderService
{
    private OrderUserFriendlyIdGeneratorInterface $orderUserFriendlyIdGenerator;
    private OrderCommandRepositoryInterface $orderRepository;

    public function __construct()
    {
        $this->orderUserFriendlyIdGenerator = new OrderUserFriendlyIdGenerator;
        $this->orderRepository = new OrderCommandRepository;
    }

    public function placeOrder(
        UserId $userId,
        DeliveryParams $deliveryParams,
        OrderItemCollection $items,
        OrderComment $orderComment
    ): Order {
        $id = $this->orderRepository->nextId();
        $orderUserFriendlyId = $this->orderUserFriendlyIdGenerator->nextId();        
        $createdAt = new DateTimeImmutable();
        
        $order = Order::initNew(
            id: $id,
            orderUserFriendlyId: $orderUserFriendlyId,
            userId: $userId,
            deliveryParams: $deliveryParams,
            items: $items,
            orderComment: $orderComment,
            createdAt: $createdAt
        );

        $this->orderRepository->add($order);

        return $order;
    }
}
