<?php

declare(strict_types=1);

namespace app\components\BrandCategory\Infrastructure;

use app\components\BrandCategory\Domain\ValueObject\BrandCategoryLogoFn;
use InvalidArgumentException;
use Yii;

class BrandCategoryImgService
{
    const IMG_DIR = 'imgbrandcategory';

    public static function getPathFull(BrandCategoryLogoFn $logoFn)
    {
        if (is_null($logoFn->getLogoFn())) {
            throw new InvalidArgumentException(' BrandCategoryLogoFn is NULL ');
        }
        return Yii::getAlias('@webroot' . '/' . self::IMG_DIR . '/' . $logoFn->getLogoFn() . '.jpg');
    }

    public static function getPublicUrlRelative(BrandCategoryLogoFn $logoFn)
    {
        if (is_null($logoFn->getLogoFn())) {
            return '/nophoto.png';
        }
        return '/' . self::IMG_DIR . '/' . $logoFn->getLogoFn() . '.jpg';
    }

    public static function delete(BrandCategoryLogoFn $logoFn): void
    {
        if (is_null($logoFn->getLogoFn())) {
            return;
        }
        @unlink(self::getPathFull($logoFn));
    }
}
