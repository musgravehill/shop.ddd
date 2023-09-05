<?php

declare(strict_types=1);

namespace app\components\SalePersonalBrandCategory\Domain\Entity;

use app\components\Brand\Domain\ValueObject\BrandId;
use app\components\BrandCategory\Domain\ValueObject\BrandCategoryId;
use app\components\SalePersonalBrandCategory\Domain\ValueObject\SalePercent;
use app\components\SalePersonalBrandCategory\Domain\ValueObject\SalePersonalBrandCategoryId;
use app\components\User\Domain\ValueObject\UserId;
use InvalidArgumentException;

class SalePersonalBrandCategory
{
    private function __construct(
        private SalePersonalBrandCategoryId $salePersonalBrandCategoryId,
        private UserId $userId,
        private BrandId $brandId,
        private BrandCategoryId $brandCategoryId,
        private SalePercent $salePercent,
    ) {
    }

    public static function new(
        UserId $userId,
        BrandId $brandId,
        BrandCategoryId $brandCategoryId,
        SalePercent $salePercent,
    ): self {
        return new self(
            salePersonalBrandCategoryId: SalePersonalBrandCategoryId::fromString(null),
            userId: $userId,
            brandId: $brandId,
            brandCategoryId: $brandCategoryId,
            salePercent: $salePercent,
        );
    }

    public static function hydrateExisting(
        SalePersonalBrandCategoryId $salePersonalBrandCategoryId,
        UserId $userId,
        BrandId $brandId,
        BrandCategoryId $brandCategoryId,
        SalePercent $salePercent,
    ) {
        if (is_null($salePersonalBrandCategoryId->getId())) {
            throw new InvalidArgumentException(' Rule: id not null. ');
        }
        return new self(
            salePersonalBrandCategoryId: $salePersonalBrandCategoryId,
            userId: $userId,
            brandId: $brandId,
            brandCategoryId: $brandCategoryId,
            salePercent: $salePercent,
        );
    }

    public function change(
        UserId $userId,
        BrandId $brandId,
        BrandCategoryId $brandCategoryId,
        SalePercent $salePercent,
    ): self {
        return new self(
            salePersonalBrandCategoryId: $this->getSalePersonalBrandCategoryId(),
            userId: $userId,
            brandId: $brandId,
            brandCategoryId: $brandCategoryId,
            salePercent: $salePercent,
        );
    }

    public function getSalePersonalBrandCategoryId()
    {
        return $this->salePersonalBrandCategoryId;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getBrandId()
    {
        return $this->brandId;
    }

    public function getBrandCategoryId()
    {
        return $this->brandCategoryId;
    }

    public function getSalePercent()
    {
        return $this->salePercent;
    }
}
