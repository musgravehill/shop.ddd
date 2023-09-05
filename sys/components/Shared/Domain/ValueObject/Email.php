<?php

declare(strict_types=1);

namespace app\components\Shared\Domain\ValueObject;

use app\components\Shared\Domain\ValueObject\ValueObjectAbstract;
use app\components\Shared\Domain\ValueObject\ValueObjectInterface;
use InvalidArgumentException;

final class Email extends ValueObjectAbstract implements ValueObjectInterface
{
    //self-validation
    public function __construct(
        private readonly string $email
    ) {
        if (mb_strlen($email, "UTF-8") > 32) {
            throw new InvalidArgumentException('Rule: email.L <= 32');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Email is not valid.');
        }       
    }

    public function getStructuralEqualityIdentifier(): string
    {
        return sha1(serialize([$this->email])); //you can add params (color white-red, size S-M-L) to array
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

    public function getEmail(): string
    {
        return $this->email;
    }
}
