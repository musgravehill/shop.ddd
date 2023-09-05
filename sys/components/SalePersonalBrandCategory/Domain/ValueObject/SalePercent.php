<?php

declare(strict_types=1);

namespace app\components\SalePersonalBrandCategory\Domain\ValueObject;

use app\components\Shared\Domain\ValueObject\ValueObjectAbstract;
use app\components\Shared\Domain\ValueObject\ValueObjectInterface;
use InvalidArgumentException;

class SalePercent extends ValueObjectAbstract implements ValueObjectInterface
{
    protected readonly int $salePercent;

    //self-validation
    public function __construct(int $salePercent)
    {
        if ($salePercent < 0 || $salePercent > 100) {
            throw new InvalidArgumentException('Rule: salePercent 0..100 ');
        }

        $this->salePercent = $salePercent;
    }

    public static function prepare($input): ?int
    {
        $res = intval($input);
        return ($res <= 0) ? null : $res;
    }

    public function getStructuralEqualityIdentifier(): string
    {
        return sha1(serialize([$this->salePercent])); //you can add params (color white-red, size S-M-L) to array
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

    public function getSalePercent(): int
    {
        return $this->salePercent;
    }
}
