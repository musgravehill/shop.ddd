<?php

declare(strict_types=1);

namespace app\components\Offer\Infrastructure;

use app\components\Brand\Domain\ValueObject\BrandId;
use app\components\BrandCategory\Domain\ValueObject\BrandCategoryId;
use app\components\Offer\Domain\Contract\OfferRepositoryInterface;
use app\components\Offer\Domain\Contract\AppliedSaleTypeIdCollection;
use app\components\Offer\Domain\ValueObject\OfferItem;
use app\components\Product\Domain\Contract\ProductRepositoryInterface;
use app\components\Product\Domain\ValueObject\ProductId;
use app\components\Product\Infrastructure\ProductRepository;
use app\components\Shared\Domain\ValueObject\QuantityPositive;
use app\components\Shared\Domain\ValueObject\Money;
use app\components\Shared\Domain\ValueObject\MoneyСurrency;
use InvalidArgumentException;
use Yii;
use yii\db\Query;

class OfferRepository implements OfferRepositoryInterface
{

    private ProductRepositoryInterface $productRepository;

    public function __construct()
    {
        $this->productRepository = new ProductRepository;
    }

    public function getOfferItem(ProductId $productId, QuantityPositive $productQuantity): ?OfferItem
    {
        $product = $this->productRepository->getById($productId);
        if (is_null($product)) {
            return null;
        }

        if ($product->getPriceSelling()->getFractionalCount() <= 0) {  // <= 1cent 
            return null;
        }

        return new OfferItem(
            productId: $productId,
            productQuantity: $productQuantity,
            priceInitial: $product->getPriceSelling(),
            priceFinal: $product->getPriceSelling(),
            brandId: $product->getBrandId(),
            brandCategoryId: $product->getBrandCategoryId(),
            appliedSaleTypeIds: new AppliedSaleTypeIdCollection(),
            supplierId: $product->getSupplierId(),
        );
    }
}
