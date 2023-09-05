<?php

declare(strict_types=1);

namespace app\components\SalePersonalBrandCategory\Domain\ValueObject;

use app\components\Shared\Domain\ValueObject\Identifier\IdInterface;
use app\components\Shared\Domain\ValueObject\Identifier\IdIntPositiveNullable;

/** @psalm-immutable */
class SalePersonalBrandCategoryId extends IdIntPositiveNullable implements IdInterface
{
}
