<?php

declare(strict_types=1);

namespace app\components\Search\Domain\Contract;

use app\components\Search\Domain\SortId;
use app\components\Search\Domain\SearchProductDto;
use app\components\Brand\Domain\ValueObject\BrandId;
use app\components\BrandCategory\Domain\ValueObject\BrandCategoryId;
use app\components\Search\Domain\SearchQuery;
use app\components\Shared\Domain\ValueObject\CountOnPage;
use app\components\Shared\Domain\ValueObject\Money;
use app\components\Shared\Domain\ValueObject\PageNumber;
use app\components\Shared\Domain\ValueObject\QuantityZeroPositive;
use app\components\Supplier\Domain\ValueObject\SupplierId;
use DateTimeImmutable;

interface SearchProductInterface
{
    /** @return SearchProductDto[] */
    public function getProducts(
        PageNumber $page,
        CountOnPage $countOnPage,
        SearchQuery $searchQuery,
        Money $priceMin,
        Money $priceMax,
        SupplierId $supplierId,
        BrandId $brandId,
        BrandCategoryId $brandCategoryId,
        QuantityZeroPositive $quantityAvailableMin,
        SortId $sortId,
        DateTimeImmutable $obsoleteСonstraintAt 
    ): array;
}
