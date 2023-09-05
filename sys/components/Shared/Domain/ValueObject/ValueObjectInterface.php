<?php

declare(strict_types=1);

namespace app\components\Shared\Domain\ValueObject;

//use Doctrine\ORM\Mapping\Embeddable;  #[Embeddable]
//use Psalm/Immutable;                  #[Immutable]

interface ValueObjectInterface
{
    public function getStructuralEqualityIdentifier(): string;
    public function isEqualsTo(ValueObjectInterface $vo): bool;    
}

// php-8.1 public readonly int $param;
// php-8.2 readonly className 
