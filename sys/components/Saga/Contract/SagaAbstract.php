<?php

declare(strict_types=1);

namespace app\components\Saga\Contract;

use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

abstract class SagaAbstract implements SagaInterface
{

    /** @param SagaCommandInterface[] $steps */
    protected array $steps = [];
    protected int $currStepIdx = 0;
    protected bool $isSuccess = true;

    public function __construct(
        protected readonly SagaDataInterface $sagaData,
        protected readonly UuidInterface $sagaId,
        protected readonly DateTimeImmutable $createdAt
    ) {
    }

    protected function addStep(SagaCommandInterface $command): self
    {
        $this->steps[] = $command;
        return $this;
    }

    public function process(): void
    {
        foreach ($this->steps as $command) {
            if ($command->execute(sagaData: $this->getSagaData())) {
                $this->incCurrStepIdx();
            } else {
                $this->compensate();
                $this->setIsSuccess(false);
                break;
            }
        }
    }

    protected function compensate()
    {
        $steps = $this->steps;
        $idx = $this->getCurrStepIdx();
        while ($idx >= 0) {
            $command = $steps[$this->getCurrStepIdx()];
            $command->compensate(sagaData: $this->getSagaData());
            $idx--;
            $this->decCurrStepIdx();
        }
    }

    protected function incCurrStepIdx(): int
    {
        $this->currStepIdx += 1;
        return $this->currStepIdx;
    }

    protected function decCurrStepIdx(): int
    {
        if (0 === $this->currStepIdx) {
            return $this->currStepIdx;
        }
        $this->currStepIdx -= 1;
        return $this->currStepIdx;
    }

    protected function getCurrStepIdx(): int
    {
        return $this->currStepIdx;
    }

    protected function setIsSuccess(bool $isSuccess): void
    {
        $this->isSuccess = $isSuccess;
    }

    public function getSagaData(): SagaDataInterface
    {
        return $this->sagaData;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getSagaId(): UuidInterface
    {
        return $this->sagaId;
    }

    public function isSuccess(): bool
    {
        return $this->isSuccess;
    }
}
