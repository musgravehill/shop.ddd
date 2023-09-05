<?php

declare(strict_types=1);

namespace app\components\BrandCategory\Infrastructure;

use app\components\Brand\Domain\ValueObject\BrandId;
use app\components\Shared\Domain\ValueObject\CountOnPage;
use app\components\Shared\Domain\ValueObject\PageNumber;
use app\components\BrandCategory\Domain\Contract\BrandCategoryRepositoryInterface;
use app\components\BrandCategory\Domain\Entity\BrandCategory;
use app\components\BrandCategory\Domain\ValueObject\BrandCategoryDsc;
use app\components\BrandCategory\Domain\ValueObject\BrandCategoryId;
use app\components\BrandCategory\Domain\ValueObject\BrandCategoryLogoFn;
use app\components\BrandCategory\Domain\ValueObject\BrandCategoryName;
use app\components\BrandCategory\Domain\ValueObject\SearchOffers;
use app\components\BrandCategory\Domain\ValueObject\SearchPriceFractionalMax;
use app\components\BrandCategory\Domain\ValueObject\SearchPriceFractionalMin;
use app\components\Search\Domain\SearchQuery;
use app\components\Shared\Domain\ValueObject\Ufu;
use app\components\Shared\Domain\ValueObject\ViewIdx;
use Exception;
use LogicException;
use Ramsey\Uuid\Uuid;
use Yii;

class BrandCategoryRepository implements BrandCategoryRepositoryInterface
{
    public function nextId(): BrandCategoryId
    {
        $nextId = null;
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            Yii::$app->db->createCommand()->insert(
                'brand_category',
                [
                    'id' => null,
                    'brandId' => 0,
                    'name' => 'nextId',
                    'dsc' => 'nextId',
                    'searchQuery' => 'nextId',
                    'searchPriceFractionalMin' => 0,
                    'searchPriceFractionalMax' => 0,
                    'searchOffers' => 'nextId',
                    'ufu' => 'nextId',
                    'logoFn' => null,
                    'viewIdx' => 0,
                ]
            )->execute();

            $data = Yii::$app->db->createCommand(' SELECT MAX(id) as max_id FROM brand_category ')->queryOne();

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

        return BrandCategoryId::fromString((string) $nextId);
    }

    public function idsNamesBrands(): array
    {
        $res = [];
        $limit = " LIMIT 99999999 ";
        $items = Yii::$app->db->createCommand("
                    SELECT
                        brand_category.id,
                        brand_category.name, 
                        brand_category.brandId                   
                    FROM  {{brand_category}} brand_category                       
                    $limit 
                   ")
            ->queryAll();

        if (!$items) {
            return $res;
        }
        foreach ($items as $item) {
            $id = (string) $item['id'];
            $res[$id] = [
                'name' => $item['name'],
                'brandId' => $item['brandId'],
            ];
        }
        return $res;
    }

    public function getByBrandId(BrandId $brandId): array
    {
        $res = [];

        $limit = " LIMIT 999 ";
        $items = Yii::$app->db->createCommand("
                        SELECT
                            brand_category.*                 
                        FROM  {{brand_category}} brand_category    
                        WHERE brand_category.brandId = '" . $brandId->getId() . "'                 
                        ORDER BY brand_category.viewIdx DESC  
                        $limit 
                       ")
            ->queryAll();

        if (!$items) {
            return $res;
        }

        foreach ($items as $item) {
            $res[] =  BrandCategory::hydrateExisting(
                id: BrandCategoryId::fromString((string) $item['id']),
                brandId: BrandId::fromString((string) $item['brandId']),
                name: new BrandCategoryName($item['name']),
                dsc: new BrandCategoryDsc($item['dsc']),
                searchQuery: new SearchQuery($item['searchQuery']),
                searchPriceFractionalMin: new SearchPriceFractionalMin($item['searchPriceFractionalMin']),
                searchPriceFractionalMax: new SearchPriceFractionalMax($item['searchPriceFractionalMax']),
                searchOffers: new SearchOffers($item['searchOffers']),
                ufu: Ufu::hydrateExisting($item['ufu']),
                logoFn: BrandCategoryLogoFn::fromString($item['logoFn']),
                viewIdx: new ViewIdx(intval($item['viewIdx'])),
            );
        }
        return $res;
    }

    public function rand(CountOnPage $cop): array
    {
        $res = [];

        $limit = " LIMIT " . $cop->getCop() . ' ';
        $items = Yii::$app->db->createCommand("
                    SELECT
                        brand_category.*                 
                    FROM  {{brand_category}} brand_category                   
                    ORDER BY RAND()  
                    $limit 
                   ")
            ->queryAll();

        if (!$items) {
            return $res;
        }

        foreach ($items as $item) {
            $res[] =  BrandCategory::hydrateExisting(
                id: BrandCategoryId::fromString((string) $item['id']),
                brandId: BrandId::fromString((string) $item['brandId']),
                name: new BrandCategoryName($item['name']),
                dsc: new BrandCategoryDsc($item['dsc']),
                searchQuery: new SearchQuery($item['searchQuery']),
                searchPriceFractionalMin: new SearchPriceFractionalMin($item['searchPriceFractionalMin']),
                searchPriceFractionalMax: new SearchPriceFractionalMax($item['searchPriceFractionalMax']),
                searchOffers: new SearchOffers($item['searchOffers']),
                ufu: Ufu::hydrateExisting($item['ufu']),
                logoFn: BrandCategoryLogoFn::fromString($item['logoFn']),
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
                        brand_category.*                 
                    FROM  {{brand_category}} brand_category                   
                    ORDER BY viewIdx DESC 
                    $limit 
                   ")
            ->queryAll();

        if (!$items) {
            return $res;
        }

        foreach ($items as $item) {
            $res[] =  BrandCategory::hydrateExisting(
                id: BrandCategoryId::fromString((string) $item['id']),
                brandId: BrandId::fromString((string) $item['brandId']),
                name: new BrandCategoryName($item['name']),
                dsc: new BrandCategoryDsc($item['dsc']),
                searchQuery: new SearchQuery($item['searchQuery']),
                searchPriceFractionalMin: new SearchPriceFractionalMin($item['searchPriceFractionalMin']),
                searchPriceFractionalMax: new SearchPriceFractionalMax($item['searchPriceFractionalMax']),
                searchOffers: new SearchOffers($item['searchOffers']),
                ufu: Ufu::hydrateExisting($item['ufu']),
                logoFn: BrandCategoryLogoFn::fromString($item['logoFn']),
                viewIdx: new ViewIdx(intval($item['viewIdx'])),
            );
        }
        return $res;
    }

    public function list(
        PageNumber $page,
        CountOnPage $cop,
        SearchQuery $q,
        BrandId $brandId
    ): array {
        $res = [];

        $offset = (int) ($page->getPageNumber() - 1) * $cop->getCop();
        $limit = " LIMIT $offset, " . $cop->getCop() . ' ';

        $q_cond = ' ';
        if (isset($q->getSearchQuery()[1])) {
            $qs = explode(' ', $q->getSearchQuery());
            foreach ($qs as $q) {
                if (isset($q[1])) {
                    $q_cond .= " AND brand_category.name LIKE '%$q%' ";
                }
            }
        }

        $brandId_cond = ' ';
        if (!is_null($brandId->getId())) {
            $brandId_cond = " AND brand_category.brandId='" . $brandId->getId() . "' ";
        }

        $items = Yii::$app->db->createCommand("
                    SELECT
                        brand_category.*                
                    FROM  {{brand_category}} brand_category       
                    WHERE 
                        1=1 
                        $brandId_cond 
                        $q_cond             
                    ORDER BY brand_category.name ASC   
                    $limit 
                   ")
            ->queryAll();

        if (!$items) {
            return $res;
        }

        foreach ($items as $item) {
            $res[] = BrandCategory::hydrateExisting(
                id: BrandCategoryId::fromString((string) $item['id']),
                brandId: BrandId::fromString((string) $item['brandId']),
                name: new BrandCategoryName($item['name']),
                dsc: new BrandCategoryDsc($item['dsc']),
                searchQuery: new SearchQuery($item['searchQuery']),
                searchPriceFractionalMin: new SearchPriceFractionalMin($item['searchPriceFractionalMin']),
                searchPriceFractionalMax: new SearchPriceFractionalMax($item['searchPriceFractionalMax']),
                searchOffers: new SearchOffers($item['searchOffers']),
                ufu: Ufu::hydrateExisting($item['ufu']),
                logoFn: BrandCategoryLogoFn::fromString($item['logoFn']),
                viewIdx: new ViewIdx(intval($item['viewIdx'])),
            );
        }
        return $res;
    }

    public function getById(BrandCategoryId $id): ?BrandCategory
    {
        $item = Yii::$app->db->createCommand("
        SELECT
            brand_category.*               
        FROM  {{brand_category}} brand_category                   
        WHERE
            brand_category.id='" . $id->getId() . "'
        LIMIT 1                    
        ")
            ->queryOne();
        if (!$item) {
            return null;
        }

        $brandCategory = BrandCategory::hydrateExisting(
            id: BrandCategoryId::fromString((string) $item['id']),
            brandId: BrandId::fromString((string) $item['brandId']),
            name: new BrandCategoryName($item['name']),
            dsc: new BrandCategoryDsc($item['dsc']),
            searchQuery: new SearchQuery($item['searchQuery']),
            searchPriceFractionalMin: new SearchPriceFractionalMin($item['searchPriceFractionalMin']),
            searchPriceFractionalMax: new SearchPriceFractionalMax($item['searchPriceFractionalMax']),
            searchOffers: new SearchOffers($item['searchOffers']),
            ufu: Ufu::hydrateExisting($item['ufu']),
            logoFn: BrandCategoryLogoFn::fromString($item['logoFn']),
            viewIdx: new ViewIdx(intval($item['viewIdx'])),
        );

        return $brandCategory;
    }

    public function getCount(): int
    {
        $res = Yii::$app->db->createCommand("
                    SELECT
                        COUNT(brand_category.id) as count_id                 
                    FROM  {{brand_category}} brand_category                        
                   ")
            ->queryOne();
        return (int) $res['count_id'];
    }

    public function save(BrandCategory $brandCategory): ?BrandCategory
    {
        $res = null;
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            if (is_null($brandCategory->getId()->getId())) {
                $res = $this->new($brandCategory);
            } else {
                $res = $this->update($brandCategory);
            }
            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw new Exception('Save: error!');
        }

        return $res;
    }

    private function new(BrandCategory $brandCategory): ?BrandCategory
    {
        $brandCategoryId = $this->nextId();
        Yii::$app->db->createCommand()->update(
            'brand_category',
            [
                'brandId' => $brandCategory->getBrandId()->getId(),
                'name' => $brandCategory->getName()->getName(),
                'dsc' => $brandCategory->getDsc()->getDsc(),
                'searchQuery' => $brandCategory->getSearchQuery()->getSearchQuery(),
                'searchPriceFractionalMin' => $brandCategory->getSearchPriceFractionalMin()->getSearchPriceFractionalMin(),
                'searchPriceFractionalMax' => $brandCategory->getSearchPriceFractionalMax()->getSearchPriceFractionalMax(),
                'searchOffers' => $brandCategory->getSearchOffers()->getSearchOffers(),
                'ufu' => $brandCategory->getUfu()->getUfu(),
                'logoFn' => $brandCategory->getLogoFn()->getLogoFn(),
                'viewIdx' => 0,
            ],
            " id = '" . $brandCategoryId->getId() . "' "
        )->execute();

        return $this->getById($brandCategoryId);
    }

    private function update(BrandCategory $brandCategory): ?BrandCategory
    {
        $brandCategoryId = $brandCategory->getId();
        Yii::$app->db->createCommand()->update(
            'brand_category',
            [
                'brandId' => $brandCategory->getBrandId()->getId(),
                'name' => $brandCategory->getName()->getName(),
                'dsc' => $brandCategory->getDsc()->getDsc(),
                'searchQuery' => $brandCategory->getSearchQuery()->getSearchQuery(),
                'searchPriceFractionalMin' => $brandCategory->getSearchPriceFractionalMin()->getSearchPriceFractionalMin(),
                'searchPriceFractionalMax' => $brandCategory->getSearchPriceFractionalMax()->getSearchPriceFractionalMax(),
                'searchOffers' => $brandCategory->getSearchOffers()->getSearchOffers(),
                'ufu' => $brandCategory->getUfu()->getUfu(),
                'logoFn' => $brandCategory->getLogoFn()->getLogoFn(),
            ],
            " id = '" . $brandCategoryId->getId() . "' "
        )->execute();

        return $this->getById($brandCategoryId);
    }

    public function incrementViewIdx(BrandCategoryId $id): void
    {
        $idString = $id->getId();
        Yii::$app->db->createCommand("
                    UPDATE                                        
                        {{brand_category}} brand_category                        
                    SET 
                        viewIdx = viewIdx + 1 
                    WHERE id='$idString'   
                    LIMIT 1     
                   ")
            ->execute();
    }
}
