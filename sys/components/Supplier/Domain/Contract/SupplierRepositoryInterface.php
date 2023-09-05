<?php

declare(strict_types=1);

namespace app\components\Supplier\Domain\Contract;

use app\components\Shared\Domain\ValueObject\CountOnPage;
use app\components\Shared\Domain\ValueObject\PageNumber;
use app\components\Supplier\Domain\Entity\Supplier;
use app\components\Supplier\Domain\ValueObject\SupplierId;

interface SupplierRepositoryInterface
{
    public function nextId(): SupplierId;
    public function save(Supplier $supplier): ?Supplier;
    /** @return list<Supplier> */
    public function list(PageNumber $page, CountOnPage $cop, bool $withSeo = false): array;
    public function getById(SupplierId $id): ?Supplier;
    public function getCount(): int;
    public function rand(CountOnPage $cop): array;
    public function getQueueSupplierId(int $nextHoursInc): ?SupplierId;
    public function idsNames();
}
