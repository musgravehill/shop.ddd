<?php

declare(strict_types=1);

namespace app\components\IAA\Authentication\Model\ValueObject;

use app\components\Shared\Domain\ValueObject\ValueObjectAbstract;
use app\components\Shared\Domain\ValueObject\ValueObjectInterface;
use InvalidArgumentException;

final class ViolationCount extends ValueObjectAbstract implements ValueObjectInterface
{
    //self-validation
    private function __construct(
        private readonly int $violationCount
    ) {
        if ($violationCount < 0 || $violationCount > 999) {
            throw new InvalidArgumentException('Rule: violationCount 0...999 .');
        }
    }

    public static function forUserWithoutViolations(): self
    {
        return new self(0);
    }

    public static function hydrateExisting(int $violationCount): self
    {
        return new self($violationCount);
    }

    public function incrementExisting(): self
    {
        return new self($this->getViolationCount() + 1);
    }

    public function getStructuralEqualityIdentifier(): string
    {
        return sha1(serialize([$this->violationCount])); //you can add params (color white-red, size S-M-L) to array
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

    public function getViolationCount(): int
    {
        return $this->violationCount;
    }
}
