<?php

declare(strict_types=1);

namespace app\components\Saga\OrderPlace;

use app\components\Offer\Domain\Service\OfferService;
use app\components\Order\Domain\Service\PlaceOrderService;
use app\components\Order\Infrastructure\Service\OrderSendToCrmService;
use app\components\Saga\Contract\SagaAbstract;
use app\components\Saga\Contract\SagaInterface;
use app\components\Saga\OrderPlace\Command\OfferMakeCommand;
use app\components\Saga\OrderPlace\Command\OrderCreateCommand;
use app\components\Saga\OrderPlace\Command\OrderSendToCrmCommand;
use app\components\Saga\OrderPlace\Dto\OrderPlaceSagaData;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

class OrderPlaceSaga extends SagaAbstract implements SagaInterface
{
    public function __construct(
        OrderPlaceSagaData $sagaData
    ) {
        parent::__construct(
            sagaData: $sagaData,
            sagaId: Uuid::uuid7(),
            createdAt: new DateTimeImmutable()
        );

        $offerMakeCommand = new OfferMakeCommand(
            offerService: new OfferService()
        );
        $orderCreateCommand = new OrderCreateCommand(
            placeOrderService: new PlaceOrderService()
        );
        $orderSendToCrmCommand = new OrderSendToCrmCommand(
            orderSendToCrmService: new OrderSendToCrmService()
        );

        $this->addStep($offerMakeCommand)
            ->addStep($orderCreateCommand)
            ->addStep($orderSendToCrmCommand);
    }
}
