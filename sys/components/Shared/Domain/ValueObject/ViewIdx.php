<?php

declare(strict_types=1);

namespace app\components\Shared\Domain\ValueObject;

use InvalidArgumentException;

class ViewIdx extends ValueObjectAbstract implements ValueObjectInterface
{
    protected readonly int $idx;


    //self-validation
    public function __construct(int $idx)
    {
        if ($idx < 0) {
            throw new InvalidArgumentException('Rule: idx >=0 .');
        }        

        $this->idx = $idx;
    }

    public function getStructuralEqualityIdentifier(): string
    {
        return sha1(serialize([$this->idx])); //you can add params (color white-red, size S-M-L) to array
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

    public function getIdx(): int
    {
        return $this->idx;
    }
}
