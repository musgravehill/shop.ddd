<?php

declare(strict_types=1);

namespace app\components\Product\Infrastructure;

use app\components\Product\Domain\Contract\ProductImgRepositoryInterface;
use app\components\Product\Domain\Entity\ProductImg;
use app\components\Product\Domain\ValueObject\ImgExternalUrl;
use app\components\Product\Domain\ValueObject\ImgExternalUrlHash;
use app\components\Product\Domain\ValueObject\ProductId;
use app\components\Product\Domain\ValueObject\ProductImgId;
use app\components\Product\Domain\ValueObject\TaskDownloadFailCount;
use DateTimeImmutable;
use Exception;
use LogicException;
use Ramsey\Uuid\Uuid;
use Yii;

class ProductImgRepository implements ProductImgRepositoryInterface
{
    public function nextId(): ProductImgId
    {
        $uuid = Uuid::uuid7()->toString();
        return ProductImgId::fromString($uuid);
    }

    public function getQueueTasks(int $nextHoursInc, int $taskCount): array
    {
        $res = [];
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {

            $items = Yii::$app->db->createCommand(" 
            SELECT
                pi.*              
            FROM  {{product_img}} pi                      
            WHERE 
                pi.taskDownloadAt <= '" . time() . "'   
            ORDER BY pi.taskDownloadAt ASC      
            LIMIT $taskCount                
            ")->queryAll();

            if (!$items) {
                throw new Exception('No tasks.');
            }

            foreach ($items as $item) {
                $productImg = ProductImg::hydrateExisting(
                    id: ProductImgId::fromString((string) $item['id']),
                    productId: ProductId::fromString((string) $item['productId']),                   
                    externalUrl: new ImgExternalUrl((string) $item['externalUrl']),
                    externalUrlHash: ImgExternalUrlHash::fromString((string) $item['externalUrlHash']),
                    taskDownloadAt: (new DateTimeImmutable())->setTimestamp($item['taskDownloadAt']),
                    taskDownloadFailCount: new TaskDownloadFailCount((int) $item['taskDownloadFailCount']),
                );
                $res[] = $productImg;

                Yii::$app->db->createCommand()->update(
                    'product_img',
                    [
                        'taskDownloadAt' => time() + 3600 * $nextHoursInc,
                    ],
                    " id = '" . $productImg->getId()->getId() . "' "
                )->execute();
            }

            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
        }

        return $res;
    }

    public function getByProductId(ProductId $productId): array
    {
        $res = [];

        $limit = " LIMIT 99 ";

        $items = Yii::$app->db->createCommand("
           SELECT
            pi.*               
            FROM  {{product_img}} pi                    
            WHERE       
                pi.productId = '" . $productId->getId() . "' 
            $limit                 
        ")->queryAll();

        if (!$items) {
            return $res;
        }

        foreach ($items as $item) {
            $productImg = ProductImg::hydrateExisting(
                id: ProductImgId::fromString((string) $item['id']),
                productId: ProductId::fromString((string) $item['productId']),                
                externalUrl: new ImgExternalUrl((string) $item['externalUrl']),
                externalUrlHash: ImgExternalUrlHash::fromString((string) $item['externalUrlHash']),
                taskDownloadAt: (new DateTimeImmutable())->setTimestamp($item['taskDownloadAt']),
                taskDownloadFailCount: new TaskDownloadFailCount((int) $item['taskDownloadFailCount']),
            );
            $res[] = $productImg;
        }

        return $res;
    }

    public function getByExternalData(ProductId $productId, ImgExternalUrlHash $externalUrlHash): ?ProductImg
    {
        $item = Yii::$app->db->createCommand("
        SELECT
            pi.*               
        FROM  {{product_img}} pi                    
        WHERE
            pi.productId = '" . $productId->getId() . "' 
            AND pi.externalUrlHash = '" . $externalUrlHash->getExternalUrlHash() . "'
        LIMIT 1                    
        ")
            ->queryOne();
        if (!$item) {
            return null;
        }

        $productImg = ProductImg::hydrateExisting(
            id: ProductImgId::fromString((string) $item['id']),
            productId: ProductId::fromString((string) $item['productId']),            
            externalUrl: new ImgExternalUrl((string) $item['externalUrl']),
            externalUrlHash: ImgExternalUrlHash::fromString((string) $item['externalUrlHash']),
            taskDownloadAt: (new DateTimeImmutable())->setTimestamp($item['taskDownloadAt']),
            taskDownloadFailCount: new TaskDownloadFailCount((int) $item['taskDownloadFailCount']),
        );

        return $productImg;
    }

    public function getById(ProductImgId $id): ?ProductImg
    {
        $item = Yii::$app->db->createCommand("
        SELECT
            pi.*               
        FROM  {{product_img}} pi                     
        WHERE
            pi.id='" . $id->getId() . "' 
        LIMIT 1                    
        ")
            ->queryOne();
        if (!$item) {
            return null;
        }

        $productImg = ProductImg::hydrateExisting(
            id: ProductImgId::fromString((string) $item['id']),
            productId: ProductId::fromString((string) $item['productId']),            
            externalUrl: new ImgExternalUrl((string) $item['externalUrl']),
            externalUrlHash: ImgExternalUrlHash::fromString((string) $item['externalUrlHash']),
            taskDownloadAt: (new DateTimeImmutable())->setTimestamp($item['taskDownloadAt']),
            taskDownloadFailCount: new TaskDownloadFailCount((int) $item['taskDownloadFailCount']),
        );

        return $productImg;
    }

    public function save(ProductImg $productImg): ?ProductImg
    {
        $res = null;
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            if (is_null($productImg->getId()->getId())) {
                $res = $this->new($productImg);
            } else {
                $res = $this->update($productImg);
            }
            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw new Exception('Save: error!');
        }

        return $res;
    }

    private function new(ProductImg $productImg): ?ProductImg
    {
        $productImgId = $this->nextId();
        Yii::$app->db->createCommand()->insert(
            'product_img',
            [
                'id' => $productImgId->getId(),
                'productId' => $productImg->getProductId()->getId(),                
                'externalUrl' => $productImg->getExternalUrl()->getExternalUrl(),
                'externalUrlHash' => $productImg->getExternalUrlHash()->getExternalUrlHash(),
                'taskDownloadAt' => $productImg->getTaskDownloadAt()->getTimestamp(),
                'taskDownloadFailCount' => $productImg->getTaskDownloadFailCount()->getFailCount(),
            ]
        )->execute();

        return $this->getById($productImgId);
    }

    private function update(ProductImg $productImg): ?ProductImg
    {
        $productImgId = $productImg->getId();
        Yii::$app->db->createCommand()->update(
            'product_img',
            [
                'productId' => $productImg->getProductId()->getId(),                
                'externalUrl' => $productImg->getExternalUrl()->getExternalUrl(),
                'externalUrlHash' => $productImg->getExternalUrlHash()->getExternalUrlHash(),
                'taskDownloadAt' => $productImg->getTaskDownloadAt()->getTimestamp(),
                'taskDownloadFailCount' => $productImg->getTaskDownloadFailCount()->getFailCount(),
            ],
            " id = '" . $productImgId->getId() . "' "
        )->execute();

        return $this->getById($productImgId);
    }
}
