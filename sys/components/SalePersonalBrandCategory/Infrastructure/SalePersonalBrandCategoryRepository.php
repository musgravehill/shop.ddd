<?php

declare(strict_types=1);

namespace app\components\SalePersonalBrandCategory\Infrastructure;

use app\components\Brand\Domain\ValueObject\BrandId;
use app\components\BrandCategory\Domain\ValueObject\BrandCategoryId;
use app\components\SalePersonalBrandCategory\Domain\Contract\SalePersonalBrandCategoryRepositoryInterface;
use app\components\SalePersonalBrandCategory\Domain\Entity\SalePersonalBrandCategory;
use app\components\SalePersonalBrandCategory\Domain\ValueObject\SalePercent;
use app\components\SalePersonalBrandCategory\Domain\ValueObject\SalePersonalBrandCategoryId;
use app\components\Shared\Domain\ValueObject\CountOnPage;
use app\components\Shared\Domain\ValueObject\PageNumber;
use app\components\User\Domain\ValueObject\UserId;
use Exception;
use LogicException;
use InvalidArgumentException;
use Yii;
use yii\db\Query;

class SalePersonalBrandCategoryRepository implements SalePersonalBrandCategoryRepositoryInterface
{
    public function nextId(): SalePersonalBrandCategoryId 
    {
        $nextId = null;
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            Yii::$app->db->createCommand()->insert(
                'sale_personal_brand_category',
                [
                    'id' => null,
                    'userId' => 'nextId',
                    'brandId' => 'nextId',
                    'brandCategoryId' => 0,
                    'salePercent' => 0,
                ]
            )->execute();

            $data = Yii::$app->db->createCommand(' SELECT MAX(id) as max_id FROM sale_personal_brand_category ')->queryOne();

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

        return SalePersonalBrandCategoryId::fromString((string) $nextId);
    }

    public function list(PageNumber $page, CountOnPage $cop, UserId $userId, BrandId $brandId): array
    {
        $res = [];

        $offset = (int) ($page->getPageNumber() - 1) * $cop->getCop();
        $limit = " LIMIT $offset, " . $cop->getCop() . ' ';

        $userId_cond = ' ';
        if (!is_null($userId->getId())) {
            $userId_cond = " AND sale_personal_brand_category.userId='" . $userId->getId() . "' ";
        }

        $brandId_cond = ' ';
        if (!is_null($brandId->getId())) {
            $brandId_cond = " AND sale_personal_brand_category.brandId='" . $brandId->getId() . "' ";
        }

        $items = Yii::$app->db->createCommand("
                    SELECT
                        sale_personal_brand_category.* ,
                        user.username as customerUsername,
                        user.email as customerEmail,          
                        user.phone as customerPhone,                          
                        brand.ufu as brandUfu,
                        brand.name as brandName,
                        brand_category.ufu as brandCategoryUfu,
                        brand_category.name as brandCategoryName 
                    FROM  {{sale_personal_brand_category}}  
                    LEFT JOIN user ON user.id = sale_personal_brand_category.userId 
                    LEFT JOIN brand ON brand.id = sale_personal_brand_category.brandId 
                    LEFT JOIN brand_category ON brand_category.id = sale_personal_brand_category.brandCategoryId                 

                    WHERE 
                        1=1  
                        $brandId_cond    
                        $userId_cond                     
                    $limit 
                   ")
            ->queryAll();

        if (!$items) {
            return $res;
        }

        foreach ($items as $item) {
            $salePersonalBrandCategory = SalePersonalBrandCategory::hydrateExisting(
                salePersonalBrandCategoryId: SalePersonalBrandCategoryId::fromString((string) $item['id']),
                userId: UserId::fromString((string) $item['userId']),
                brandId: BrandId::fromString((string) $item['brandId']),
                brandCategoryId: BrandCategoryId::fromString((string) $item['brandCategoryId']),
                salePercent: new SalePercent(intval($item['salePercent'])),
            );
            $res[] = [
                'salePersonalBrandCategory' => $salePersonalBrandCategory,
                'customerUsername' => $item['customerUsername'],
                'customerEmail' => $item['customerEmail'],
                'customerPhone' => $item['customerPhone'],
                'brandUfu' => $item['brandUfu'],
                'brandName' => $item['brandName'],
                'brandCategoryUfu' => $item['brandCategoryUfu'],
                'brandCategoryName' => $item['brandCategoryName'],
            ];
        }
        return $res;
    }

    public function getPercent(UserId $userId, BrandId $brandId, BrandCategoryId $brandCategoryId): int
    {
        if (is_null($userId->getId()) || is_null($brandId->getId()) || is_null($brandCategoryId->getId())) {
            return 0;
        }

        $row = (new Query())
            ->select('salePercent')
            ->from('{{%sale_personal_brand_category}}')
            ->where([
                'userId' => $userId->getId(),
                'brandId' => $brandId->getId(),
                'brandCategoryId' => $brandCategoryId->getId(),
            ])
            ->one();

        if (!$row) {
            return 0;
        }
        return intval($row['salePercent']);
    }

    public function save(SalePersonalBrandCategory $salePersonalBrandCategory): ?SalePersonalBrandCategory
    {
        $res = null;
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            if (is_null($salePersonalBrandCategory->getSalePersonalBrandCategoryId()->getId())) {
                $res = $this->new($salePersonalBrandCategory);
            } else {
                $res = $this->update($salePersonalBrandCategory);
            }
            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();            
            throw new Exception('Save: error!');
        }

        return $res;
    }

    private function new(SalePersonalBrandCategory $salePersonalBrandCategory): ?SalePersonalBrandCategory
    {
        $salePersonalBrandCategoryId = $this->nextId();
        Yii::$app->db->createCommand()->update(
            'sale_personal_brand_category',
            [
                'userId' => $salePersonalBrandCategory->getUserId()->getId(),
                'brandId' =>  $salePersonalBrandCategory->getBrandId()->getId(),
                'brandCategoryId' => $salePersonalBrandCategory->getBrandCategoryId()->getId(),
                'salePercent' => $salePersonalBrandCategory->getSalePercent()->getSalePercent(),
            ],
            " id = '" . $salePersonalBrandCategoryId->getId() . "' "
        )->execute();

        return $this->getById($salePersonalBrandCategoryId);
    }

    private function update(SalePersonalBrandCategory $salePersonalBrandCategory): ?SalePersonalBrandCategory
    {
        $salePersonalBrandCategoryId = $salePersonalBrandCategory->getSalePersonalBrandCategoryId();
        Yii::$app->db->createCommand()->update(
            'sale_personal_brand_category',
            [
                'userId' => $salePersonalBrandCategory->getUserId()->getId(),
                'brandId' =>  $salePersonalBrandCategory->getBrandId()->getId(),
                'brandCategoryId' => $salePersonalBrandCategory->getBrandCategoryId()->getId(),
                'salePercent' => $salePersonalBrandCategory->getSalePercent()->getSalePercent(),
            ],
            " id = '" . $salePersonalBrandCategoryId->getId() . "' "
        )->execute();

        return $this->getById($salePersonalBrandCategoryId);
    }

    public function getById(SalePersonalBrandCategoryId $id): ?SalePersonalBrandCategory
    {
        $item = Yii::$app->db->createCommand("
        SELECT
            sale_personal_brand_category.*               
        FROM  {{sale_personal_brand_category}} sale_personal_brand_category                   
        WHERE
            sale_personal_brand_category.id='" . $id->getId() . "'
        LIMIT 1                    
        ")
            ->queryOne();
        if (!$item) {
            return null;
        }

        $salePersonalBrandCategory = SalePersonalBrandCategory::hydrateExisting(
            salePersonalBrandCategoryId: SalePersonalBrandCategoryId::fromString((string) $item['id']),
            userId: UserId::fromString((string) $item['userId']),
            brandId: BrandId::fromString((string) $item['brandId']),
            brandCategoryId: BrandCategoryId::fromString((string) $item['brandCategoryId']),
            salePercent: new SalePercent(intval($item['salePercent'])),
        );

        return $salePersonalBrandCategory;
    }
}
