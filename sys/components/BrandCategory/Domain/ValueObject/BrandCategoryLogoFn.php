<?php

declare(strict_types=1);

namespace app\components\BrandCategory\Domain\ValueObject;

use app\components\Shared\Domain\ValueObject\ValueObjectAbstract;
use app\components\Shared\Domain\ValueObject\ValueObjectInterface;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

final class BrandCategoryLogoFn extends ValueObjectAbstract implements ValueObjectInterface
{
    //self-validation
    private function __construct(
        private readonly ?string $logoFn 
    ) {
        if (!is_null($logoFn) && !Uuid::isValid($logoFn)) {
            throw new InvalidArgumentException('logoFn should be a Ramsey\Uuid\Uuid.');
        }
    }

    public function getStructuralEqualityIdentifier(): string
    {
        return sha1(serialize([$this->logoFn])); //you can add params (color white-red, size S-M-L) to array
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

    public static function fromString(?string $string): self
    {
        return new self($string);
    }

    public static function new(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public function getLogoFn(): ?string
    {
        return $this->logoFn;
    }
}
