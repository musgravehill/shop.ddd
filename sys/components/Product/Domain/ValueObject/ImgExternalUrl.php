<?php

declare(strict_types=1);

namespace app\components\Product\Domain\ValueObject;

use app\components\Shared\Domain\ValueObject\ValueObjectAbstract;
use app\components\Shared\Domain\ValueObject\ValueObjectInterface;
use InvalidArgumentException;

final class ImgExternalUrl extends ValueObjectAbstract implements ValueObjectInterface
{
    private readonly string $url;

    //self-validation
    public function __construct($url)
    {
        if (mb_strlen($url, "utf-8") > 255) {
            throw new InvalidArgumentException('Rule: url should be 1..255.');
        }
        if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
            throw new InvalidArgumentException('Url is not valid.');
        }
        $imgExts = array("jpg", "jpeg", "png");
        $urlExt = pathinfo($url, PATHINFO_EXTENSION);
        if (!in_array($urlExt, $imgExts)) {
            throw new InvalidArgumentException('Url is not valid.');
        }

        $this->url = (string) $url;
    }

    public static function prepare($str): ?string
    {
        $str = trim($str);
        $str = mb_substr($str, 0, 255, "UTF-8");
        if (filter_var($str, FILTER_VALIDATE_URL) === FALSE) {
            return null;
        }
        $imgExts = array("jpg", "jpeg", "png");
        $urlExt = pathinfo($str, PATHINFO_EXTENSION);
        if (!in_array($urlExt, $imgExts)) {
            return null;
        }

        return $str;
    }

    public function getStructuralEqualityIdentifier(): string
    {
        return sha1(serialize([$this->url])); //you can add params (color white-red, size S-M-L) to array
    }

    //structural equality, compare
    public function isEqualsTo(ValueObjectInterface $vo): bool
    {
        parent::isEqualsTo($vo);
        if ($this->getStructuralEqualityIdentifier() !== $vo->getStructuralEqualityIdentifier()) {
            return false;
        }
        return true;
    }

    public function getExternalUrl(): string
    {
        return $this->url;
    }

    //immutable
}
