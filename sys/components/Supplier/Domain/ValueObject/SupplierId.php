<?php

declare(strict_types=1);

namespace app\components\Supplier\Domain\ValueObject;

use app\components\Shared\Domain\ValueObject\Identifier\IdUUIDv7;
use InvalidArgumentException;

final class SupplierId extends IdUUIDv7
{
}
