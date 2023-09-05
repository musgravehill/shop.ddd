<?php

declare(strict_types=1);

namespace app\components\Brand\Domain\ValueObject;

use app\components\Shared\Domain\ValueObject\ValueObjectAbstract;
use app\components\Shared\Domain\ValueObject\ValueObjectInterface;
use InvalidArgumentException;

final class NameCanonical extends ValueObjectAbstract implements ValueObjectInterface
{
    //self-validation
    private function __construct(
        private readonly string $namecanonical
    ) {
        $l = mb_strlen($namecanonical, "UTF-8");
        if ($l < 1 || $l > 128) {
            throw new InvalidArgumentException('Rule: namecanonical.L 1..128. ');
        }
        if (!preg_match('/^[a-z\d]{1,128}$/u', $namecanonical)) {
            throw new InvalidArgumentException('Rule: namecanonical should be simple text 1..128.');
        }
    }

    public static function fromRu($str): self
    {
        $str = self::transform($str);
        $str = preg_replace('/[^a-z\d]/Uui', '', $str);
        $str = substr($str, 0, 128);
        return new self($str);
    }

    public static function hydrateExisting($namecanonical): self
    {
        return new self($namecanonical);
    }

    public function getStructuralEqualityIdentifier(): string
    {
        return sha1(serialize([$this->namecanonical])); //you can add params (color white-red, size S-M-L) to array
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

    public function getNamecanonical(): string
    {
        return $this->namecanonical;
    }

    private static function transform($text)
    {
        $text = mb_strtolower($text, "utf-8");
        $cyr = array('а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я',);
        $lat = array('a', 'b', 'v', 'g', 'd', 'e', 'e', 'zh', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'ts', 'ch', 'sh', 'sht', '', 'y', '', 'e', 'yu', 'ya',);
        $url = $text ? str_replace($cyr, $lat, $text) : '';
        $url = preg_replace("/[^a-z\d]/Uui", '', $url);
        return $url;
    }
}
