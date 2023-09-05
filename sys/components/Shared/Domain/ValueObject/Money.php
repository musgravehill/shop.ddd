<?php

declare(strict_types=1);

namespace app\components\Shared\Domain\ValueObject;

use InvalidArgumentException;

final class Money extends ValueObjectAbstract implements ValueObjectInterface
{
    private readonly int $fractionalCount; //cent, kopek, céntimo, dinar      
    private readonly MoneyСurrency $currency;

    //self-validation
    public function __construct(int $fractionalCount, MoneyСurrency $currency)
    {
        if ($fractionalCount < 0) {
            throw new InvalidArgumentException('Rule: fractionalCount >=0.');
        }
        if (filter_var($fractionalCount, FILTER_VALIDATE_INT) === false) {
            throw new InvalidArgumentException('Rule: fractionalCount integer.');
        }
        $this->fractionalCount = $fractionalCount;
        $this->currency = $currency;
    }

    public function getStructuralEqualityIdentifier(): string
    {
        return sha1(serialize([$this->fractionalCount, $this->currency])); //you can add params (color white-red, size S-M-L) to array
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
        if (!$this->isCurrencyEqualsTo($vo)) {
            throw new InvalidArgumentException('You must sum the same currencies.');
        }

        return new self(
            fractionalCount: ($this->getFractionalCount() + $vo->getFractionalCount()),
            currency: $this->getСurrency()
        );
    }

    //immutable
    public function multiplyBy(int $m): self
    {
        if (!$m) {
            throw new InvalidArgumentException('Rule: multiply by $m >=0. ');
        }

        return new self(
            fractionalCount: ($m * $this->getFractionalCount()),
            currency: $this->getСurrency()
        );
    }

    private function isCurrencyEqualsTo(self $vo): bool
    {
        return $this->getСurrency() === $vo->getСurrency();
    }

    public function getFractionalCount(): int
    {
        return $this->fractionalCount;
    }

    public function getСurrency(): MoneyСurrency
    {
        return $this->currency;
    }
}
