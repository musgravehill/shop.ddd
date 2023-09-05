<?php

declare(strict_types=1);

namespace app\components\Cart\tests;

use PHPUnit\Framework\TestCase;

use app\components\Cart\Domain\Aggregate\Cart;
use app\components\Cart\Domain\ValueObject\CartItem;
use app\components\Product\Domain\ValueObject\ProductId;
use app\components\Shared\Domain\ValueObject\QuantityPositive;
use app\components\Cart\Infrastructure\CartMemRepository;

final class CartTest extends TestCase
{
    public function testGuest(): void
    {
        $userId = null;
        $repository = new CartMemRepository();         
        $cart = Cart::create(repository: $repository);
        $this->assertInstanceOf(
            expected: Cart::class,
            actual: $cart
        );

        $item = new CartItem(
            productId: ProductId::fromString('1'),
            productQuantity: new QuantityPositive(3)
        );
        $cart->setItem($item);
        $item = new CartItem(
            productId: ProductId::fromString('1'),
            productQuantity: new QuantityPositive(10)
        );
        $cart->setItem($item);
        $res = $cart->getItems();
        $this->assertEquals(
            expected: 10,
            actual: $res[0]->getProductQuantity()->getQuantity()
        );

        $item = new CartItem(
            productId: ProductId::fromString('2'),
            productQuantity: new QuantityPositive(20)
        );
        $cart->setItem($item);
        $res = $cart->getItems();
        $this->assertEquals(
            expected: 30,
            actual: array_reduce(
                $res->toArray(),
                function ($accumulator, $item) {
                    $accumulator += $item->getProductQuantity()->getQuantity();
                    return $accumulator;
                }
            ) ?? 0
        );
    }

    public function testClient(): void
    {
        $userId = 1;
        $repository = new CartMemRepository();        
        $cart = Cart::create(repository: $repository);
        $this->assertInstanceOf(
            expected: Cart::class,
            actual: $cart
        );

        $item = new CartItem(
            productId: ProductId::fromString('1'),
            productQuantity: new QuantityPositive(3) 
        );
        $cart->setItem($item);
        $item = new CartItem(
            productId: ProductId::fromString('1'),
            productQuantity: new QuantityPositive(10)
        );
        $cart->setItem($item);
        $res = $cart->getItems();
        $this->assertEquals(
            expected: 10,
            actual: $res[0]->getProductQuantity()->getQuantity()
        );

        $item = new CartItem(
            productId: ProductId::fromString('2'),
            productQuantity: new QuantityPositive(20)
        );
        $cart->setItem($item);
        $res = $cart->getItems();
        $this->assertEquals(
            expected: 30,
            actual: array_reduce(
                $res->toArray(),
                function ($accumulator, $item) {
                    $accumulator += $item->getProductQuantity()->getQuantity();
                    return $accumulator;
                }
            ) ?? 0
        );

        // $this->expectException(\InvalidArgumentException::class);
    }
}
