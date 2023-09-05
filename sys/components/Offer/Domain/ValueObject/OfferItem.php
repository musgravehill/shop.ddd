<?php

declare(strict_types=1);

namespace app\components\Offer\Domain\ValueObject;

use app\components\Brand\Domain\ValueObject\BrandId;
use app\components\BrandCategory\Domain\ValueObject\BrandCategoryId;
use app\components\Offer\Domain\Contract\AppliedSaleTypeIdCollection;
use app\components\Product\Domain\ValueObject\ProductId;
use app\components\Sale\Domain\ValueObject\SaleTypeId;
use app\components\Shared\Domain\ValueObject\Money;
use app\components\Shared\Domain\ValueObject\QuantityPositive;
use app\components\Shared\Domain\ValueObject\ValueObjectAbstract;
use app\components\Shared\Domain\ValueObject\ValueObjectInterface;
use app\components\Supplier\Domain\ValueObject\SupplierId;
use InvalidArgumentException;

final class OfferItem extends ValueObjectAbstract implements ValueObjectInterface
{
    //self-validation
    public function __construct(
        private readonly ProductId $productId,
        private readonly QuantityPositive $productQuantity,
        private readonly Money $priceInitial,
        private readonly Money $priceFinal,
        private readonly BrandId $brandId,
        private readonly BrandCategoryId $brandCategoryId,
        private readonly AppliedSaleTypeIdCollection $appliedSaleTypeIds,
        private readonly SupplierId $supplierId,
    ) {
        if ($this->priceInitial->getFractionalCount() <= 0 || $this->priceFinal->getFractionalCount() <= 0) {
            throw new InvalidArgumentException('Rule: price >0 .');
        }
    }

    public function getStructuralEqualityIdentifier(): string
    {
        return sha1(serialize([$this->productId,])); //you can add params (color white-red, size S-M-L) to array
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

    public function getProductId(): ProductId
    {
        return $this->productId;
    }

    public function getProductQuantity(): QuantityPositive
    {
        return $this->productQuantity;
    }

    public function getPriceInitial(): Money
    {
        return $this->priceInitial;
    }

    public function getPriceFinal(): Money
    {
        return $this->priceFinal;
    }

    public function getBrandId(): BrandId
    {
        return $this->brandId;
    }

    public function getBrandCategoryId(): BrandCategoryId
    {
        return $this->brandCategoryId;
    }

    public function getAppliedSaleTypeIds(): AppliedSaleTypeIdCollection
    {
        return $this->appliedSaleTypeIds;
    }

    public function getSupplierId(): SupplierId
    {
        return $this->supplierId;
    }

    public function applySale(Money $priceFinal, SaleTypeId $saleTypeId): self
    {
        if ($priceFinal->getFractionalCount() <= 0) {
            throw new InvalidArgumentException('Rule: price >0 .');
        }
        $appliedSaleTypeIds = $this->getAppliedSaleTypeIds();
        $appliedSaleTypeIds->append($saleTypeId);
        return new OfferItem(
            productId: $this->getProductId(),
            productQuantity: $this->getProductQuantity(),
            priceInitial: $this->getPriceInitial(),
            priceFinal: $priceFinal,
            brandId: $this->getBrandId(),
            brandCategoryId: $this->getBrandCategoryId(),
            appliedSaleTypeIds: $appliedSaleTypeIds,
            supplierId: $this->getSupplierId(),
        );
    }
}
