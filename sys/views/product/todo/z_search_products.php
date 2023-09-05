<?php

use yii\helpers\Html;
use app\components\HelperY;
use yii\helpers\Url;
?>

<div class="row products_search__row">
    <?php if ($ps): ?>       
        <?php foreach ($ps as $productSphinx): ?>
            <?php
            $p = \app\models\Product::find()->where(['id' => (int) $productSphinx['product_id'],])->asArray()->limit(1)->one();
            $cats_raw = \app\models\ProductCategory::find()->select(['category.name', 'category.id'])->leftJoin('category', 'product_category.category_id = category.id')->where(['product_category.product_id' => (int) $productSphinx['product_id'],])->asArray()->limit(2)->all();

            if (!$p) {
                continue;
            }
            echo $this->context->renderPartial('/product/_search_product', [
                'p' => $p,
                'productSphinx' => $productSphinx,
                'cats_raw' => $cats_raw,
            ]);
            ?>            
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        helper_highlight_text('<?= Html::encode($q) ?>', $('span[products_search__item-title]'), 'font-weight:bold;');
        products_search__companies_render();
    });
    function products_search__companies_render() {
        //let companies_cities = {};
        let companies_addrs_count = [];
        let company_addresses = JSON.parse('<?= addslashes(json_encode($companies_addresses, JSON_HEX_QUOT | JSON_HEX_APOS)) ?>');
        $.each(company_addresses, function (i, addr) {
            let company_id = addr.company_id;
            companies_addrs_count[company_id] = 0;

            let company_name = addr.c_name;
            $('span[products_search__item-company-name][company_id=' + company_id + ']').text(company_name);
        });
        $.each(company_addresses, function (i, addr) {
            let company_id = addr.company_id;
            companies_addrs_count[company_id]++;

            if (companies_addrs_count[company_id] > 1) {
                let addr_wt = 'адресов: ' + companies_addrs_count[company_id] + ' ';
                $('span[products_search__item-company-cities][company_id=' + company_id + ']').html(addr_wt);
            } else {
                let addr_wt = addr.dsc + ' (' + addr.work_time + ') ';
                $('span[products_search__item-company-cities][company_id=' + company_id + ']').append(addr_wt);
            }
        });


        /* $.each(company_addresses, function (i, addr) {
         let company_id = addr.company_id;
         let geo_city = addr.geo_city;
         companies_cities[company_id][geo_city] = 1;
         });
         $.each(companies_cities, function (company_id, cities) {
         let cities_unique_string = (Object.keys(cities)).join(', ');
         $('span[products_search__item-company-cities][company_id=' + company_id + ']').text('(' + cities_unique_string + ')');
         });
         */



    }
</script>