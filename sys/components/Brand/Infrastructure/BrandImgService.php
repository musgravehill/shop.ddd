<?php

declare(strict_types=1);

namespace app\components\Brand\Infrastructure;

use app\components\Brand\Domain\ValueObject\BrandLogoFn;
use InvalidArgumentException;
use Yii;

class BrandImgService
{
    const IMG_DIR = 'imgbrand';

    public static function getPathFull(BrandLogoFn $logoFn)
    {
        if (is_null($logoFn->getLogoFn())) {
            throw new InvalidArgumentException(' BrandLogoFn is NULL ');
        }
        return Yii::getAlias('@webroot' . '/' . self::IMG_DIR . '/' . $logoFn->getLogoFn() . '.jpg');
    }

    public static function getPublicUrlRelative(BrandLogoFn $logoFn)
    {
        if (is_null($logoFn->getLogoFn())) {
            return '/nophoto.png';
        }
        return '/' . self::IMG_DIR . '/' . $logoFn->getLogoFn() . '.jpg';
    }

    public static function delete(BrandLogoFn $logoFn): void
    {
        if (is_null($logoFn->getLogoFn())) {
            return;
        }
        @unlink(self::getPathFull($logoFn));
    }
}
