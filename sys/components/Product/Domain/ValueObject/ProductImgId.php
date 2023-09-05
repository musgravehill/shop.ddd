<?php

declare(strict_types=1);

namespace app\components\Product\Domain\ValueObject;

use app\components\Shared\Domain\ValueObject\Identifier\IdInterface;
use app\components\Shared\Domain\ValueObject\Identifier\IdUUIDv7;

/** @psalm-immutable */
class ProductImgId extends IdUUIDv7 implements IdInterface
{
}
