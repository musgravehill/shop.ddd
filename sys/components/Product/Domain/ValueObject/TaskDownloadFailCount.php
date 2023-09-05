<?php

declare(strict_types=1);

namespace app\components\Product\Domain\ValueObject;

use app\components\Shared\Domain\ValueObject\ValueObjectAbstract;
use app\components\Shared\Domain\ValueObject\ValueObjectInterface;
use InvalidArgumentException;

class TaskDownloadFailCount extends ValueObjectAbstract implements ValueObjectInterface
{
    protected readonly int $failCount;

    //self-validation
    public function __construct(int $failCount)
    {
        if ($failCount < 0) {
            throw new InvalidArgumentException('Rule: failCount >=0 .');
        }

        $this->failCount = $failCount;
    }

    public function getStructuralEqualityIdentifier(): string
    {
        return sha1(serialize([$this->failCount])); //you can add params (color white-red, size S-M-L) to array
    }

    //structural equality, compare
    public function isEqualsTo(ValueObjectInterface $vo): bool
    {
        parent::isEqualsTo($vo);
        /** @var self $vo */
        if ($this->getStructuralEqualityIdentifier() !== $vo->getStructuralEqualityIdentifier()) {
            return false;
        }
        return true;
    }

    public function getFailCount(): int
    {
        return $this->failCount;
    }

    public function incrementFails(): self
    {
        return new self(1 + $this->getFailCount());
    }
}
