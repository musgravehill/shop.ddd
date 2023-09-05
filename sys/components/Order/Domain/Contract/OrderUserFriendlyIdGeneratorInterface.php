<?php

declare(strict_types=1);

namespace app\components\Order\Domain\Contract;

use app\components\Order\Domain\ValueObject\OrderUserFriendlyId;

interface OrderUserFriendlyIdGeneratorInterface
{
    public function nextId(): OrderUserFriendlyId;
}
