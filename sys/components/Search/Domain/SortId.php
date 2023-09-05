<?php

declare(strict_types=1);

namespace app\components\Search\Domain;

enum SortId: int
{
    case ProductRelevantDesc = 1;
    case ProductPriceAsc = 2;
    case ProductPriceDesc = 3;
}
