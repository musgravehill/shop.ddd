<?php

declare(strict_types=1);

namespace app\components\Search\Infrastructure;

use Yii;
//
use app\components\Search\Domain\Contract\SearchProductInterface;
use app\components\Search\Domain\SortId;
use app\components\Search\Domain\SearchProductDto;
use app\components\Search\Domain\SearchQuery;
use app\components\Brand\Domain\ValueObject\BrandId;
use app\components\BrandCategory\Domain\ValueObject\BrandCategoryId;
use app\components\HelperY;
use app\components\Shared\Domain\ValueObject\CountOnPage;
use app\components\Shared\Domain\ValueObject\Money;
use app\components\Shared\Domain\ValueObject\PageNumber;
use app\components\Shared\Domain\ValueObject\QuantityZeroPositive;
use app\components\Supplier\Domain\ValueObject\SupplierId;
use DateTimeImmutable;

class SearchProductSphinx implements SearchProductInterface
{
    //see also app\components\Product\Domain\ValueObject\ProductName  
    private function generateQueryString(SearchQuery $searchQuery)
    {
        $q = Yii::$app->sphinx->escapeMatchValue($searchQuery->getSearchQuery());
        $q = str_replace('\/', '\\\\/', $q);
        $words = explode(' ', $q);
        $wordsAdditional = [];
        foreach ($words as $word) {
            if (mb_strpos($word, '-', 0, "utf-8") >= 1) {
                $word = str_replace('\-', '-', $word);
                $wordsAdditional = array_merge($wordsAdditional, explode('-', $word));
            }
            if (mb_strpos($word, '—', 0, "utf-8") >= 1) {
                $word = str_replace('\—', '—', $word);
                $wordsAdditional = array_merge($wordsAdditional, explode('—', $word));
            }
        }

        $words = array_merge($words, $wordsAdditional);

        foreach ($words as $k => $word) {
            if (mb_strlen($word, "utf-8") <= 2) {
                unset($words[$k]);
            }
        }

        /* $filePath = Yii::getAlias('@webroot/log/sphinx.html');
          $handle = fopen($filePath, 'a+');
          fwrite($handle, PHP_EOL . print_r($q, 1) . PHP_EOL . PHP_EOL);
          fclose($handle); */

        $q = implode(" | ", $words);

        return $q;
    }

    /** @return SearchProductDto[] */
    public function getProducts(
        PageNumber $page,
        CountOnPage $countOnPage,
        SearchQuery $searchQuery,
        Money $priceMin,
        Money $priceMax,
        SupplierId $supplierId,
        BrandId $brandId,
        BrandCategoryId $brandCategoryId,
        QuantityZeroPositive $quantityAvailableMin,
        SortId $sortId,
        DateTimeImmutable $obsoleteСonstraintAt
    ): array {
        $offset = intval(abs($page->getPageNumber() - 1) * $countOnPage->getCop());
        $limit = " $offset, " . $countOnPage->getCop() . ' ';

        $condSearch = " MATCH('') ";
        $words = $this->generateQueryString($searchQuery);
        if (isset($words[2])) {
            $condSearch = " MATCH('$words')  ";
        }

        $condPriceMin = ' '; // 1cent
        if ($priceMin->getFractionalCount() > 0) {
            $condPriceMin = ' AND  priceSelling >= ' . $priceMin->getFractionalCount() . ' ';
        }

        $condPriceMax = ' ';
        if ($priceMax->getFractionalCount() > 0) {
            $condPriceMax = ' AND  priceSelling <= ' . $priceMax->getFractionalCount() . ' ';
        }

        $condQuantityAvailableMin = ' ';
        if ($quantityAvailableMin->getQuantity() > 0) {
            $condQuantityAvailableMin = ' AND quantityAvailable >= ' . $quantityAvailableMin->getQuantity() . ' ';
        }

        $condBrand = ' ';
        if (!is_null($brandId->getId())) {
            $condBrand = " AND brandId ='" . $brandId->getId() . "' ";
        }

        $condBrandCategory = ' ';
        if (!is_null($brandCategoryId->getId())) {
            $condBrandCategory = " AND brandCategoryId ='" . $brandCategoryId->getId() . "' ";
        }

        $condSupplier = ' ';
        if (!is_null($supplierId->getId())) {
            $condSupplier = " AND supplierId ='" . $supplierId->getId() . "' ";
        } else {
            $condSupplier = " AND supplierId <>'" . HelperY::params('seoSupplierId') . "' ";
        }

        $order = ' relevance DESC ';
        switch ($sortId) {
            case SortId::ProductRelevantDesc:
                $order = ' relevance DESC ';
                break;
            case SortId::ProductPriceAsc:
                $order = ' priceSelling ASC ';
                break;
            case SortId::ProductPriceDesc:
                $order = ' priceSelling DESC ';
                break;
        }

        $condObsolete = ' AND updatedAt >= ' . $obsoleteСonstraintAt->getTimestamp() . ' ';

        $sql = "SELECT
                id, 
                WEIGHT() as relevance                  
        FROM index_sdbnv2_product_plain  
        WHERE                   
            $condSearch  
            $condPriceMin  
            $condPriceMax 
            $condQuantityAvailableMin 
            $condBrand 
            $condBrandCategory 
            $condSupplier 
            $condObsolete 
        GROUP BY id    
        ORDER BY $order  
        LIMIT $limit  
        OPTION ranker=PROXIMITY_BM25, field_weights=(product_name=10), max_matches=" . CountOnPage::CopMax . "
        ";

        $rows = Yii::$app->sphinx->createCommand($sql, [])->queryAll();

        /*
        $filePath = Yii::getAlias('@webroot/log/sphinx.html');
        $handle = fopen($filePath, 'a+');
        fwrite($handle, PHP_EOL . print_r($filters, 1) . PHP_EOL . $sql . PHP_EOL);
        fclose($handle);
        */

        $res = [];
        if ($rows) {
            foreach ($rows as $row) {
                $res[] = new SearchProductDto(
                    id: (string) $row['id'],
                    relevance: intval($row['relevance']),
                );
            }
        }

        return $res;
    }
}
