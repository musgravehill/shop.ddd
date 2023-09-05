<?php

declare(strict_types=1);

namespace app\components\UserCompany\Domain\ValueObject;

use app\components\Shared\Domain\ValueObject\Identifier\IdInterface;
use app\components\Shared\Domain\ValueObject\Identifier\IdUUIDv7;

/** @psalm-immutable */
class UserCompanyId extends IdUUIDv7 implements IdInterface
{
}
