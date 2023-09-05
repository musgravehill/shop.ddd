<?php

declare(strict_types=1);

namespace app\components\User\Domain\ValueObject;

use app\components\Shared\Domain\ValueObject\ValueObjectAbstract;
use app\components\Shared\Domain\ValueObject\ValueObjectInterface;
use InvalidArgumentException;

final class Phone extends ValueObjectAbstract implements ValueObjectInterface
{
    //self-validation
    public function __construct(
        private readonly string $phone
    ) {
        if (!preg_match('/^\d{10}$/u', $phone)) {
            throw new InvalidArgumentException('Rule: phone should be d{10} .');
        }
    }

    public static function dummy(): self
    {
        return new self('0000000000');
    }

    public function getStructuralEqualityIdentifier(): string
    {
        return sha1(serialize([$this->phone])); //you can add params (color white-red, size S-M-L) to array
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

    public function getPhone(): string
    {
        return $this->phone;
    }
}
