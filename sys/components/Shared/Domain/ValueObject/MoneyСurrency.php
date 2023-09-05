<?php

declare(strict_types=1);

namespace app\components\Shared\Domain\ValueObject;

// ISO-4217
enum MoneyСurrency: int
{
    case RUB = 643;
    case USD = 840;
    case EUR = 978;
}
