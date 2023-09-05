<?php

declare(strict_types=1);

namespace app\components\Product\Infrastructure;

use app\components\Brand\Domain\ValueObject\BrandId;
use app\components\BrandCategory\Domain\ValueObject\BrandCategoryId;
use app\components\HelperY;
use app\components\Product\Domain\Entity\Product;
use app\components\Product\Domain\ValueObject\ProductDsc;
use app\components\Product\Domain\ValueObject\ProductExternalId;
use app\components\Product\Domain\ValueObject\ProductId;
use app\components\Product\Domain\ValueObject\ProductName;
use app\components\Product\Domain\ValueObject\ProductSku;
use app\components\Shared\Domain\ValueObject\Money;
use app\components\Shared\Domain\ValueObject\MoneyСurrency;
use app\components\Shared\Domain\ValueObject\QuantityZeroPositive;
use app\components\Shared\Domain\ValueObject\Ufu;
use app\components\Supplier\Domain\ValueObject\SupplierId;
use DateTimeImmutable;
use Yii;

class ProductMigration
{
    public static function migrate(): void
    {
        $productRepository = new ProductRepository;
        $limit = " LIMIT 999999 ";
        $items = Yii::$app->db->createCommand("
                    SELECT
                        p.*                  
                    FROM  {{productold}} p     
                    WHERE p.company_id=1 AND p.type_id=4                     
                    $limit  
                   ")
            ->queryAll();

        if (!$items) {
            return;
        }
        foreach ($items as $item) {
            self::importProduct($item, $productRepository);
        }
    }

    public static function importProduct($item, ProductRepository $productRepository)
    {
        $item['name'] = HelperY::purify($item['name'], '/[^\w\d\s\-\.\(\):]/Uui');
        $item['brand_id'] = ($item['brand_id'] > 0) ? $item['brand_id'] : null;
        $item['brand_category_id'] = ($item['brand_category_id'] > 0) ? $item['brand_category_id'] : null;


        $id = ProductId::fromString((string) $item['id']);
        $ufu = Ufu::fromRu((string) $item['name']);
        $externalId = new ProductExternalId((string) $item['sku']);
        $supplierId = SupplierId::fromString((string) HelperY::params('seoSupplierId'));
        $brandId = BrandId::fromString(is_null($item['brand_id']) ? null : (string) $item['brand_id']);
        $brandCategoryId = BrandCategoryId::fromString(is_null($item['brand_category_id']) ? null : (string) $item['brand_category_id']);
        $pricePurchase = new Money((int) $item['price_purchase'], MoneyСurrency::RUB);
        $priceSelling = new Money((int) $item['price'], MoneyСurrency::RUB);
        $quantityAvailable = new QuantityZeroPositive((int) 0);
        $sku = new ProductSku((string) $item['sku']);
        $name = new ProductName((string) $item['name']);
        $dsc = new ProductDsc((string) $item['dsc']);
        $createdAt = new DateTimeImmutable();
        $updatedAt = new DateTimeImmutable();

        $product = Product::hydrateExisting(
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
        );

        $product = $productRepository->import($product);
    }
}
