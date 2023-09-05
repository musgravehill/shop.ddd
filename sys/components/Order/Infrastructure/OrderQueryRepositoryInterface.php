<?php

declare(strict_types=1);

namespace app\components\Order\Infrastructure;

use app\components\Order\App\DTO\OrderResponce;
use app\components\User\Domain\ValueObject\UserId;
use app\components\Order\Domain\ValueObject\OrderId;
use app\components\Shared\Domain\ValueObject\PageNumber;

interface OrderQueryRepositoryInterface
{
    public function getOrder(OrderId $orderId): ?OrderResponce;

    /** @return \app\components\Order\App\DTO\OrderItemResponce[] */
    public function getOrderItems(OrderId $orderId): array;

    /** @return \app\components\Order\App\DTO\OrderResponce[] */
    public function getOrders(UserId $userId, PageNumber $page): array;
}
