<?php

declare(strict_types=1);

namespace app\components\Saga\Contract;

use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

interface SagaInterface
{    
    public function getSagaData(): SagaDataInterface;
    public function process(): void;
    public function getCreatedAt(): DateTimeImmutable;
    public function getSagaId(): UuidInterface;
}
