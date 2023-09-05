<?php

declare(strict_types=1);

namespace app\components\Product\Domain\Contract;

use app\components\Brand\Domain\ValueObject\BrandId;
use app\components\BrandCategory\Domain\ValueObject\BrandCategoryId;
use app\components\Product\Domain\ValueObject\ProductId;
use app\components\Product\Domain\Entity\Product;
use app\components\Product\Domain\ValueObject\ProductExternalId;
use app\components\Search\Domain\SearchQuery;
use app\components\Shared\Domain\ValueObject\CountOnPage;
use app\components\Shared\Domain\ValueObject\PageNumber;
use app\components\Supplier\Domain\ValueObject\SupplierId;
use DateTimeImmutable;

interface ProductRepositoryInterface
{
    public function nextId(): ProductId;
    public function save(Product $product): ?Product;
    public function getById(ProductId $id): ?Product;
    public function getByExternalData(ProductExternalId $productExternalId, SupplierId $supplierId): ?Product;
    public function rand(CountOnPage $cop, BrandId $brandId): array;
    public function incrementViewIdx(ProductId $id): void;
    public function popular(SupplierId $supplierId, CountOnPage $cop, BrandId $brandId, DateTimeImmutable $obsoleteСonstraintAt): array;
    /** @return list<Product> */
    public function list(
        PageNumber $page,
        CountOnPage $cop,
        SearchQuery $q,
        BrandId $brandId,
        BrandCategoryId $brandCategoryId,
        bool $BBcEmptyOnly,
        SupplierId $supplierId,
    ): array;
    public function totalCount():int;
}
