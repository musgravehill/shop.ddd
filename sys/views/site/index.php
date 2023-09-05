<?php

use yii\helpers\Html;
use app\components\HelperY;
use yii\helpers\Url;

$this->title = 'Маркетплейс для организаций';

\Yii::$app->view->registerMetaTag([
    'name' => 'keywords',
    'content' => Html::encode(HelperY::sanitizeWDS($this->title)),
]);
\Yii::$app->view->registerMetaTag([
    'name' => 'description',
    'content' => Html::encode(HelperY::sanitizeWDS($this->title)),
]);
?>

<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-3">
                <form class="form-inline" method="GET" action="/product/search">
                    <input type="text" name="searchQuery" class="form-control mr-1" id="product__totalcount" title="Поиск" placeholder="Поиск товаров" style="width: 200px;">
                    <button type="submit" class="btn btn-outline-secondary" title="Поиск">
                        <span class="icon-search"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="d-inline-block">
                    Популярные производители
                </h3>
            </div>
            <div class="card-body">
                <div class="row" id="brand__showcase">
                </div>
                <a href="/brand/list" class="text-dark helper-font-20 mt-1 helper-font-bold helper-underline">
                    Все производители
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="d-inline-block">
                    Популярные категории
                </h3>
            </div>
            <div class="card-body">
                <div class="row" id="brandcategory__showcase">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="d-inline-block">
                    Популярные товары
                </h3>
            </div>
            <div class="card-body">
                <div class="row" id="product__showcase">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="d-inline-block">
                    Новости
                </h3>
            </div>
            <div class="card-body">
                <div class="row" id="page__showcase">
                </div>
                <a href="/page/list" class="text-dark helper-font-20 mt-1 helper-font-bold helper-underline">
                    Все новости
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="d-inline-block">
                    Поставщики
                </h3>
            </div>
            <div class="card-body">
                <div class="row" id="supplier__showcase">
                </div>
                <a href="/supplier/list" class="text-dark helper-font-20 mt-1 helper-font-bold helper-underline">
                    Все поставщики
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        brand__showcase();
        supplier__showcase();
        brandcategory__showcase();
        product__showcase();
        page__showcase();
        product__totalcount();

        async function product__totalcount() {
            const url = '<?= Url::to(['product/totalcount',], true); ?>';
            const data = {};
            const response = await helper_getData(url, data);
            const res = await response.text();
            document.getElementById("product__totalcount").placeholder = res + " товар(ов)";
        };

        async function page__showcase() {
            const url = '<?= Url::to(['page/showcase',], true); ?>';
            const data = {};
            const response = await helper_getData(url, data);
            const res = await response.text();
            document.getElementById('page__showcase').innerHTML = res;
        };

        async function product__showcase() {
            const url = '<?= Url::to(['product/showcase', 'countOnPage' => 24,], true); ?>';
            const data = {};
            const response = await helper_getData(url, data);
            const res = await response.text();
            document.getElementById('product__showcase').innerHTML = res;
        };

        async function brand__showcase() {
            const url = '<?= Url::to(['brand/showcase',], true); ?>';
            const data = {};
            const response = await helper_getData(url, data);
            const res = await response.text();
            document.getElementById('brand__showcase').innerHTML = res;
        };

        async function supplier__showcase() {
            const url = '<?= Url::to(['supplier/showcase',], true); ?>';
            const data = {};
            const response = await helper_getData(url, data);
            const res = await response.text();
            document.getElementById('supplier__showcase').innerHTML = res;
        };

        async function brandcategory__showcase() {
            const url = '<?= Url::to(['brandcategory/showcase',], true); ?>';
            const data = {};
            const response = await helper_getData(url, data);
            const res = await response.text();
            document.getElementById('brandcategory__showcase').innerHTML = res;
        };
    });
</script>