<?php

declare(strict_types=1);

namespace app\components\Order\tests;

use app\components\Order\Domain\Contract\OrderItemCollection;
use PHPUnit\Framework\TestCase;

final class OrderItemCollectionTest extends TestCase
{
    public function testForeignConstruct(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $c = new OrderItemCollection([1, 2, 3]);
    }

    public function testForeignAdd(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $c = new OrderItemCollection();
        $c[] = 1;
    }

    public function testForeignSet(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $c = new OrderItemCollection();
        $c[0] = 1;
    }    
}
