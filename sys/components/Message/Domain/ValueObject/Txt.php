<?php

declare(strict_types=1);

namespace app\components\Message\Domain\ValueObject;

use app\components\Shared\Domain\ValueObject\ValueObjectAbstract;
use app\components\Shared\Domain\ValueObject\ValueObjectInterface;
use InvalidArgumentException;

final class Txt extends ValueObjectAbstract implements ValueObjectInterface
{
    //self-validation
    public function __construct(
        private readonly string $txt
    ) {
        if (mb_strlen($txt, "utf-8") > 65535) {
            throw new InvalidArgumentException('Rule: txt should be simple text 1..65535.');
        }
    }

    public static function prepare($str): ?string
    {
        $str = strip_tags($str);
        $str = $str ? str_replace(array('&laquo;', '&raquo;', '«', '»', "'", '&quot;', '`'), '"', $str) : '';
        $str = $str ? str_replace(array('—', '—', '–', '−', '-'), '-', $str) : '';
        $str = preg_replace('/[^\w\d\s\+\-\?\$\.\*\(\)\[\],~!@#№%&=:;<>_\/"]/Uui', ' ', $str);
        $str = preg_replace('/ {2,}/Uui', ' ', $str);
        $str = $str ? str_replace(array("\r", "\n", "\t"), '', $str) : '';
        $str = trim($str);
        $str = mb_substr($str, 0, 65535, "UTF-8");
        return $str;
    }

    public function getStructuralEqualityIdentifier(): string
    {
        return sha1(serialize([$this->txt])); //you can add params (color white-red, size S-M-L) to array
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

    public function getTxt(): string
    {
        return $this->txt;
    }
}
