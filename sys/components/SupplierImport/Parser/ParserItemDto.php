<?php

declare(strict_types=1);

namespace app\components\SupplierImport\Parser;

use app\components\Brand\Domain\ValueObject\BrandName;
use app\components\HelperY;
use app\components\Product\Domain\ValueObject\ImgExternalUrl;
use app\components\Product\Domain\ValueObject\ProductDsc;
use app\components\Product\Domain\ValueObject\ProductName;
use app\components\Product\Domain\ValueObject\ProductSku;
use app\components\Shared\Domain\ValueObject\Money;
use app\components\Shared\Domain\ValueObject\QuantityZeroPositive;

class ParserItemDto
{
    public function __construct(
        private ?string $sku,
        private ?string $brandName,
        private ?string $name,
        private ?string $dsc,
        private QuantityZeroPositive $quantityAvailable,
        private Money $pricePurchase,
        private Money $priceSelling,
        private ?string $imgUrl
    ) {
        $this->sku = is_null($sku) ? null : ProductSku::prepare($sku);
        $this->brandName = is_null($brandName) ? null : BrandName::prepare($brandName);
        $this->name = is_null($name) ? null : ProductName::prepare($name);
        $this->dsc = is_null($dsc) ? null : ProductDsc::prepare($dsc);
        $this->imgUrl = is_null($imgUrl) ? null : ImgExternalUrl::prepare($imgUrl);
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function getBrandName()
    {
        return $this->brandName;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDsc()
    {
        return $this->dsc;
    }

    public function getQuantityAvailable()
    {
        return $this->quantityAvailable;
    }

    public function getPricePurchase()
    {
        return $this->pricePurchase;
    }

    public function getPriceSelling()
    {
        return $this->priceSelling;
    }

    public function getImgUrl()
    {
        return $this->imgUrl;
    }
}
