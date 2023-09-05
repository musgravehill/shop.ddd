<?php

declare(strict_types=1);

namespace app\components\Shared\Domain\ValueObject;

use InvalidArgumentException;

class PageNumber extends ValueObjectAbstract implements ValueObjectInterface
{
    protected readonly int $page;

    //self-validation
    public function __construct(int $page)
    {
        if ($page < 0) {
            throw new InvalidArgumentException('Rule: page >0 .');
        }

        if (0 === $page) {
            throw new InvalidArgumentException('Rule: page >0 .');
        }

        $this->page = $page;
    }

    public static function prepare($input): int
    {
        $res = intval($input);
        return ($res <= 0) ? 1 : $res;
    }

    public function getStructuralEqualityIdentifier(): string
    {
        return sha1(serialize([$this->page])); //you can add params (color white-red, size S-M-L) to array
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

    public function getPageNumber(): int
    {
        return $this->page;
    }
}
