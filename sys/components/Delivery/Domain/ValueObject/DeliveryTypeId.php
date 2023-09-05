<?php

declare(strict_types=1);

namespace app\components\Delivery\Domain\ValueObject;

enum DeliveryTypeId: int
{
    case SelfPickup = 1;
    case CDEK = 2;
    case DelovieLinii = 3;
}
