<?php

declare(strict_types=1);

namespace app\components\BrandCategory\Domain\Contract;

use app\components\Brand\Domain\ValueObject\BrandId;
use app\components\Shared\Domain\ValueObject\CountOnPage;
use app\components\Shared\Domain\ValueObject\PageNumber;
use app\components\BrandCategory\Domain\Entity\BrandCategory;
use app\components\BrandCategory\Domain\ValueObject\BrandCategoryId;
use app\components\Search\Domain\SearchQuery;

interface BrandCategoryRepositoryInterface
{
    public function nextId(): BrandCategoryId;
    public function save(BrandCategory $brandCategory): ?BrandCategory;
    /** @return list<BrandCategory> */
    public function list(
        PageNumber $page,
        CountOnPage $cop,
        SearchQuery $q,
        BrandId $brandId
    ): array;
    public function getById(BrandCategoryId $id): ?BrandCategory;
    public function rand(CountOnPage $cop): array;
    public function getByBrandId(BrandId $brandId): array;
    public function idsNamesBrands(): array;
    public function incrementViewIdx(BrandCategoryId $id): void;
    public function popular(CountOnPage $cop): array;
}
