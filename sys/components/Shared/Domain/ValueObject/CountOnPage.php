<?php

declare(strict_types=1);

namespace app\components\Shared\Domain\ValueObject;

use InvalidArgumentException;

class CountOnPage extends ValueObjectAbstract implements ValueObjectInterface
{
    protected readonly int $cop;
    const CopMax = 9999;

    //self-validation
    public function __construct(int $cop)
    {
        if ($cop < 0) {
            throw new InvalidArgumentException('Rule: cop >0 .');
        }

        if (0 === $cop) {
            throw new InvalidArgumentException('Rule: cop >0 .');
        }

        $this->cop = $cop;
    }

    public static function prepare($input): int
    {
        $res = intval($input);
        $res = ($res <= 0) ? 1 : $res;
        $res = ($res > self::CopMax) ? self::CopMax : $res;
        return  $res;
    }

    public function getStructuralEqualityIdentifier(): string
    {
        return sha1(serialize([$this->cop])); //you can add params (color white-red, size S-M-L) to array
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

    public function getCop(): int
    {
        return $this->cop;
    }
}
