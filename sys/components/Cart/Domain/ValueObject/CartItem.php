<?php

declare(strict_types=1);

namespace app\components\Cart\Domain\ValueObject;

use app\components\Shared\Domain\ValueObject\ValueObjectAbstract;
use app\components\Shared\Domain\ValueObject\ValueObjectInterface;
use app\components\Product\Domain\ValueObject\ProductId;
use app\components\Shared\Domain\ValueObject\QuantityPositive;
use InvalidArgumentException;

final class CartItem extends ValueObjectAbstract implements ValueObjectInterface
{
    //self-validation
    public function __construct(
        private readonly ProductId $productId,
        private readonly QuantityPositive $productQuantity
    ) {
    }
    
    public function getStructuralEqualityIdentifier(): string
    {
        return sha1(serialize([$this->productId,])); //you can add params (color white-red, size S-M-L) to array
    }

    public function getProductQuantity(): QuantityPositive
    {
        return $this->productQuantity;
    }

    public function getProductId(): ProductId
    {
        return $this->productId;
    }

    //immutable    
    public function plus(CartItem $cartItem): self
    {
        if (!$this->isEqualsTo($cartItem)) {
            throw new InvalidArgumentException('Items are not equals.');
        }

        return new self($this->productId, $this->productQuantity->getSumWith($cartItem->getProductQuantity()));
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
}
