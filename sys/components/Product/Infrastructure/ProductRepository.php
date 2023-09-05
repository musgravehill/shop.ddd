<?php

declare(strict_types=1);

namespace app\components\Product\Infrastructure;

use app\components\Brand\Domain\ValueObject\BrandId;
use app\components\BrandCategory\Domain\ValueObject\BrandCategoryId;
use app\components\Currency\CurrencyService;
use app\components\HelperY;
use app\components\Shared\Domain\ValueObject\CountOnPage;
use app\components\Shared\Domain\ValueObject\PageNumber;
use app\components\Product\Domain\Contract\ProductRepositoryInterface;
use app\components\Product\Domain\Entity\Product;
use app\components\Product\Domain\ValueObject\ProductDsc;
use app\components\Product\Domain\ValueObject\ProductExternalId;
use app\components\Product\Domain\ValueObject\ProductId;
use app\components\Product\Domain\ValueObject\ProductName;
use app\components\Product\Domain\ValueObject\ProductSku;
use app\components\Search\Domain\SearchQuery;
use app\components\Shared\Domain\ValueObject\Money;
use app\components\Shared\Domain\ValueObject\MoneyСurrency;
use app\components\Shared\Domain\ValueObject\QuantityZeroPositive;
use app\components\Shared\Domain\ValueObject\Ufu;
use app\components\Shared\Domain\ValueObject\ViewIdx;
use app\components\Supplier\Domain\ValueObject\SupplierId;
use DateTimeImmutable;
use Exception;
use LogicException;
use Ramsey\Uuid\Uuid;
use Yii;

class ProductRepository implements ProductRepositoryInterface
{
    private readonly CurrencyService $currencyService;

    public function __construct()
    {
        $this->currencyService = new CurrencyService;
    }

    public function nextId(): ProductId
    {
        $nextId = null;
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            Yii::$app->db->createCommand()->insert(
                'product',
                [
                    'id' => null,
                    'ufu' => 'nextId',
                    'externalId' => 'nextId',
                    'supplierId' => 'nextId',
                    'brandId' => 0,
                    'brandCategoryId' => 0,
                    'pricePurchase' => 0,
                    'priceSelling' => 0,
                    'quantityAvailable' => 0,
                    'sku' => 'nextId',
                    'name' => 'nextId',
                    'dsc' => 'nextId',
                    'createdAt' => time(),
                    'updatedAt' => time(),
                    'viewIdx' => 0,
                ]
            )->execute();

            $data = Yii::$app->db->createCommand(' SELECT MAX(id) as max_id FROM product ')->queryOne();

            if ($data) {
                $nextId = (string) $data['max_id'];
            }
            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
        }

        if (is_null($nextId)) {
            throw new Exception(' Rule: seq not null. ');
        }

        return ProductId::fromString((string) $nextId);
    }

    public function rand(CountOnPage $cop, BrandId $brandId): array
    {
        $res = [];

        $condBrand = ' ';
        if (!is_null($brandId->getId())) {
            $condBrand = " AND brandId ='" . $brandId->getId() . "' ";
        }

        $limit = " LIMIT " . $cop->getCop() . ' ';
        $items = Yii::$app->db->createCommand("
            SELECT
                product.*               
            FROM  {{product}} product   
            WHERE 
                1=1
                $condBrand                
            ORDER BY RAND()  
            $limit 
                   ")
            ->queryAll();

        if (!$items) {
            return $res;
        }

        foreach ($items as $item) {
            $res[] =  Product::hydrateExisting(
                id: ProductId::fromString((string) $item['id']),
                ufu: Ufu::hydrateExisting((string) $item['ufu']),
                externalId: new ProductExternalId((string) $item['externalId']),
                supplierId: SupplierId::fromString((string) $item['supplierId']),
                brandId: BrandId::fromString(is_null($item['brandId']) ? null : (string) $item['brandId']),
                brandCategoryId: BrandCategoryId::fromString(is_null($item['brandCategoryId']) ? null : (string) $item['brandCategoryId']),
                pricePurchase: new Money((int) $item['pricePurchase'], MoneyСurrency::RUB),
                priceSelling: new Money((int) $item['priceSelling'], MoneyСurrency::RUB),
                quantityAvailable: new QuantityZeroPositive((int) $item['quantityAvailable']),
                sku: new ProductSku((string) $item['sku']),
                name: new ProductName((string) $item['name']),
                dsc: new ProductDsc((string) $item['dsc']),
                createdAt: (new DateTimeImmutable())->setTimestamp($item['createdAt']),
                updatedAt: (new DateTimeImmutable())->setTimestamp($item['updatedAt']),
                viewIdx: new ViewIdx(intval($item['viewIdx'])),
            );
        }
        return $res;
    }

    public function popular(SupplierId $supplierId, CountOnPage $cop, BrandId $brandId, DateTimeImmutable $obsoleteСonstraintAt): array
    {
        $res = [];

        $condBrand = ' ';
        if (!is_null($brandId->getId())) {
            $condBrand = " AND brandId ='" . $brandId->getId() . "' ";
        }

        $condSupplierId = " AND supplierId = '" . $supplierId->getId() . "' ";
        $condObsolete = ' AND updatedAt >= ' . $obsoleteСonstraintAt->getTimestamp() . ' ';

        $limit = " LIMIT " . $cop->getCop() . ' ';
        $items = Yii::$app->db->createCommand("
            SELECT
                product.*               
            FROM  {{product}} product   
            WHERE 
                1=1
                $condBrand    
                $condSupplierId   
                $condObsolete           
            ORDER BY viewIdx DESC  
            $limit 
                   ")
            ->queryAll();

        if (!$items) {
            return $res;
        }

        foreach ($items as $item) {
            $res[] =  Product::hydrateExisting(
                id: ProductId::fromString((string) $item['id']),
                ufu: Ufu::hydrateExisting((string) $item['ufu']),
                externalId: new ProductExternalId((string) $item['externalId']),
                supplierId: SupplierId::fromString((string) $item['supplierId']),
                brandId: BrandId::fromString(is_null($item['brandId']) ? null : (string) $item['brandId']),
                brandCategoryId: BrandCategoryId::fromString(is_null($item['brandCategoryId']) ? null : (string) $item['brandCategoryId']),
                pricePurchase: new Money((int) $item['pricePurchase'], MoneyСurrency::RUB),
                priceSelling: new Money((int) $item['priceSelling'], MoneyСurrency::RUB),
                quantityAvailable: new QuantityZeroPositive((int) $item['quantityAvailable']),
                sku: new ProductSku((string) $item['sku']),
                name: new ProductName((string) $item['name']),
                dsc: new ProductDsc((string) $item['dsc']),
                createdAt: (new DateTimeImmutable())->setTimestamp($item['createdAt']),
                updatedAt: (new DateTimeImmutable())->setTimestamp($item['updatedAt']),
                viewIdx: new ViewIdx(intval($item['viewIdx'])),
            );
        }
        return $res;
    }

    public function getByExternalData(ProductExternalId $productExternalId, SupplierId $supplierId): ?Product
    {
        $item = Yii::$app->db->createCommand("
        SELECT
            product.*               
        FROM  {{product}} product                   
        WHERE
            product.externalId='" . $productExternalId->getId() . "' 
            AND product.supplierId='" . $supplierId->getId() . "'
        LIMIT 1                    
        ")
            ->queryOne();
        if (!$item) {
            return null;
        }

        $product = Product::hydrateExisting(
            id: ProductId::fromString((string) $item['id']),
            ufu: Ufu::hydrateExisting((string) $item['ufu']),
            externalId: new ProductExternalId((string) $item['externalId']),
            supplierId: SupplierId::fromString((string) $item['supplierId']),
            brandId: BrandId::fromString(is_null($item['brandId']) ? null : (string) $item['brandId']),
            brandCategoryId: BrandCategoryId::fromString(is_null($item['brandCategoryId']) ? null : (string) $item['brandCategoryId']),
            pricePurchase: new Money((int) $item['pricePurchase'], MoneyСurrency::RUB),
            priceSelling: new Money((int) $item['priceSelling'], MoneyСurrency::RUB),
            quantityAvailable: new QuantityZeroPositive((int) $item['quantityAvailable']),
            sku: new ProductSku((string) $item['sku']),
            name: new ProductName((string) $item['name']),
            dsc: new ProductDsc((string) $item['dsc']),
            createdAt: (new DateTimeImmutable())->setTimestamp($item['createdAt']),
            updatedAt: (new DateTimeImmutable())->setTimestamp($item['updatedAt']),
            viewIdx: new ViewIdx(intval($item['viewIdx'])),
        );

        return $product;
    }

    public function getById(ProductId $id): ?Product
    {
        $item = Yii::$app->db->createCommand("
        SELECT
            product.*               
        FROM  {{product}} product                   
        WHERE
            product.id='" . $id->getId() . "'
        LIMIT 1                    
        ")
            ->queryOne();
        if (!$item) {
            return null;
        }

        $product = Product::hydrateExisting(
            id: ProductId::fromString((string) $item['id']),
            ufu: Ufu::hydrateExisting((string) $item['ufu']),
            externalId: new ProductExternalId((string) $item['externalId']),
            supplierId: SupplierId::fromString((string) $item['supplierId']),
            brandId: BrandId::fromString(is_null($item['brandId']) ? null : (string) $item['brandId']),
            brandCategoryId: BrandCategoryId::fromString(is_null($item['brandCategoryId']) ? null : (string) $item['brandCategoryId']),
            pricePurchase: new Money((int) $item['pricePurchase'], MoneyСurrency::RUB),
            priceSelling: new Money((int) $item['priceSelling'], MoneyСurrency::RUB),
            quantityAvailable: new QuantityZeroPositive((int) $item['quantityAvailable']),
            sku: new ProductSku((string) $item['sku']),
            name: new ProductName((string) $item['name']),
            dsc: new ProductDsc((string) $item['dsc']),
            createdAt: (new DateTimeImmutable())->setTimestamp($item['createdAt']),
            updatedAt: (new DateTimeImmutable())->setTimestamp($item['updatedAt']),
            viewIdx: new ViewIdx(intval($item['viewIdx'])),
        );

        return $product;
    }

    public function save(Product $product): ?Product
    {
        $res = null;
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            if (is_null($product->getId()->getId())) {
                $res = $this->new($product);
            } else {
                $res = $this->update($product);
            }
            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw new Exception('Save: error!');
        }

        return $res;
    }

    private function new(Product $product): ?Product
    {
        $productId = $this->nextId();
        $pricePurchaseRub = $this->currencyService->convertToRub($product->getPricePurchase());
        $priceSellingRub = $this->currencyService->convertToRub($product->getPriceSelling());
        Yii::$app->db->createCommand()->update(
            'product',
            [
                'ufu' => $product->getUfu()->getUfu(),
                'externalId' => $product->getExternalId()->getId(),
                'supplierId' => $product->getSupplierId()->getId(),
                'brandId' => $product->getBrandId()->getId(),
                'brandCategoryId' => $product->getBrandCategoryId()->getId(),
                'pricePurchase' => $pricePurchaseRub->getFractionalCount(),
                'priceSelling' => $priceSellingRub->getFractionalCount(),
                'quantityAvailable' => $product->getQuantityAvailable()->getQuantity(),
                'sku' => $product->getSku()->getSku(),
                'name' => $product->getName()->getName(),
                'dsc' => $product->getDsc()->getDsc(),
                'createdAt' => $product->getCreatedAt()->getTimestamp(),
                'updatedAt' => $product->getUpdatedAt()->getTimestamp(),
                'viewIdx' => 0,
            ],
            " id = '" . $productId->getId() . "' "
        )->execute();

        return $this->getById($productId);
    }

    private function update(Product $product): ?Product
    {
        $productId = $product->getId();
        $pricePurchaseRub = $this->currencyService->convertToRub($product->getPricePurchase());
        $priceSellingRub = $this->currencyService->convertToRub($product->getPriceSelling());
        Yii::$app->db->createCommand()->update(
            'product',
            [
                'ufu' => $product->getUfu()->getUfu(),
                'externalId' => $product->getExternalId()->getId(),
                'supplierId' => $product->getSupplierId()->getId(),
                'brandId' => $product->getBrandId()->getId(),
                'brandCategoryId' => $product->getBrandCategoryId()->getId(),
                'pricePurchase' => $pricePurchaseRub->getFractionalCount(),
                'priceSelling' => $priceSellingRub->getFractionalCount(),
                'quantityAvailable' => $product->getQuantityAvailable()->getQuantity(),
                'sku' => $product->getSku()->getSku(),
                'name' => $product->getName()->getName(),
                'dsc' => $product->getDsc()->getDsc(),
                'createdAt' => $product->getCreatedAt()->getTimestamp(),
                'updatedAt' => $product->getUpdatedAt()->getTimestamp(),
            ],
            " id = '" . $productId->getId() . "' "
        )->execute();

        return $this->getById($productId);
    }


    public function import(Product $product): void
    {
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            Yii::$app->db->createCommand()->insert(
                'product',
                [
                    'id' => $product->getId()->getId(),
                    'ufu' => $product->getUfu()->getUfu(),
                    'externalId' => $product->getExternalId()->getId(),
                    'supplierId' => $product->getSupplierId()->getId(),
                    'brandId' => $product->getBrandId()->getId(),
                    'brandCategoryId' => $product->getBrandCategoryId()->getId(),
                    'pricePurchase' => $product->getPricePurchase()->getFractionalCount(),
                    'priceSelling' => $product->getPriceSelling()->getFractionalCount(),
                    'quantityAvailable' => $product->getQuantityAvailable()->getQuantity(),
                    'sku' => $product->getSku()->getSku(),
                    'name' => $product->getName()->getName(),
                    'dsc' => $product->getDsc()->getDsc(),
                    'createdAt' => $product->getCreatedAt()->getTimestamp(),
                    'updatedAt' => $product->getUpdatedAt()->getTimestamp(),
                    'viewIdx' => 0,
                ]
            )->execute();

            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
        }
    }

    public function incrementViewIdx(ProductId $id): void
    {
        $idString = $id->getId();
        Yii::$app->db->createCommand("
                    UPDATE                                        
                        {{product}} product                        
                    SET 
                        viewIdx = viewIdx + 1 
                    WHERE id='$idString'   
                    LIMIT 1     
                   ")
            ->execute();
    }

    /** @return list<Product> */
    public function list(
        PageNumber $page,
        CountOnPage $cop,
        SearchQuery $q,
        BrandId $brandId,
        BrandCategoryId $brandCategoryId,
        bool $BBcEmptyOnly,
        SupplierId $supplierId,
    ): array {
        $res = [];

        $offset = (int) ($page->getPageNumber() - 1) * $cop->getCop();
        $limit = " LIMIT $offset, " . $cop->getCop() . ' ';

        $q_cond = ' ';
        if (isset($q->getSearchQuery()[1])) {
            $qs = explode(' ', $q->getSearchQuery());
            foreach ($qs as $q) {
                if (isset($q[1])) {
                    $q_cond .= " AND p.name LIKE '%$q%' ";
                }
            }
        }

        $brandId_cond = ' ';
        if (!is_null($brandId->getId())) {
            $brandId_cond = " AND p.brandId='" . $brandId->getId() . "' ";
        }

        $brandCategoryId_cond = ' ';
        if (!is_null($brandCategoryId->getId())) {
            $brandCategoryId_cond = " AND p.brandCategoryId='" . $brandCategoryId->getId() . "' ";
        }

        $supplierId_cond = ' ';
        if (!is_null($supplierId->getId())) {
            $supplierId_cond = " AND p.supplierId='" . $supplierId->getId() . "' ";
        }

        $BBcEmptyOnly_cond = ' ';
        if ($BBcEmptyOnly) {
            $BBcEmptyOnly_cond = " AND p.brandId IS NULL AND p.brandCategoryId IS NULL ";
        }

        $items = Yii::$app->db->createCommand("
                    SELECT
                        p.*                
                    FROM  {{product}} p       
                    WHERE 
                        1=1 
                        $brandId_cond 
                        $brandCategoryId_cond 
                        $q_cond         
                        $BBcEmptyOnly_cond     
                        $supplierId_cond 
                    ORDER BY p.viewIdx DESC    
                    $limit 
                   ")
            ->queryAll();

        if (!$items) {
            return $res;
        }

        foreach ($items as $item) {
            $res[] =  Product::hydrateExisting(
                id: ProductId::fromString((string) $item['id']),
                ufu: Ufu::hydrateExisting((string) $item['ufu']),
                externalId: new ProductExternalId((string) $item['externalId']),
                supplierId: SupplierId::fromString((string) $item['supplierId']),
                brandId: BrandId::fromString(is_null($item['brandId']) ? null : (string) $item['brandId']),
                brandCategoryId: BrandCategoryId::fromString(is_null($item['brandCategoryId']) ? null : (string) $item['brandCategoryId']),
                pricePurchase: new Money((int) $item['pricePurchase'], MoneyСurrency::RUB),
                priceSelling: new Money((int) $item['priceSelling'], MoneyСurrency::RUB),
                quantityAvailable: new QuantityZeroPositive((int) $item['quantityAvailable']),
                sku: new ProductSku((string) $item['sku']),
                name: new ProductName((string) $item['name']),
                dsc: new ProductDsc((string) $item['dsc']),
                createdAt: (new DateTimeImmutable())->setTimestamp($item['createdAt']),
                updatedAt: (new DateTimeImmutable())->setTimestamp($item['updatedAt']),
                viewIdx: new ViewIdx(intval($item['viewIdx'])),
            );
        }
        return $res;
    }

    public function totalCount(): int
    {
        $item = Yii::$app->db->createCommand("
                    SELECT
                        COUNT(p.id) as countId               
                    FROM  {{product}} p                       
                   ")
            ->queryOne();

        return intval($item['countId']);
    }
}
