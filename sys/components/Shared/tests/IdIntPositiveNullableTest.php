<?php

declare(strict_types=1);

namespace app\components\Shared\tests;

use app\components\Shared\Domain\ValueObject\Identifier\IdIntPositiveNullable;
use PHPUnit\Framework\TestCase;

final class IdIntPositiveNullableTest extends TestCase
{
    public function testNormal(): void
    {
        $id = IdIntPositiveNullable::fromString('99');
        $this->assertInstanceOf(
            expected: IdIntPositiveNullable::class,
            actual: $id
        );
    }
    public function testZero(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $id = IdIntPositiveNullable::fromString('0');
    }
    public function testNull(): void
    {
        $id = IdIntPositiveNullable::fromString(null);
        $this->assertInstanceOf(
            expected: IdIntPositiveNullable::class,
            actual: $id
        );
    }
    public function testMinus(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $id = IdIntPositiveNullable::fromString('-999');
    }

    public function testEquals(): void
    {
        $id1 = IdIntPositiveNullable::fromString('1');
        $id1_1 = IdIntPositiveNullable::fromString('1');
        $id2 = IdIntPositiveNullable::fromString('2');
        $this->assertTrue($id1->isEqualsTo($id1_1));
        $this->assertFalse($id1->isEqualsTo($id2));
    }

    

    public function testToGet(): void
    {
        $id = IdIntPositiveNullable::fromString('99');
        $this->assertEquals(
            expected: '99',
            actual: $id->getId()
        );

        $id = IdIntPositiveNullable::fromString(null);
        $this->assertEquals(
            expected: null,
            actual: $id->getId()
        );
    }
}
