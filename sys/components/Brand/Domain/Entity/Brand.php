<?php

declare(strict_types=1);

namespace app\components\Brand\Domain\Entity;

use app\components\Brand\Domain\ValueObject\BrandId;
use app\components\Brand\Domain\ValueObject\BrandDsc;
use app\components\Brand\Domain\ValueObject\BrandExternalId;
use app\components\Brand\Domain\ValueObject\BrandLogoFn;
use app\components\Brand\Domain\ValueObject\BrandName;
use app\components\Brand\Domain\ValueObject\NameCanonical;
use app\components\Shared\Domain\ValueObject\Ufu;
use app\components\Shared\Domain\ValueObject\ViewIdx;
use InvalidArgumentException;

class Brand
{
    private function __construct(
        private BrandId $id,
        private BrandName $name,
        private BrandDsc $dsc,
        private BrandExternalId $externalId,
        private Ufu $ufu,
        private BrandLogoFn $logoFn,
        private NameCanonical $nameCanonical,
        private ViewIdx $viewIdx,
    ) {
    }

    public static function new(
        BrandName $name,
        BrandDsc $dsc,
        BrandExternalId $externalId,
        BrandLogoFn $logoFn
    ): self {
        return new self(
            id: BrandId::fromString(null),
            name: $name,
            dsc: $dsc,
            externalId: $externalId,
            ufu: Ufu::fromRu($name->getName()),
            logoFn: $logoFn,
            nameCanonical: NameCanonical::fromRu($name->getName()),
            viewIdx: new ViewIdx(0),
        );
    }

    public static function hydrateExisting(
        BrandId $id,
        BrandName $name,
        BrandDsc $dsc,
        BrandExternalId $externalId,
        Ufu $ufu,
        BrandLogoFn $logoFn,
        NameCanonical $nameCanonical,
        ViewIdx $viewIdx,
    ): self {
        if (is_null($id->getId())) {
            throw new InvalidArgumentException(' Rule: id not null. ');
        }
        return new self(
            id: $id,
            name: $name,
            dsc: $dsc,
            externalId: $externalId,
            ufu: $ufu,
            logoFn: $logoFn,
            nameCanonical: $nameCanonical,
            viewIdx: $viewIdx,
        );
    }

    public function change(
        BrandName $name,
        BrandDsc $dsc,
        BrandExternalId $externalId
    ): self {
        return new self(
            id: $this->getId(),
            name: $name,
            dsc: $dsc,
            externalId: $externalId,
            ufu: Ufu::fromRu($name->getName()),
            logoFn: $this->getLogoFn(),
            nameCanonical: NameCanonical::fromRu($name->getName()),
            viewIdx: $this->getViewIdx(),
        );
    }

    public function changeLogoFn(
        BrandLogoFn $logoFn
    ): self {
        return new self(
            id: $this->getId(),
            name: $this->getName(),
            dsc: $this->getDsc(),
            externalId: $this->getExternalId(),
            ufu: $this->getUfu(),
            logoFn: $logoFn,
            nameCanonical: $this->getNameCanonical(),
            viewIdx: $this->getViewIdx(),
        );
    }

    public function getId(): BrandId
    {
        return $this->id;
    }

    public function getName(): BrandName
    {
        return $this->name;
    }

    public function getDsc(): BrandDsc
    {
        return $this->dsc;
    }

    public function getExternalId(): BrandExternalId
    {
        return $this->externalId;
    }

    public function getUfu(): Ufu
    {
        return $this->ufu;
    }

    public function getLogoFn()
    {
        return $this->logoFn;
    }

    public function getNameCanonical()
    {
        return $this->nameCanonical;
    }

    public function getViewIdx(): ViewIdx
    {
        return $this->viewIdx;
    }
}
