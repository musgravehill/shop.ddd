<?php

declare(strict_types=1);

namespace app\components\Product\Domain\Entity;

use app\components\Brand\Domain\ValueObject\BrandId;
use app\components\BrandCategory\Domain\ValueObject\BrandCategoryId;
use app\components\Product\Domain\ValueObject\ProductDsc;
use app\components\Product\Domain\ValueObject\ProductExternalId;
use app\components\Product\Domain\ValueObject\ProductId;
use app\components\Product\Domain\ValueObject\ProductName;
use app\components\Product\Domain\ValueObject\ProductSku;
use app\components\Shared\Domain\ValueObject\Money;
use app\components\Shared\Domain\ValueObject\QuantityZeroPositive;
use app\components\Shared\Domain\ValueObject\Ufu;
use app\components\Shared\Domain\ValueObject\ViewIdx;
use app\components\Supplier\Domain\ValueObject\SupplierId;
use DateTimeImmutable;
use InvalidArgumentException;

// parser: brandName + 235 symbols name   

class Product
{
    private function __construct(
        private ProductId $id,
        private Ufu $ufu,
        private ProductExternalId $externalId,
        private SupplierId $supplierId,
        private BrandId $brandId,
        private BrandCategoryId $brandCategoryId,
        private Money $pricePurchase,
        private Money $priceSelling,
        private QuantityZeroPositive $quantityAvailable,
        private ProductSku $sku,
        private ProductName $name,
        private ProductDsc $dsc,
        private DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt,
        private ViewIdx $viewIdx,
    ) {
    }

    public static function new(
        ProductExternalId $externalId,
        SupplierId $supplierId,
        BrandId $brandId,
        BrandCategoryId $brandCategoryId,
        Money $pricePurchase,
        Money $priceSelling,
        QuantityZeroPositive $quantityAvailable,
        ProductSku $sku,
        ProductName $name,
        ProductDsc $dsc,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            id: ProductId::fromString(null),
            ufu: Ufu::fromRu($name->getName()),
            externalId: $externalId,
            supplierId: $supplierId,
            brandId: $brandId,
            brandCategoryId: $brandCategoryId,
            pricePurchase: $pricePurchase,
            priceSelling: $priceSelling,
            quantityAvailable: $quantityAvailable,
            sku: $sku,
            name: $name,
            dsc: $dsc,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
            viewIdx: new ViewIdx(0),
        );
    }

    public static function hydrateExisting(
        ProductId $id,
        Ufu $ufu,
        ProductExternalId $externalId,
        SupplierId $supplierId,
        BrandId $brandId,
        BrandCategoryId $brandCategoryId,
        Money $pricePurchase,
        Money $priceSelling,
        QuantityZeroPositive $quantityAvailable,
        ProductSku $sku,
        ProductName $name,
        ProductDsc $dsc,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
        ViewIdx $viewIdx,
    ): self {
        if (is_null($id->getId())) {
            throw new InvalidArgumentException(' Rule: id not null. ');
        }
        return new self(
            id: $id,
            ufu: $ufu,
            externalId: $externalId,
            supplierId: $supplierId,
            brandId: $brandId,
            brandCategoryId: $brandCategoryId,
            pricePurchase: $pricePurchase,
            priceSelling: $priceSelling,
            quantityAvailable: $quantityAvailable,
            sku: $sku,
            name: $name,
            dsc: $dsc,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
            viewIdx: $viewIdx,
        );
    }

    public function updateBySupplier(
        BrandId $brandId,
        Money $pricePurchase,
        Money $priceSelling,
        QuantityZeroPositive $quantityAvailable,
        ProductSku $sku,
        ProductName $name,
        ProductDsc $dsc
    ): self {
        return new self(
            id: $this->getId(),
            ufu: $this->getUfu(),
            externalId: $this->getExternalId(),
            supplierId: $this->getSupplierId(),
            brandId: $brandId,
            brandCategoryId: $this->getBrandCategoryId(),
            pricePurchase: $pricePurchase,
            priceSelling: $priceSelling,
            quantityAvailable: $quantityAvailable,
            sku: $sku,
            name: $name,
            dsc: $dsc,
            createdAt: $this->getCreatedAt(),
            updatedAt: new DateTimeImmutable(),
            viewIdx: $this->getViewIdx(),
        );
    }

    public function setBrandBrandCategory(
        BrandId $brandId,
        BrandCategoryId $brandCategoryId,
    ): self {
        return new self(
            id: $this->getId(),
            ufu: $this->getUfu(),
            externalId: $this->getExternalId(),
            supplierId: $this->getSupplierId(),
            brandId: $brandId,
            brandCategoryId: $brandCategoryId,
            pricePurchase: $this->getPricePurchase(),
            priceSelling: $this->getPriceSelling(),
            quantityAvailable: $this->getQuantityAvailable(),
            sku: $this->getSku(),
            name: $this->getName(),
            dsc: $this->getDsc(),
            createdAt: $this->getCreatedAt(),
            updatedAt: new DateTimeImmutable(),
            viewIdx: $this->getViewIdx(),
        );
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUfu()
    {
        return $this->ufu;
    }

    public function getExternalId()
    {
        return $this->externalId;
    }

    public function getSupplierId()
    {
        return $this->supplierId;
    }

    public function getBrandId()
    {
        return $this->brandId;
    }

    public function getBrandCategoryId()
    {
        return $this->brandCategoryId;
    }

    public function getPricePurchase()
    {
        return $this->pricePurchase;
    }

    public function getPriceSelling()
    {
        return $this->priceSelling;
    }

    public function getQuantityAvailable()
    {
        return $this->quantityAvailable;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDsc()
    {
        return $this->dsc;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function getViewIdx(): ViewIdx
    {
        return $this->viewIdx;
    }
}
