<?php

declare(strict_types=1);

namespace app\components\Shared\Domain\ValueObject;

use InvalidArgumentException;

class QuantityPositive extends ValueObjectAbstract implements ValueObjectInterface
{
    protected readonly int $quantity;

    //self-validation
    public function __construct(int $quantity)
    {
        if ($quantity < 0) {
            throw new InvalidArgumentException('Rule: quantity >0 .');
        }

        if (0 === $quantity) {
            throw new InvalidArgumentException('Rule: quantity >0 .');
        }

        $this->quantity = $quantity;
    }

    public function getStructuralEqualityIdentifier(): string
    {
        return sha1(serialize([$this->quantity])); //you can add params (color white-red, size S-M-L) to array
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

    //immutable
    public function getSumWith(self $vo): self
    {
        return new self(
            quantity: ($this->getQuantity() + $vo->getQuantity())
        );
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
