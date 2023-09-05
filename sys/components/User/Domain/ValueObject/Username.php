<?php

declare(strict_types=1);

namespace app\components\User\Domain\ValueObject;

use app\components\Shared\Domain\ValueObject\ValueObjectAbstract;
use app\components\Shared\Domain\ValueObject\ValueObjectInterface;
use InvalidArgumentException;

final class Username extends ValueObjectAbstract implements ValueObjectInterface
{
    //self-validation
    public function __construct(
        private readonly string $username
    ) {
        if (!preg_match('/^[\w\s]{1,128}$/u', $username)) {
            throw new InvalidArgumentException('Rule: username should be simple text 1..128.');
        }
    }

    public static function dummy(): self
    {
        return new self('ФИО');
    }

    public function getStructuralEqualityIdentifier(): string
    {
        return sha1(serialize([$this->username])); //you can add params (color white-red, size S-M-L) to array
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

    public function getUsername(): string
    {
        return $this->username;
    }
}
