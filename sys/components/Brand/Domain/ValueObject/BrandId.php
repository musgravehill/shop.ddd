<?php

declare(strict_types=1);

namespace app\components\Brand\Domain\ValueObject;

use app\components\Shared\Domain\ValueObject\Identifier\IdIntPositiveNullable;
use app\components\Shared\Domain\ValueObject\Identifier\IdUUIDv7;
use InvalidArgumentException;

final class BrandId extends IdIntPositiveNullable
{
}
