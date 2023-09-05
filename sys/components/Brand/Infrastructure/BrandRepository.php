<?php

declare(strict_types=1);

namespace app\components\Brand\Infrastructure;

use app\components\Shared\Domain\ValueObject\CountOnPage;
use app\components\Shared\Domain\ValueObject\PageNumber;
use app\components\Brand\Domain\Contract\BrandRepositoryInterface;
use app\components\Brand\Domain\Entity\Brand;
use app\components\Brand\Domain\ValueObject\BrandDsc;
use app\components\Brand\Domain\ValueObject\BrandExternalId;
use app\components\Brand\Domain\ValueObject\BrandId;
use app\components\Brand\Domain\ValueObject\BrandLogoFn;
use app\components\Brand\Domain\ValueObject\BrandName;
use app\components\Brand\Domain\ValueObject\NameCanonical;
use app\components\Shared\Domain\ValueObject\Ufu;
use app\components\Shared\Domain\ValueObject\ViewIdx;
use Exception;
use LogicException;
use Ramsey\Uuid\Uuid;
use Yii;

class BrandRepository implements BrandRepositoryInterface
{
    public function nextId(): BrandId
    {
        $nextId = null;
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            Yii::$app->db->createCommand()->insert(
                'brand',
                [
                    'id' => null,
                    'name' => 'nextId',
                    'dsc' => 'nextId',
                    'externalId' => 0,
                    'ufu' => 'nextId',
                    'logoFn' => null,
                    'nameCanonical' => 'nextId',
                    'viewIdx' => 0,
                ]
            )->execute();

            $data = Yii::$app->db->createCommand(' SELECT MAX(id) as max_id FROM brand ')->queryOne();

            if ($data) {
                $nextId = $data['max_id'];
            }
            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
        }

        if (is_null($nextId)) {
            throw new Exception(' Rule: seq not null. ');
        }

        return BrandId::fromString((string) $nextId);
    }

    public function getIdByName(string $nameRaw): BrandId
    {
        $nameCanonical = NameCanonical::fromRu($nameRaw);
        $item = Yii::$app->db->createCommand("
        SELECT
            brand.id               
        FROM  {{brand}} brand                   
        WHERE
            brand.nameCanonical='" . $nameCanonical->getNamecanonical() . "'  
        LIMIT 1                    
        ")
            ->queryOne();
        if (!$item) {
            return BrandId::fromString(null);
        }
        return BrandId::fromString((string)$item['id']);
    }

    public function idsNames(): array
    {
        $res = [];
        $limit = " LIMIT 99999999 ";
        $items = Yii::$app->db->createCommand("
                    SELECT
                        brand.id,
                        brand.name                  
                    FROM  {{brand}} brand                       
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

    public function rand(CountOnPage $cop): array
    {
        $res = [];

        $limit = " LIMIT " . $cop->getCop() . ' ';
        $items = Yii::$app->db->createCommand("
                    SELECT
                        brand.*                 
                    FROM  {{brand}} brand                   
                    ORDER BY RAND()  
                    $limit 
                   ")
            ->queryAll();

        if (!$items) {
            return $res;
        }

        foreach ($items as $item) {
            $res[] =  Brand::hydrateExisting(
                id: BrandId::fromString((string) $item['id']),
                name: new BrandName($item['name']),
                dsc: new BrandDsc($item['dsc']),
                externalId: BrandExternalId::fromString((string) $item['externalId']),
                ufu: Ufu::hydrateExisting($item['ufu']),
                logoFn: BrandLogoFn::fromString($item['logoFn']),
                nameCanonical: NameCanonical::hydrateExisting((string) $item['nameCanonical']),
                viewIdx: new ViewIdx(intval($item['viewIdx'])),
            );
        }
        return $res;
    }

    public function popular(CountOnPage $cop): array
    {
        $res = [];

        $limit = " LIMIT " . $cop->getCop() . ' ';
        $items = Yii::$app->db->createCommand("
                    SELECT
                        brand.*                  
                    FROM  {{brand}} brand                   
                    ORDER BY viewIdx DESC 
                    $limit 
                   ")
            ->queryAll();

        if (!$items) {
            return $res;
        }

        foreach ($items as $item) {
            $res[] =  Brand::hydrateExisting(
                id: BrandId::fromString((string) $item['id']),
                name: new BrandName($item['name']),
                dsc: new BrandDsc($item['dsc']),
                externalId: BrandExternalId::fromString((string) $item['externalId']),
                ufu: Ufu::hydrateExisting($item['ufu']),
                logoFn: BrandLogoFn::fromString($item['logoFn']),
                nameCanonical: NameCanonical::hydrateExisting((string) $item['nameCanonical']),
                viewIdx: new ViewIdx(intval($item['viewIdx'])),
            );
        }
        return $res;
    }

    public function list(PageNumber $page, CountOnPage $cop): array
    {
        $res = [];

        $offset = (int) ($page->getPageNumber() - 1) * $cop->getCop();
        $limit = " LIMIT $offset, " . $cop->getCop() . ' ';

        $items = Yii::$app->db->createCommand("
                    SELECT
                        brand.*                
                    FROM  {{brand}} brand                   
                    ORDER BY brand.name ASC   
                    $limit 
                   ")
            ->queryAll();

        if (!$items) {
            return $res;
        }

        foreach ($items as $item) {
            $res[] =  Brand::hydrateExisting(
                id: BrandId::fromString((string) $item['id']),
                name: new BrandName($item['name']),
                dsc: new BrandDsc($item['dsc']),
                externalId: BrandExternalId::fromString((string) $item['externalId']),
                ufu: Ufu::hydrateExisting($item['ufu']),
                logoFn: BrandLogoFn::fromString($item['logoFn']),
                nameCanonical: NameCanonical::hydrateExisting((string) $item['nameCanonical']),
                viewIdx: new ViewIdx(intval($item['viewIdx'])),
            );
        }
        return $res;
    }

    public function getById(BrandId $id): ?Brand
    {
        $item = Yii::$app->db->createCommand("
        SELECT
            brand.*               
        FROM  {{brand}} brand                   
        WHERE
            brand.id='" . $id->getId() . "'
        LIMIT 1                    
        ")
            ->queryOne();
        if (!$item) {
            return null;
        }

        $brand = Brand::hydrateExisting(
            id: BrandId::fromString((string) $item['id']),
            name: new BrandName($item['name']),
            dsc: new BrandDsc($item['dsc']),
            externalId: BrandExternalId::fromString((string) $item['externalId']),
            ufu: Ufu::hydrateExisting($item['ufu']),
            logoFn: BrandLogoFn::fromString($item['logoFn']),
            nameCanonical: NameCanonical::hydrateExisting((string) $item['nameCanonical']),
            viewIdx: new ViewIdx(intval($item['viewIdx'])),
        );

        return $brand;
    }

    public function getCount(): int
    {
        $res = Yii::$app->db->createCommand("
                    SELECT
                        COUNT(brand.id) as count_id                 
                    FROM  {{brand}} brand                        
                   ")
            ->queryOne();
        return (int) $res['count_id'];
    }

    public function save(Brand $brand): ?Brand
    {
        $res = null;
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            if (is_null($brand->getId()->getId())) {
                $res = $this->new($brand);
            } else {
                $res = $this->update($brand);
            }
            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw new Exception('Save: error!');
        }

        return $res;
    }

    private function new(Brand $brand): ?Brand
    {
        $brandId = $this->nextId();
        Yii::$app->db->createCommand()->update(
            'brand',
            [
                'name' => $brand->getName()->getName(),
                'dsc' => $brand->getDsc()->getDsc(),
                'externalId' => $brand->getExternalId()->getId(),
                'ufu' => $brand->getUfu()->getUfu(),
                'logoFn' => $brand->getLogoFn()->getLogoFn(),
                'nameCanonical' => $brand->getNameCanonical()->getNamecanonical(),
                'viewIdx' => 0,
            ],
            " id = '" . $brandId->getId() . "' "
        )->execute();

        return $this->getById($brandId);
    }

    private function update(Brand $brand): ?Brand
    {
        $brandId = $brand->getId();
        Yii::$app->db->createCommand()->update(
            'brand',
            [
                'name' => $brand->getName()->getName(),
                'dsc' => $brand->getDsc()->getDsc(),
                'externalId' => $brand->getExternalId()->getId(),
                'ufu' => $brand->getUfu()->getUfu(),
                'logoFn' => $brand->getLogoFn()->getLogoFn(),
                'nameCanonical' => $brand->getNameCanonical()->getNamecanonical(),
            ],
            " id = '" . $brandId->getId() . "' "
        )->execute();

        return $this->getById($brandId);
    }


    public function generateNameCanonicals(): void
    {
        $limit = " LIMIT 99999999 ";
        $items = Yii::$app->db->createCommand("
                    SELECT
                        brand.id,
                        brand.name                  
                    FROM  {{brand}} brand                       
                    $limit 
                   ")
            ->queryAll();

        if (!$items) {
            return;
        }
        foreach ($items as $item) {
            $id = (string) $item['id'];
            $nameRaw = (string) $item['name'];
            $nameCanonical = NameCanonical::fromRu($nameRaw);

            Yii::$app->db->createCommand()->update(
                'brand',
                [
                    'nameCanonical' => $nameCanonical->getNamecanonical(),
                ],
                " id = '" . $id . "' "
            )->execute();
        }
    }

    public function incrementViewIdx(BrandId $id): void
    {
        $idString = $id->getId();
        Yii::$app->db->createCommand("
                    UPDATE                                        
                      {{brand}} brand                        
                    SET 
                        viewIdx = viewIdx + 1 
                    WHERE id='$idString' 
                    LIMIT 1     
                   ")
            ->execute();
    }
}
