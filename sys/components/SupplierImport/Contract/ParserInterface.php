<?php

declare(strict_types=1);

namespace app\components\SupplierImport\Contract;

use Generator;

interface ParserInterface
{
    public function run(): Generator;
}
