<?php

declare(strict_types=1);

namespace app\components\Supplier\Domain\Entity;

use app\components\Supplier\Domain\ValueObject\CityName;
use app\components\Supplier\Domain\ValueObject\SupplierId;
use app\components\Supplier\Domain\ValueObject\ImgUrl;
use app\components\Supplier\Domain\ValueObject\SupplierDsc;
use app\components\Supplier\Domain\ValueObject\SupplierName;
use DateTimeImmutable;
use InvalidArgumentException;

class Supplier 
{
    private function __construct(
        private SupplierId $id,
        private SupplierName $name,
        private SupplierDsc $dsc,
        private ImgUrl $imgUrl,
        private DateTimeImmutable $taskDownloadAt,
        private readonly CityName $cityName,
    ) {
    }

    public static function new(
        SupplierName $name,
        SupplierDsc $dsc,
        ImgUrl $imgUrl,
        DateTimeImmutable $taskDownloadAt,
        CityName $cityName,
    ): self {
        return new self(
            id: SupplierId::fromString(null),
            name: $name,
            dsc: $dsc,
            imgUrl: $imgUrl,
            taskDownloadAt: $taskDownloadAt,
            cityName: $cityName,
        );
    }

    public static function hydrateExisting(
        SupplierId $id,
        SupplierName $name,
        SupplierDsc $dsc,
        ImgUrl $imgUrl,
        DateTimeImmutable $taskDownloadAt,
        CityName $cityName,
    ): self {
        if (is_null($id->getId())) {
            throw new InvalidArgumentException(' Rule: id not null. ');
        }
        return new self(
            id: $id,
            name: $name,
            dsc: $dsc,
            imgUrl: $imgUrl,
            taskDownloadAt: $taskDownloadAt,
            cityName: $cityName,
        );
    }

    public function getId(): SupplierId
    {
        return $this->id;
    }
    public function getName(): SupplierName
    {
        return $this->name;
    }
    public function getDsc(): SupplierDsc
    {
        return $this->dsc;
    }
    public function getImgUrl(): ImgUrl
    {
        return $this->imgUrl;
    }
    public function getTaskDownloadAt()
    {
        return $this->taskDownloadAt;
    }

    public function getCityName(): CityName
    {
        return $this->cityName;
    }
}
