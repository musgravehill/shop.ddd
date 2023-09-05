<?php

declare(strict_types=1);

namespace app\components\Product\Domain\ValueObject;

use app\components\Shared\Domain\ValueObject\ValueObjectAbstract;
use app\components\Shared\Domain\ValueObject\ValueObjectInterface;
use InvalidArgumentException;

final class ImgExternalUrlHash extends ValueObjectAbstract implements ValueObjectInterface
{
    private readonly string $hash;

    //self-validation
    private function __construct($hash)
    {
        $l = mb_strlen($hash, "utf-8");
        if ($l !== 32) {
            throw new InvalidArgumentException('Rule: hash should be 32.');
        }
        $this->hash = (string) $hash;
    }

    public function getStructuralEqualityIdentifier(): string
    {
        return sha1(serialize([$this->hash])); //you can add params (color white-red, size S-M-L) to array
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

    public function getExternalUrlHash(): string
    {
        return $this->hash;
    }

    public static function fromString(string $hash): self
    {
        return new self($hash);
    }

    public static function generateExternalUrlHash(ImgExternalUrl $externalUrl): self
    {
        $hash = md5($externalUrl->getExternalUrl());
        return new self($hash);
    }

    //immutable
}
