<?php

declare(strict_types=1);

namespace app\components\Shared\Domain\ValueObject;

use InvalidArgumentException;

abstract class ValueObjectAbstract implements ValueObjectInterface
{
    //structural equality, compare
    public function isEqualsTo(ValueObjectInterface $vo): bool
    {
        if (get_class($this) !== get_class($vo)) {
            throw new InvalidArgumentException('Objects of different classes');
        }

        return true;
        //It can NOT compare 2 object with plain Reflections::properties because some VO has objects\Enum as property
        // $foo->name === $bar->name
        // $foo->colorEnum === $bar->colorEnum no way!         
    }      
}
