<?php

declare(strict_types=1);

namespace app\components\Order\Domain\ValueObject;

use app\components\Shared\Domain\ValueObject\Identifier\IdInterface;
use app\components\Shared\Domain\ValueObject\Identifier\IdUUIDv7;

/** @psalm-immutable */
class OrderItemId extends IdUUIDv7 implements IdInterface
{
}
