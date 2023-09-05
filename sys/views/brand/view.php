<?php

use app\components\Brand\Domain\Entity\Brand;
use app\components\Brand\Infrastructure\BrandImgService;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;

/** @var Brand $brand */

$this->title =  Html::encode($brand->getName()->getName());

\Yii::$app->view->registerMetaTag([
    'name' => 'keywords',
    'content' => 'СпецДилер - агрегатор компаний и скидок ',
]);
\Yii::$app->view->registerMetaTag([
    'name' => 'description',
    'content' => 'СпецДилер - агрегатор компаний и скидок ',
]);
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <img src="<?= BrandImgService::getPublicUrlRelative($brand->getLogoFn()) ?>" style="height: 48px; max-width: 100px;">
                    <h1 class="d-inline-block">
                        <?= Html::encode($brand->getName()->getName()) ?>
                    </h1>
                     
                </div>
            </div>
            <div class="card-body">
                <div class="row" id="brand__brandcategorys"></div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h2>Популярные товары</h2>
            </div>
            <div class="card-body">
                <div class="row" id="product__showcase">
                </div>
                <hr>
                <?= $brand->getDsc()->getDsc() ?>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        brand__brandcategorys();
        async function brand__brandcategorys() {
            const url = '<?= Url::to(['brandcategory/brand', 'id' => $brand->getId()->getId()], true); ?>';
            const data = {};
            const response = await helper_getData(url, data);
            const res = await response.text();
            document.getElementById('brand__brandcategorys').innerHTML = res;
        }

        product__showcase();
        async function product__showcase() {
            const url = '<?= Url::to(['product/showcase', 'countOnPage' => 24, 'brandId' => $brand->getId()->getId(),], true); ?>';
            const data = {};
            const response = await helper_getData(url, data);
            const res = await response.text();
            document.getElementById('product__showcase').innerHTML = res;
        };
    });
</script>