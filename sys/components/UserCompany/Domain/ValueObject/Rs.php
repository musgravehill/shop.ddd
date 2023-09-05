<?php

declare(strict_types=1);

namespace app\components\UserCompany\Domain\ValueObject;

use app\components\Shared\Domain\ValueObject\ValueObjectAbstract;
use app\components\Shared\Domain\ValueObject\ValueObjectInterface;
use InvalidArgumentException;

final class Rs extends ValueObjectAbstract implements ValueObjectInterface
{
    //self-validation
    public function __construct(
        private readonly string $rs
    ) {        
        if (!preg_match('/^[\d]{20}$/u', $rs)) {
            throw new InvalidArgumentException('Rule: rs d 20.');
        }
    }

    public function getStructuralEqualityIdentifier(): string
    {
        return sha1(serialize([$this->rs])); //you can add params (color white-red, size S-M-L) to array
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

    public function getRs(): string
    {
        return $this->rs;
    }
}
