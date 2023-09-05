<?php

declare(strict_types=1);

namespace app\components\BrandCategory\Domain\ValueObject;

use app\components\Shared\Domain\ValueObject\ValueObjectAbstract;
use app\components\Shared\Domain\ValueObject\ValueObjectInterface;
use InvalidArgumentException;

final class SearchPriceFractionalMax extends ValueObjectAbstract implements ValueObjectInterface
{
    //self-validation
    public function __construct(
        private readonly int $searchPriceFractionalMax
    ) {
        if ($searchPriceFractionalMax < 0) {
            throw new InvalidArgumentException('Rule: searchPriceFractionalMax >=0.');
        }
        if (filter_var($searchPriceFractionalMax, FILTER_VALIDATE_INT) === false) {
            throw new InvalidArgumentException('Rule: searchPriceFractionalMax integer.');
        }
    }

    public function getStructuralEqualityIdentifier(): string
    {
        return sha1(serialize([$this->searchPriceFractionalMax])); //you can add params (color white-red, size S-M-L) to array
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

    public function getSearchPriceFractionalMax(): int
    {
        return $this->searchPriceFractionalMax;
    }
}
