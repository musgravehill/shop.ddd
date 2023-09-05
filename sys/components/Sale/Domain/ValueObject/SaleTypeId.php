<?php

declare(strict_types=1);

namespace app\components\Sale\Domain\ValueObject;

enum SaleTypeId: int
{
    case PersonalBrandCategory = 1;
    case Promocode = 2;
    case TotalCostLevelUp = 3; //order over 50000 has "-1000"
}
