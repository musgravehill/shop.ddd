<?php

declare(strict_types=1);

namespace app\components\Saga\Contract;

interface SagaCommandInterface
{
    public function execute(SagaDataInterface $sagaData): bool;
    public function compensate(SagaDataInterface $sagaData): void; //idempotence?
}
