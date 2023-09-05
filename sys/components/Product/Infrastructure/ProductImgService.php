<?php

declare(strict_types=1);

namespace app\components\Product\Infrastructure;

use app\components\Product\Domain\Contract\ProductImgRepositoryInterface;
use app\components\Product\Domain\ValueObject\ImgExternalUrl;
use app\components\Product\Domain\ValueObject\ProductId;
use app\components\Product\Domain\ValueObject\ProductImgId;
use InvalidArgumentException;
use Yii;
use yii\imagine\Image;

class ProductImgService
{
    const IMG_DIR = 'imgproduct';
    const MAX_FILE_SIZE_MB = 3;
    const W_PX = 800;
    const H_PX = 600;
    const NO_PHOTO_URL_PUBLIC = '/img/nophoto.png';

    private ProductImgRepositoryInterface $productImgRepository;

    public function __construct()
    {
        $this->productImgRepository = new ProductImgRepository;
    }

    public function getImgUrls(ProductId $productId): array
    {
        $res = [];
        $productImgs = $this->productImgRepository->getByProductId(productId: $productId);
        foreach ($productImgs as $productImg) {
            $res[] = (string) self::getPublicUrlRelative($productImg->getId());
        }
        if (!isset($res[0])) {
            $res[] = self::NO_PHOTO_URL_PUBLIC;
        }
        return $res;
    }

    private static function getPathFull(ProductImgId $productImgId)
    {
        if (is_null($productImgId->getId())) {
            throw new InvalidArgumentException(' ProductImgId is NULL ');
        }

        $fileName = (string) $productImgId->getId();
        $folderName = mb_substr($fileName, -1, 1, "UTF8");

        return Yii::getAlias('@webroot' . '/' . self::IMG_DIR . '/' . $folderName . '/' . $fileName . '.jpg');
    }

    private static function getPublicUrlRelative(ProductImgId $productImgId)
    {
        if (is_null($productImgId->getId())) {
            return self::NO_PHOTO_URL_PUBLIC;
        }

        $fileName = (string) $productImgId->getId();
        $folderName = mb_substr($fileName, -1, 1, "UTF8");

        return '/' . self::IMG_DIR . '/' . $folderName . '/' . $fileName . '.jpg';
    }

    public static function delete(ProductImgId $productImgId): void
    {
        if (is_null($productImgId->getId())) {
            return;
        }
        @unlink(self::getPathFull($productImgId));
    }

    public static function download(ImgExternalUrl $externalUrl, ProductImgId $productImgId): bool
    {
        $url = $externalUrl->getExternalUrl();
        $pathFull = self::getPathFull($productImgId);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.1 Safari/537.11');
        $res = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        if (!$res) {
            return false;
        }

        if ($info['content_type'] === 'image/jpeg' || $info['content_type'] === 'image/jpg' || $info['content_type'] === 'image/png' || $info['content_type'] === 'image/gif') {
            $sizeMb = (float) $info['download_content_length'] / (1024 * 1024);
        } else {
            return false;
        }

        if ($sizeMb > self::MAX_FILE_SIZE_MB) {
            return false;
        }

        if ($sizeMb < 0.001) {
            return false;
        }

        try {
            if (file_put_contents($pathFull, $res)) {
                Image::resize($pathFull, self::W_PX, self::H_PX, true, true)->save($pathFull, ['jpeg_quality' => 80]);
            }
        } catch (\Throwable $t) {
            return false;
        }

        return true;
    }
}
