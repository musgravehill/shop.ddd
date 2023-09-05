<?php

declare(strict_types=1);

namespace app\components\Product\Domain\ValueObject;

use app\components\Shared\Domain\ValueObject\ValueObjectAbstract;
use app\components\Shared\Domain\ValueObject\ValueObjectInterface;
use InvalidArgumentException;

final class ProductSku extends ValueObjectAbstract implements ValueObjectInterface
{
    //self-validation
    public function __construct(
        private readonly string $sku
    ) {
        if (mb_strlen($sku, "utf-8") > 64) {
            throw new InvalidArgumentException('Rule: sku should be simple text 1..64.');
        }
        if (!preg_match('/^[\w\d]{1,64}$/u', $sku)) {
            throw new InvalidArgumentException('Rule: sku should be simple text 1..64.');
        }
    }

    public static function prepare($str): ?string 
    {
        //$str = strip_tags($str);
        //$str = $str ? str_replace(array('&laquo;', '&raquo;', '«', '»', "'", '&quot;', '`'), '', $str) : '';
        //$str = $str ? str_replace(array('—', '—', '–', '−', '-'), '-', $str) : '';
        $str = preg_replace('/[^\w\d]/Uui', '', $str);
        //$str = preg_replace('/ {2,}/Uui', ' ', $str);
        //$str = $str ? str_replace(array("\r", "\n", "\t"), '', $str) : '';
        //$str = trim($str);
        $str = mb_substr($str, 0, 64, "UTF-8");
        return $str;
    }

    public function getStructuralEqualityIdentifier(): string
    {
        return sha1(serialize([$this->sku])); //you can add params (color white-red, size S-M-L) to array
    }

    //structural equality, compare      
    public function isEqualsTo(ValueObjectInterface $vo): bool
    {
        parent::isEqualsTo($vo);
        /** @var self $vo */
        if ($this->getStructuralEqualityIdentifier() !== $vo->getStructuralEqualityIdentifier()) {
            return false;
        }
        return true;
    }

    public function getSku(): string
    {
        return $this->sku;
    }
}
