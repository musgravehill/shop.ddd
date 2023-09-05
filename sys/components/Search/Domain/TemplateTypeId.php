<?php

declare(strict_types=1);

namespace app\components\Search\Domain;

enum TemplateTypeId: int
{
    case Seo = 1;
    case Supplier = 2;
}
