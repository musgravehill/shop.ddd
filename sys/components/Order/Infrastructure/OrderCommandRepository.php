<?php

declare(strict_types=1);

namespace app\components\Order\Infrastructure;

use Yii;
use Ramsey\Uuid\Uuid;

use app\components\Order\Domain\Aggregate\Order;
use app\components\Order\Domain\Contract\OrderCommandRepositoryInterface;
use app\components\Order\Domain\Entity\OrderItem;
use app\components\Order\Domain\ValueObject\OrderId;
use app\components\Order\Domain\ValueObject\OrderItemId;
use app\components\Sale\Domain\ValueObject\SaleTypeId;
use app\components\Shared\Domain\ValueObject\Identifier\IdInterface;
use Exception;

class OrderCommandRepository implements OrderCommandRepositoryInterface
{
    public function nextId(): IdInterface
    {
        $uuid = Uuid::uuid7()->toString();
        return OrderId::fromString($uuid);
    }

    public function nextOrderItemId(): IdInterface
    {
        $uuid = Uuid::uuid7()->toString();
        return OrderItemId::fromString($uuid);
    }

    public function add(Order $order): void
    {
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            //save order
            Yii::$app->db->createCommand()->insert('order_list', [
                'id' => $order->getId()->getId() ?? $this->nextId()->getId(),
                'userFriendlyOrderId' => $order->getOrderUserFriendlyId()->getId(),
                'userId' => $order->getUserId()->getId(),
                'deliveryTypeId' => $order->getDeliveryParams()->getDeliveryTypeId()->value,
                'cityName' => $order->getDeliveryParams()->getCityName(),
                'comment' => $order->getOrderComment()->getComment(),
                'createdAt' => $order->getCreatedAt()->getTimestamp(),
                'priceTotalFractionalCount' => $order->getPriceTotal()->getFractionalCount(),
            ])->execute();

            $orderItemCollection = $order->getItems();
            foreach ($orderItemCollection as $orderItem) {
                $this->addOrderItem(
                    order: $order,
                    orderItem: $orderItem
                );
            }

            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();              
            throw new Exception('Save: error!');           
        }
    }

    private function addOrderItem(Order $order, OrderItem $orderItem): void
    {
        $appliedSaleTypeIds = array_map(
            function (SaleTypeId $item) {
                return $item->value;
            },
            $orderItem->getAppliedSaleTypeIds()->toArray()
        );

        Yii::$app->db->createCommand()->insert('order_items', [
            'id' => $orderItem->getId()->getId() ?? $this->nextOrderItemId()->getId(),
            'orderId' => $order->getId()->getId(),
            'productId' => $orderItem->getProductId()->getId(),
            'quantity' => $orderItem->getQuantity()->getQuantity(),
            'priceInitialFractional' => $orderItem->getPriceInitial()->getFractionalCount(),
            'priceFinalFractional' => $orderItem->getPriceFinal()->getFractionalCount(),
            'appliedSaleTypeIds' => implode(',', $appliedSaleTypeIds)
        ])->execute();
    }

    /* public function getById(OrderId $orderId): ?Order
    {
        $order = Order::hydrateOrder(
            id: $id,
            userId: $userId,
            deliveryParams: $deliveryParams,
            items: $items,
            comment: $comment,
            createdAt: $createdAt
        );
        return $order;
    }*/
}
