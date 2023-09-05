<?php

declare(strict_types=1);

namespace app\components\Product\Domain\ValueObject;

use app\components\Shared\Domain\ValueObject\ValueObjectAbstract;
use app\components\Shared\Domain\ValueObject\ValueObjectInterface;
use InvalidArgumentException;

final class ProductExternalId extends ValueObjectAbstract implements ValueObjectInterface 
{
    //self-validation
    public function __construct(
        private readonly string $id
    ) {
        if (mb_strlen($id, "utf-8") > 64) {
            throw new InvalidArgumentException('Rule: id should be simple text 1..64.');
        }
        if (!preg_match('/^[\w\d\-]{1,64}$/u', $id)) {
            throw new InvalidArgumentException('Rule: id should be simple text 1..64.');
        }
    }

    public function getStructuralEqualityIdentifier(): string
    {
        return sha1(serialize([$this->id])); //you can add params (color white-red, size S-M-L) to array
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

    public function getId(): string
    {
        return $this->id;
    }
}
