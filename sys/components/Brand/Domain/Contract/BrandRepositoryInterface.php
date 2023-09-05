<?php

declare(strict_types=1);

namespace app\components\Brand\Domain\Contract;

use app\components\Shared\Domain\ValueObject\CountOnPage;
use app\components\Shared\Domain\ValueObject\PageNumber;
use app\components\Brand\Domain\Entity\Brand;
use app\components\Brand\Domain\ValueObject\BrandId;
use app\components\Brand\Domain\ValueObject\BrandName;

interface BrandRepositoryInterface
{
    public function nextId(): BrandId;
    public function save(Brand $brand): ?Brand;
    /** @return list<Brand> */
    public function list(PageNumber $page, CountOnPage $cop): array;
    public function getById(BrandId $id): ?Brand;
    public function rand(CountOnPage $cop): array;
    public function idsNames(): array;
    public function getIdByName(string $nameRaw): BrandId;
    public function generateNameCanonicals(): void;
    public function incrementViewIdx(BrandId $id): void;
    public function popular(CountOnPage $cop): array;
}
