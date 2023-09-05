<?php

declare(strict_types=1);

namespace app\components\SalePersonalBrandCategory\Domain\Contract;

use app\components\Brand\Domain\ValueObject\BrandId;
use app\components\BrandCategory\Domain\ValueObject\BrandCategoryId;
use app\components\SalePersonalBrandCategory\Domain\Entity\SalePersonalBrandCategory;
use app\components\SalePersonalBrandCategory\Domain\ValueObject\SalePersonalBrandCategoryId;
use app\components\Shared\Domain\ValueObject\CountOnPage;
use app\components\Shared\Domain\ValueObject\PageNumber;
use app\components\User\Domain\ValueObject\UserId;

interface SalePersonalBrandCategoryRepositoryInterface
{
    public function nextId(): SalePersonalBrandCategoryId;
    public function save(SalePersonalBrandCategory $salePersonalBrandCategory): ?SalePersonalBrandCategory;
    public function getPercent(UserId $userId, BrandId $brandId, BrandCategoryId $brandCategoryId): int;
    public function list(PageNumber $page, CountOnPage $cop, UserId $userId, BrandId $brandId): array;
    public function getById(SalePersonalBrandCategoryId $id): ?SalePersonalBrandCategory;
}
