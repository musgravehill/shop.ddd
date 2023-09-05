<?php

namespace app\components;

use Yii;

class HelperCache
{
    const BRAND_SHOWCASE = 'BRAND_SHOWCASE';
    const BRANDCATEGORY_SHOWCASE = 'BRANDCATEGORY_SHOWCASE';
    const PRODUCT_SHOWCASE = 'PRODUCT_SHOWCASE';
    const SUPPLIER_SHOWCASE = 'SUPPLIER_SHOWCASE';
    const PAGE_SHOWCASE = 'PAGE_SHOWCASE';
    const PRODUCT_TOTALCOUNT = 'PRODUCT_TOTALCOUNT';

    const BRAND_BRANDCATEGORYS = 'BRAND_BRANDCATEGORYS'; //with params

    public static function getCacheKey($key, array $params = []): string
    {
        ksort($params);
        return sha1(serialize([$key, $params]));
    }
}
