<?php

declare(strict_types=1);

namespace app\components\SupplierImport\Parser;

use app\components\Supplier\Domain\ValueObject\SupplierId;
use app\components\SupplierImport\Contract\ParserInterface;
use Exception;

class ParserFactory
{
    public function getParser(SupplierId $supplierId): ParserInterface
    {
        $chunk = preg_replace('/[^\w\d]/Uui', '', $supplierId->getId());
        $parserClass = 'app\components\SupplierImport\Parser\Parser_' . $chunk;

        if (class_exists($parserClass)) {
            return new $parserClass();
        } else {
            throw new Exception($parserClass . " not found. ");
        }
    }
}
