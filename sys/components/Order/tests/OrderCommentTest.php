<?php

declare(strict_types=1);

namespace app\components\Order\tests;  

use app\components\Order\Domain\ValueObject\OrderComment;
use PHPUnit\Framework\TestCase;

final class OrderCommentTest extends TestCase
{
    public function testOutOfRange(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $ci = new OrderComment(            
             comment: '1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890' .
             '1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890' .
             '1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890' .
             '1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890' .
             '1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890' .
             '1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890' 
        );
    }

    public function testForbiddenSymbolTag(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $ci = new OrderComment(            
             comment: '<script></script>'
        );
    }

    public function testForbiddenSymbolPhp(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $ci = new OrderComment(            
             comment: '<?php echo 1; ?>'
        );
    }

    public function testForbiddenSymbolQuotes1(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $ci = new OrderComment(            
             comment: '`'
        );
    }
    public function testForbiddenSymbolQuotes2(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $ci = new OrderComment(            
             comment: '"'
        );
    }
    public function testForbiddenSymbolQuotes3(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $ci = new OrderComment(            
             comment: "'"
        );
    }

    public function testNormal(): void
    {
        $ci = new OrderComment(            
            comment: '10000 г. Москва Район Кремля'
       );
        $this->assertInstanceOf(
            expected: OrderComment::class,
            actual: $ci
        );
    }   
}
