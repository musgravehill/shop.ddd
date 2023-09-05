<?php

declare(strict_types=1);

namespace app\components\BrandCategory\Domain\ValueObject;

use app\components\Shared\Domain\ValueObject\Identifier\IdIntPositiveNullable;
use app\components\Shared\Domain\ValueObject\Identifier\IdUUIDv7;
use InvalidArgumentException;

final class BrandCategoryId extends IdIntPositiveNullable
{
}
