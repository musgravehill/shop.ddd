<?php

declare(strict_types=1);

namespace app\components\Offer\Infrastructure;

use app\components\Brand\Domain\ValueObject\BrandId;
use app\components\BrandCategory\Domain\ValueObject\BrandCategoryId;
use app\components\Offer\Domain\Contract\OfferRepositoryInterface;
use app\components\Offer\Domain\Contract\AppliedSaleTypeIdCollection;   
use app\components\Offer\Domain\ValueObject\OfferItem;
use app\components\Product\Domain\ValueObject\ProductId;
use app\components\Shared\Domain\ValueObject\QuantityPositive;
use app\components\Shared\Domain\ValueObject\Money;
use app\components\Shared\Domain\ValueObject\MoneyСurrency;
use app\components\Supplier\Domain\ValueObject\SupplierId;

class OfferRepositoryMock implements OfferRepositoryInterface
{
    private array $data = [
        '1' => [
            'price' => 10,
            'brand_id' => 11,
            'brand_category_id' => 12,
        ],
        '2' => [
            'price' => 20,
            'brand_id' => 21,
            'brand_category_id' => 22,
        ],
    ];
    public function getOfferItem(ProductId $productId, QuantityPositive $productQuantity): ?OfferItem
    {
        $row = $this->data[$productId->getId()];
        $price = new Money(fractionalCount: intval($row['price']), currency: MoneyСurrency::RUB);
        $brandId = BrandId::fromString($row['brand_id']);
        $brandCategoryId = BrandCategoryId::fromString($row['brand_category_id']);

        return new OfferItem(
            productId: $productId,
            productQuantity: $productQuantity,
            priceInitial: $price,
            priceFinal: $price,
            brandId: $brandId,
            brandCategoryId: $brandCategoryId,
            appliedSaleTypeIds: new AppliedSaleTypeIdCollection(),
            supplierId: SupplierId::fromString('018872a0-64e9-724d-961f-8dbbc9530094'),
        );
    }
}
