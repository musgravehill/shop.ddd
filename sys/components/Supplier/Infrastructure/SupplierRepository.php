<?php

declare(strict_types=1);

namespace app\components\Supplier\Infrastructure;

use app\components\HelperY;
use app\components\Shared\Domain\ValueObject\CountOnPage;
use app\components\Shared\Domain\ValueObject\PageNumber;
use app\components\Supplier\Domain\Contract\SupplierRepositoryInterface;
use app\components\Supplier\Domain\Entity\Supplier;
use app\components\Supplier\Domain\ValueObject\CityName;
use app\components\Supplier\Domain\ValueObject\ImgUrl;
use app\components\Supplier\Domain\ValueObject\SupplierDsc;
use app\components\Supplier\Domain\ValueObject\SupplierId;
use app\components\Supplier\Domain\ValueObject\SupplierName;
use DateTimeImmutable;
use Exception;
use LogicException;
use Ramsey\Uuid\Uuid;
use Yii;

class SupplierRepository implements SupplierRepositoryInterface
{
    public function nextId(): SupplierId
    {
        $uuid = Uuid::uuid7()->toString();
        return SupplierId::fromString($uuid);
    }

    public function getQueueSupplierId(int $nextHoursInc): ?SupplierId
    {
        $res = null;
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {

            $item = Yii::$app->db->createCommand(" 
            SELECT 
                s.id                
            FROM  {{supplier}} s                    
            WHERE
                s.taskDownloadAt <= '" . time() . "' 
            ORDER BY s.taskDownloadAt ASC     
            LIMIT 1                    
            ")->queryOne();

            if (!$item) {
                throw new Exception('No task.');
            }

            $supplierId = SupplierId::fromString($item['id']);

            Yii::$app->db->createCommand()->update(
                'supplier',
                [
                    'taskDownloadAt' => time() + 3600 * $nextHoursInc,
                ],
                " id = '" . $supplierId->getId() . "' "
            )->execute();

            $res = $supplierId;

            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
        }

        return $res;
    }

    public function rand(CountOnPage $cop): array
    {
        $res = [];

        $cond_notSeo = ' s.id <> "' . HelperY::params('seoSupplierId') . '" ';

        $limit = " LIMIT " . $cop->getCop() . ' ';
        $ss = Yii::$app->db->createCommand("
                    SELECT
                        s.*                  
                    FROM  {{supplier}} s  
                    WHERE 
                        $cond_notSeo                  
                    ORDER BY RAND()  
                    $limit 
                   ")
            ->queryAll();

        if (!$ss) {
            return $res;
        }

        foreach ($ss as $s) {
            $res[] =  Supplier::hydrateExisting(
                id: SupplierId::fromString($s['id']),
                name: new SupplierName($s['name']),
                dsc: new SupplierDsc($s['dsc']),
                imgUrl: new ImgUrl($s['imgUrl']),
                taskDownloadAt: (new DateTimeImmutable())->setTimestamp($s['taskDownloadAt']),
                cityName: new CityName($s['cityName']),
            );
        }
        return $res;
    }

    public function list(PageNumber $page, CountOnPage $cop, bool $withSeo = false): array
    {
        $res = [];

        $cond_withSeo = ' AND s.id <> "' . HelperY::params('seoSupplierId') . '" ';
        if ($withSeo) {
            $cond_withSeo = ''; 
        }

        $offset = (int) ($page->getPageNumber() - 1) * $cop->getCop();
        $limit = " LIMIT $offset, " . $cop->getCop() . ' ';

        $ss = Yii::$app->db->createCommand("
                    SELECT
                        s.*                
                    FROM  {{supplier}} s        
                    WHERE 
                        1=1 
                        $cond_withSeo               
                    ORDER BY s.id DESC  
                    $limit 
                   ")
            ->queryAll();

        if (!$ss) {
            return $res;
        }

        foreach ($ss as $s) {
            $res[] =  Supplier::hydrateExisting(
                id: SupplierId::fromString($s['id']),
                name: new SupplierName($s['name']),
                dsc: new SupplierDsc($s['dsc']),
                imgUrl: new ImgUrl($s['imgUrl']),
                taskDownloadAt: (new DateTimeImmutable())->setTimestamp($s['taskDownloadAt']),
                cityName: new CityName($s['cityName']),
            );
        }
        return $res;
    }

    public function getById(SupplierId $id): ?Supplier
    {
        $s = Yii::$app->db->createCommand("
        SELECT
            s.*               
        FROM  {{supplier}} s                   
        WHERE
            s.id='" . $id->getId() . "'
        LIMIT 1                    
        ")
            ->queryOne();
        if (!$s) {
            return null;
        }

        $supplier = Supplier::hydrateExisting(
            id: SupplierId::fromString($s['id']),
            name: new SupplierName($s['name']),
            dsc: new SupplierDsc($s['dsc']),
            imgUrl: new ImgUrl($s['imgUrl']),
            taskDownloadAt: (new DateTimeImmutable())->setTimestamp($s['taskDownloadAt']),
            cityName: new CityName($s['cityName']),
        );

        return $supplier;
    }

    public function getCount(): int
    {
        $res = Yii::$app->db->createCommand("
                    SELECT
                        COUNT(s.id) as count_id                 
                    FROM  {{supplier}} s                        
                   ")
            ->queryOne();
        return (int) $res['count_id'];
    }

    public function save(Supplier $supplier): ?Supplier
    {
        $res = null;
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            if (is_null($supplier->getId()->getId())) {
                $res = $this->new($supplier);
            } else {
                $res = $this->update($supplier);
            }
            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw new Exception('Save: error!');
        }

        return $res;
    }

    private function new(Supplier $supplier): ?Supplier
    {
        $supplierId = $this->nextId();
        Yii::$app->db->createCommand()->insert(
            'supplier',
            [
                'id' => $supplierId->getId(),
                'name' => $supplier->getName()->getName(),
                'dsc' => $supplier->getDsc()->getDsc(),
                'imgUrl' => $supplier->getImgUrl()->getImgUrl(),
                'taskDownloadAt' => $supplier->getTaskDownloadAt()->getTimestamp(),
                'cityName' => $supplier->getCityName()->getCityName(),
            ]
        )->execute();

        return $this->getById($supplierId);
    }

    private function update(Supplier $supplier): ?Supplier
    {
        $supplierId = $supplier->getId();
        Yii::$app->db->createCommand()->update(
            'supplier',
            [
                'name' => $supplier->getName()->getName(),
                'dsc' => $supplier->getDsc()->getDsc(),
                'imgUrl' => $supplier->getImgUrl()->getImgUrl(),
                'taskDownloadAt' => $supplier->getTaskDownloadAt()->getTimestamp(),
                'cityName' => $supplier->getCityName()->getCityName(),
            ],
            " id = '" . $supplierId->getId() . "' "
        )->execute();

        return $this->getById($supplierId);
    }

    public function idsNames(){
        $res = [];
        $limit = " LIMIT 99999999 ";
        $items = Yii::$app->db->createCommand("
                    SELECT
                        s.id,
                        s.name                  
                    FROM  {{supplier}} s                       
                    $limit 
                   ")
            ->queryAll();

        if (!$items) {
            return $res;
        }
        foreach ($items as $item) {
            $id = (string) $item['id'];
            $res[$id] = (string) $item['name'];
        }
        return $res;
    }
}
