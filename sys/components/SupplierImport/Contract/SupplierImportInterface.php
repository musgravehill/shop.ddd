<?php

declare(strict_types=1);

namespace app\components\SupplierImport\Contract;

use app\components\Supplier\Domain\ValueObject\SupplierId;

interface SupplierImportInterface
{
    public function importProducts(): void;
    public function importImgs(): void;
}
