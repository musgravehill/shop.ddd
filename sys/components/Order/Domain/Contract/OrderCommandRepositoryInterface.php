<?php

declare(strict_types=1);

namespace app\components\Order\Domain\Contract;

use app\components\Order\Domain\Aggregate\Order;
use app\components\Order\Domain\ValueObject\OrderId;
use app\components\Shared\Domain\Contract\RepositoryInterface;

interface OrderCommandRepositoryInterface extends RepositoryInterface
{
    public function add(Order $order): void;
   //TODO public function getById(OrderId $orderId): ?Order;     
}
