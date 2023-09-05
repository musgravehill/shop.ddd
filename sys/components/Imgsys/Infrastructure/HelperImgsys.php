<?php

declare(strict_types=1);

namespace app\components\Imgsys\Infrastructure;

use app\components\Imgsys\Domain\ValueObject\ImgsysId;
use Yii;
use yii\helpers\Url;

class HelperImgsys
{
    const IMG_DIR = 'imgsys';

    public static function getPathFull(ImgsysId $id)
    {
        return Yii::getAlias('@webroot' . '/' . self::IMG_DIR . '/' . $id->getId() . '.jpg');
    }

    public static function getPublicUrlRelative(ImgsysId $id)
    {
        return '/' . self::IMG_DIR . '/' . $id->getId() . '.jpg';
    }

    public static function getPublicUrlAbsolute(ImgsysId $id)
    {
        return Url::to('/' . self::IMG_DIR . '/' . $id->getId() . '.jpg', true);
    }

    public static function delete(ImgsysId $id): void
    {
        @unlink(self::getPathFull($id));
    }
}
