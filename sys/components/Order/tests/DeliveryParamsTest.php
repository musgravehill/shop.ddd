<?php

declare(strict_types=1); 

namespace app\components\Order\tests;  

use app\components\Delivery\Domain\ValueObject\DeliveryTypeId;
use app\components\Order\Domain\ValueObject\DeliveryParams;
use PHPUnit\Framework\TestCase;

final class DeliveryParamsTest extends TestCase
{   
    public function testOutOfRange(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $ci = new DeliveryParams(
            deliveryTypeId: DeliveryTypeId::CDEK,
            cityName: '1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890' .
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
        $ci = new DeliveryParams(
            deliveryTypeId: DeliveryTypeId::CDEK,
            cityName: '<script></script>'
        );
    }

    public function testForbiddenSymbolPhp(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $ci = new DeliveryParams(
            deliveryTypeId: DeliveryTypeId::CDEK,
            cityName: '<?php echo 1; ?>'
        );
    }

    public function testForbiddenSymbolQuotes1(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $ci = new DeliveryParams(
            deliveryTypeId: DeliveryTypeId::CDEK,
            cityName: '`'
        );
    }
    public function testForbiddenSymbolQuotes2(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $ci = new DeliveryParams(
            deliveryTypeId: DeliveryTypeId::CDEK,
            cityName: '"'
        );
    }
    public function testForbiddenSymbolQuotes3(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $ci = new DeliveryParams(
            deliveryTypeId: DeliveryTypeId::CDEK,
            cityName: "'"
        );
    }

    public function testNormal(): void
    {
        $ci = new DeliveryParams(
            deliveryTypeId: DeliveryTypeId::CDEK,
            cityName: '10000 г. Москва Район Кремля'
        );
        $this->assertInstanceOf(
            expected: DeliveryParams::class,
            actual: $ci
        );
    }
}
