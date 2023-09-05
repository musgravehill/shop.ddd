<?php

declare(strict_types=1);

namespace app\components\Product\Domain\ValueObject;

use app\components\Shared\Domain\ValueObject\Identifier\IdInterface;
use app\components\Shared\Domain\ValueObject\Identifier\IdIntPositiveNullable;

/** @psalm-immutable */
class ProductId extends IdIntPositiveNullable implements IdInterface
{
}
