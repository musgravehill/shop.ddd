<?php

declare(strict_types=1);

namespace app\components\Order\Infrastructure;

use app\components\Delivery\Domain\ValueObject\DeliveryTypeId;
use app\components\Order\Domain\ValueObject\OrderId;
use app\components\Order\App\DTO\OrderResponce;
use app\components\Order\App\DTO\OrderItemResponce;
use app\components\Shared\Domain\ValueObject\PageNumber;
use app\components\User\Domain\ValueObject\UserId;
use DateTimeImmutable;
use InvalidArgumentException;
use Yii;

class OrderQueryRepository implements OrderQueryRepositoryInterface
{
    public function getOrder(OrderId $orderId): ?OrderResponce
    {
        $orderRaw = Yii::$app->db->createCommand("
                    SELECT
                        ol.*               
                    FROM  {{order_list}} ol                   
                    WHERE
                        ol.id='" . $orderId->getId() . "'
                    LIMIT 1                    
                   ")
            ->queryOne();
        if (!$orderRaw) {
            return null;
        }

        $userId = $orderRaw['userId'];
        $customer = Yii::$app->db->createCommand("
                    SELECT
                        customer.username,
                        customer.phone,
                        customer.email          
                    FROM  {{user}} customer                  
                    WHERE
                        customer.id='" . $userId . "'
                    LIMIT 1                    
                   ")
            ->queryOne();
        if (!$customer) {
            return null;
        }

        $responce = new OrderResponce(
            orderId: (string) $orderId->getId(),
            userFriendlyOrderId: $orderRaw['userFriendlyOrderId'],
            deliveryTypeId: (string) DeliveryTypeId::from($orderRaw['deliveryTypeId'])->value,
            deliveryCityName: (string) $orderRaw['cityName'],
            orderComment: (string) $orderRaw['comment'],
            createdAt: (new DateTimeImmutable())->setTimestamp($orderRaw['createdAt']),
            priceTotalFractionalCount: $orderRaw['priceTotalFractionalCount'],
            userId: (string) $orderRaw['userId'],
            customerName: (string) $customer['username'],
            customerEmail: (string) $customer['email'],
            customerPhone: (string) $customer['phone']
        );

        return $responce;
    }

    public function getOrderItems(OrderId $orderId): array
    {
        $responce = [];
        $itemsRaw = Yii::$app->db->createCommand("
                    SELECT
                        oi.*,
                        product.name as productName,
                        product.ufu as productUfu    
                    FROM  {{order_items}} oi  
                    LEFT JOIN {{product}} product ON product.id = oi.productId                
                    WHERE
                        oi.orderId='" . $orderId->getId() . "'
                    LIMIT 1000                 
                   ")
            ->queryAll();
        if (!$itemsRaw) {
            return $responce;
        }

        foreach ($itemsRaw as $itemRaw) {
            $responce[] = new OrderItemResponce(
                orderItemId: (string) $itemRaw['id'],
                productId: (string) $itemRaw['productId'],
                quantity: $itemRaw['quantity'],
                priceInitialFractionalCount: $itemRaw['priceInitialFractional'],
                priceFinalFractionalCount: $itemRaw['priceFinalFractional'],
                appliedSaleTypeIds: explode(',', $itemRaw['appliedSaleTypeIds']),
                productName: (string) $itemRaw['productName'],
                productUfu: (string) $itemRaw['productUfu'],
            );
        }

        return $responce;
    }

    public function getOrders(UserId $userId, PageNumber $page): array
    {
        $responce = [];

        $condUserId = ' ';
        if (!is_null($userId->getId())) {
            $condUserId = ' AND order_list.userId="' . $userId->getId() . '" ';
        }

        $cop = 10;
        $offset = (int) ($page->getPageNumber() - 1) * $cop;
        $limit = " LIMIT $offset, $cop ";

        $ordersRaw = Yii::$app->db->createCommand("
                    SELECT
                        order_list.*,
                        user.username as customerUsername,
                        user.email as customerEmail,          
                        user.phone as customerPhone                          
                    FROM  {{order_list}} order_list     
                    LEFT JOIN {{user}} user ON user.id = order_list.userId                               
                    WHERE
                        1=1 
                        $condUserId
                    ORDER BY order_list.id DESC      
                    $limit                    
                   ")
            ->queryAll();

        if (!$ordersRaw) {
            return $responce;
        }

        foreach ($ordersRaw as $orderRaw) {
            $responce[] = new OrderResponce(
                orderId: (string) $orderRaw['id'],
                userFriendlyOrderId: $orderRaw['userFriendlyOrderId'],
                deliveryTypeId: (string) DeliveryTypeId::from($orderRaw['deliveryTypeId'])->value,
                deliveryCityName: (string) $orderRaw['cityName'],
                orderComment: (string) $orderRaw['comment'],
                createdAt: (new DateTimeImmutable())->setTimestamp($orderRaw['createdAt']),
                priceTotalFractionalCount: $orderRaw['priceTotalFractionalCount'],

                userId: (string) $orderRaw['userId'],
                customerName: (string) $orderRaw['customerUsername'],
                customerEmail: (string) $orderRaw['customerEmail'],
                customerPhone: (string) $orderRaw['customerPhone']
            );
        }

        return $responce;
    }
}
