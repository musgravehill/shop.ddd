<?php

declare(strict_types=1);

namespace app\components\BrandCategory\Domain\ValueObject;

use app\components\Shared\Domain\ValueObject\ValueObjectAbstract;
use app\components\Shared\Domain\ValueObject\ValueObjectInterface;
use InvalidArgumentException;

final class SearchPriceFractionalMin extends ValueObjectAbstract implements ValueObjectInterface
{
    //self-validation
    public function __construct(
        private readonly int $searchPriceFractionalMin
    ) {
        if ($searchPriceFractionalMin < 0) {
            throw new InvalidArgumentException('Rule: searchPriceFractionalMin >=0.');
        }
        if (filter_var($searchPriceFractionalMin, FILTER_VALIDATE_INT) === false) {
            throw new InvalidArgumentException('Rule: searchPriceFractionalMin integer.');
        }
    }

    public function getStructuralEqualityIdentifier(): string
    {
        return sha1(serialize([$this->searchPriceFractionalMin])); //you can add params (color white-red, size S-M-L) to array
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

    public function getSearchPriceFractionalMin(): int
    {
        return $this->searchPriceFractionalMin;
    }
}
