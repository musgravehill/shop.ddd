<?php

declare(strict_types=1);

namespace app\components\Shared\Domain\ValueObject\Identifier;

use InvalidArgumentException;

abstract class IdAbstract implements IdInterface
{  
    //structural equality, compare
    public function isEqualsTo(IdInterface $id): bool
    {
        if (get_class($this) !== get_class($id)) {
            throw new InvalidArgumentException('IDs of different classes.');
        }

        /** @var self $vo */
        if ($this->getId() !== $id->getId()) {
            return false;
        }
        return true;
    }
}
