<?php

declare(strict_types=1);

namespace app\components\SupplierImport\tests;

use app\components\Supplier\Domain\ValueObject\SupplierId;
use app\components\SupplierImport\Parser\ParserFactory;
use PHPUnit\Framework\TestCase;

final class SupplierImportTest extends TestCase
{
    public function testParser(): void
    {
        $supplierId = SupplierId::fromString('01887d79-fd0c-71e1-bae6-368aa81e9503');
        $parserFactory = new ParserFactory;
        $parser = $parserFactory->getParser($supplierId);
        foreach ($parser->run() as $dto) {
            print_r($dto);
        }
    }
}
