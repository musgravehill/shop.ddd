<?php

declare(strict_types=1);

namespace app\components\UserCompany\Domain\ValueObject;

use app\components\Shared\Domain\ValueObject\ValueObjectAbstract;
use app\components\Shared\Domain\ValueObject\ValueObjectInterface;
use InvalidArgumentException;

final class Name extends ValueObjectAbstract implements ValueObjectInterface
{
    //self-validation
    public function __construct(
        private readonly string $name
    ) {        
        if (!preg_match('/^[\w\d\s\-]{1,255}$/u', $name)) {
            throw new InvalidArgumentException('Rule: name should be simple text 1..255.');
        }
    }

    public function getStructuralEqualityIdentifier(): string
    {
        return sha1(serialize([$this->name])); //you can add params (color white-red, size S-M-L) to array
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

    public function getName(): string
    {
        return $this->name;
    }
}
