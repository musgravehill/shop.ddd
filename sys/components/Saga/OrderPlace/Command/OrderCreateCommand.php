<?php

declare(strict_types=1);

namespace app\components\Saga\OrderPlace\Command;

use app\components\Order\Domain\Contract\AppliedSaleTypeIdCollection;
use app\components\Order\Domain\Contract\OrderItemCollection;
use app\components\Order\Domain\Entity\OrderItem;
use app\components\Order\Domain\Service\PlaceOrderService;
use app\components\User\Domain\ValueObject\UserId;
use app\components\Order\Domain\ValueObject\OrderItemId;
use app\components\Product\Domain\ValueObject\ProductId;
use app\components\Shared\Domain\ValueObject\QuantityPositive;
use app\components\Saga\Contract\SagaCommandAbstract;
use app\components\Saga\Contract\SagaCommandInterface;
use app\components\Saga\Contract\SagaDataInterface;
use app\components\Saga\OrderPlace\Dto\OrderPlaceSagaData;
use app\components\Shared\Domain\ValueObject\Money;

class OrderCreateCommand extends SagaCommandAbstract implements SagaCommandInterface
{
    public function __construct(
        private PlaceOrderService $placeOrderService
    ) {
    }

    public function execute(SagaDataInterface $sagaData): bool
    {
        /** @var OrderPlaceSagaData $sagaData */
        try {
            $items = new OrderItemCollection();
            $offerItems = $sagaData->getOffer()->getItems();
            foreach ($offerItems as $offerItem) {
                /** @var \app\components\Offer\Domain\ValueObject\OfferItem $offerItem */
                $id = OrderItemId::fromString(null);
                $productId = ProductId::fromString($offerItem->getProductId()->getId());
                $quantity = new QuantityPositive($offerItem->getProductQuantity()->getQuantity());
                $priceInitial = new Money(
                    fractionalCount: $offerItem->getPriceInitial()->getFractionalCount(),
                    currency: $offerItem->getPriceInitial()->getСurrency()
                );
                $priceFinal = new Money(
                    fractionalCount: $offerItem->getPriceFinal()->getFractionalCount(),
                    currency: $offerItem->getPriceFinal()->getСurrency()
                );
                $appliedSaleTypeIds = new AppliedSaleTypeIdCollection(
                    $offerItem->getAppliedSaleTypeIds()->toArray()
                );
                $orderItem = OrderItem::initNew(
                    id: $id,
                    productId: $productId,
                    quantity: $quantity,
                    priceInitial: $priceInitial,
                    priceFinal: $priceFinal,
                    appliedSaleTypeIds: $appliedSaleTypeIds
                );
                $items->append($orderItem);
            }

            $orderComment = $sagaData->getOrderComment();

            //do work
            $order = $this->placeOrderService->placeOrder(
                userId: $sagaData->getUserId(),
                deliveryParams: $sagaData->getDeliveryParams(),
                items: $items,
                orderComment: $orderComment
            );

            //save data for next step
            $sagaData->setOrder($order);
            $sagaData->setInfo($sagaData->getInfo() . ' Order_exe ');
        } catch (\Throwable $th) {
            print_r($th); die;
            return false;
        }

        return true;
    }

    //idempotence?
    public function compensate(SagaDataInterface $sagaData): void
    {
        /** @var OrderPlaceSagaData $sagaData */
        $sagaData->setInfo($sagaData->getInfo() . ' Order_comp ');
    }
}
