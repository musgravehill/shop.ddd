<?php

declare(strict_types=1);

namespace app\components\Shared\tests;

use app\components\Shared\Domain\ValueObject\Identifier\IdIntPositiveNullable;
use app\components\Shared\Domain\ValueObject\Identifier\IdUUIDv7;
use PHPUnit\Framework\TestCase;

final class IdUUIDv7Test extends TestCase
{
    public function testNormal(): void
    {
        $id = IdUUIDv7::fromString('00ccebbc-13e0-7000-8b18-6150ad2d0c05');
        $this->assertInstanceOf(
            expected: IdUUIDv7::class,
            actual: $id
        );
    }
    public function testZero(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $id = IdUUIDv7::fromString('0');
    }
    public function testNull(): void
    {
        $id = IdUUIDv7::fromString(null);
        $this->assertInstanceOf(
            expected: IdUUIDv7::class,
            actual: $id
        );
    }
    public function testNotValidUuid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $id = IdUUIDv7::fromString('11-13e0-7000-8b18-11');
    }

    public function testEquals(): void
    {
        $id1 = IdUUIDv7::fromString('00ccebbc-13e0-7000-8b18-6150ad2d0c05');
        $id1_1 = IdUUIDv7::fromString('00ccebbc-13e0-7000-8b18-6150ad2d0c05');
        $id2 = IdUUIDv7::fromString('10ccebbc-13e0-7000-8b18-6150ad2d0c05');
        $this->assertTrue($id1->isEqualsTo($id1_1));
        $this->assertFalse($id1->isEqualsTo($id2));
    }

    public function testEqualsForeign(): void
    {
        $id1 = IdUUIDv7::fromString('00ccebbc-13e0-7000-8b18-6150ad2d0c05');
        $id2 = IdIntPositiveNullable::fromString('1');
        $this->expectException(\InvalidArgumentException::class);
        $id1->isEqualsTo($id2);
    }

    public function testToGet(): void
    {
        $id1 = IdUUIDv7::fromString('00ccebbc-13e0-7000-8b18-6150ad2d0c05');
        $this->assertEquals(
            expected: '00ccebbc-13e0-7000-8b18-6150ad2d0c05',
            actual: $id1->getId()
        );
    }
}
