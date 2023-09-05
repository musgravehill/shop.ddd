<?php

declare(strict_types=1);

namespace app\components\Saga\OrderPlace\Command;

use app\components\Offer\Domain\Service\OfferService;
use app\components\Saga\Contract\SagaCommandAbstract;
use app\components\Saga\Contract\SagaCommandInterface;
use app\components\Saga\Contract\SagaDataInterface;
use app\components\Saga\OrderPlace\Dto\ItemRaw;
use app\components\Saga\OrderPlace\Dto\OrderPlaceSagaData;

class OfferMakeCommand extends SagaCommandAbstract implements SagaCommandInterface
{
    public function __construct(
        private OfferService $offerService 
    ) {
    }

    public function execute(SagaDataInterface $sagaData): bool
    {
        /** @var OrderPlaceSagaData $sagaData */
        try {
            /** @var \app\components\Offer\Domain\Service\OfferFactory $offerFactory */
            $offerFactory = $this->offerService->getOfferFactory($sagaData->getUserId());

            foreach ($sagaData->getItemsRaw() as $itemRaw) {
                /** @var ItemRaw $itemRaw */
                $offerFactory->addItem(
                    productId: \app\components\Product\Domain\ValueObject\ProductId::fromString($itemRaw->getProductId()),
                    productQuantity: new \app\components\Shared\Domain\ValueObject\QuantityPositive($itemRaw->getQuantity())
                );
            }
            $offer = $offerFactory->getOffer();
            
            //save data for next step
            $sagaData->setOffer($offer);
            $sagaData->setInfo($sagaData->getInfo() . ' Offer_exe ');
        } catch (\Throwable $th) {
            return false;
        }      

        return true;
    }

    //idempotence?
    public function compensate(SagaDataInterface $sagaData): void
    {
        /** @var OrderPlaceSagaData $sagaData */
        $sagaData->setInfo($sagaData->getInfo() . ' Offer_comp ');
    }
}
