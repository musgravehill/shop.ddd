<?php

declare(strict_types=1);

namespace app\components\Saga\OrderPlace\Command;

use app\components\Order\Infrastructure\Service\OrderSendToCrmService;
use app\components\Saga\Contract\SagaCommandAbstract;
use app\components\Saga\Contract\SagaCommandInterface;
use app\components\Saga\Contract\SagaDataInterface;
use app\components\Saga\OrderPlace\Dto\OrderPlaceSagaData;

class OrderSendToCrmCommand extends SagaCommandAbstract implements SagaCommandInterface
{
    public function __construct(
        private OrderSendToCrmService $orderSendToCrmService
    ) {
    }

    public function execute(SagaDataInterface $sagaData): bool
    {
        /** @var OrderPlaceSagaData $sagaData */
        try {
            //get data from prev step
            $order = $sagaData->getOrder();
            $orderId = $order->getId();

            //do work
            $this->orderSendToCrmService->sendToCrm($orderId);

            $sagaData->setInfo($sagaData->getInfo() . ' CRM_exe ');
        } catch (\Throwable $th) {
            return false;
        }
        return true;
    }

    //idempotence?
    public function compensate(SagaDataInterface $sagaData): void
    {
        /** @var OrderPlaceSagaData $sagaData */
        $sagaData->setInfo($sagaData->getInfo() . ' CRM_comp ');
    }
}
