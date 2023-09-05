<?php

use yii\helpers\Html;
use app\components\HelperY;
use yii\helpers\Url;
//
use app\models\ProductHelper;

$this->title = 'Поиск: ' . Html::encode($urlParams['q']);

\Yii::$app->view->registerMetaTag([
    'name' => 'keywords',
    'content' => Html::encode($urlParams['q']) . ' СпецДилер купить'
]);
\Yii::$app->view->registerMetaTag([
    'name' => 'description',
    'content' => Html::encode($urlParams['q']) . ' СпецДилер купить'
]);
?>

<div class="row">
    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mt-1 mb-2">
        <form method="GET" action="<?= Url::toRoute(['product/search']) ?>">
            <div class="form-row">                
                <div class="col-auto">
                    <input value="<?= Html::encode($urlParams['q']) ?>" type="text" name="q" class="form-control mr-1" placeholder="товар, услуга" style="width: 315px;">
                </div>                
            </div>
            <div class="form-row mt-1">
                <div class="col-auto">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Цена от</div>
                        </div>
                        <input zero2null title="от" placeholder="" name="price_min" type="number" class="form-control" value="<?= (int) $urlParams['price_min'] ?>" style="width: 90px;" step="1" />
                    </div>
                </div>
                <div class="col-auto">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">до</div>
                        </div>
                        <input zero2null title="до" placeholder="" name="price_max" type="number" class="form-control" value="<?= (int) $urlParams['price_max'] ?>" style="width: 90px;" step="1" />
                    </div>
                </div>
            </div>
            <div class="form-row mt-1">
                <div class="col-auto">
                    <input <?= ($urlParams['type_id'] === ProductHelper::TYPE_SERVICE) ? 'checked' : '' ?> name="type_id" type="radio" value="<?= Html::encode(ProductHelper::TYPE_SERVICE) ?>" /> Услуга
                    <input <?= ($urlParams['type_id'] === ProductHelper::TYPE_PRODUCT) ? 'checked' : '' ?> name="type_id" type="radio" class="ml-3" value="<?= Html::encode(ProductHelper::TYPE_PRODUCT) ?>" /> Товар
                     </div>
            </div>  
            <div class="form-row mt-1">
                <div class="col-auto">                 
                    <input <?= ($urlParams['distance'] === ProductHelper::DISTANCE_km_50) ? 'checked' : '' ?> name="distance" type="radio" class="" value="<?= Html::encode(ProductHelper::DISTANCE_km_50) ?>" /> <?= Html::encode(ProductHelper::DISTANCE_km_50) ?>км
                    <input <?= ($urlParams['distance'] === ProductHelper::DISTANCE_km_all) ? 'checked' : '' ?> name="distance" type="radio" class="ml-3" value="<?= Html::encode(ProductHelper::DISTANCE_km_all) ?>" /> Везде
                </div>
            </div>            
            <div class="form-row mt-2">
                <div class="col-auto">
                    <button type="submit" class="btn btn-danger zd-none zd-sm-block">
                        Поиск
                    </button>
                    <br>
                    <span class="helper-font-12 text-secondary">
                        *можно расширить условия поиска
                    </span>
                    <!--button type="submit" class="btn btn-danger d-block d-sm-none">
                        <span class="icon-search"></span>
                    </button-->
                </div>
            </div>
        </form>
    </div>

    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <?=
        $this->context->renderPartial('_search_map', [
            'companies_addresses' => $companies_addresses,
            'companies_urls' => $companies_urls,
            'geoUserData' => $geoUserData,
        ]);
        ?>
        <hr />
    </div>
</div>



<header class="sticky-top" id="search_sticker" style="box-shadow: 0 0px 10px 7px #fff;">
    <nav class="bg-light p-2" id="search_sticker_header" style="display: none;">
        <span class="d-inline-block helper-font-22 helper-cursor-pointer" title="Вверх" onclick=" $(window).scrollTop(0);">
            <span class="icon-arrow-left helper-font-16"></span>
            <b>
                <?= (isset($urlParams['q'][1])) ? Html::encode($urlParams['q']) : 'поиск' ?>
            </b>
        </span>
        <div class="float-right d-inline-block">
            <a href="<?= Url::toRoute('cart/index') ?>" class="mr-3 helper-no-decor d-inline-block">
                <span
                    cart__top_nav_count_total
                    class="badge badge-danger"
                    style="vertical-align: top; display: none;"
                    >
                </span>
                <span
                    cart__top_nav_icon
                    class="icon-shopping-cart helper-font-20 helper-font-bold"
                    style="display: none;"
                    >
                </span>
            </a>
        </div>
    </nav>
</header>


<div class="row">
    <div class="col-12 mt-3">
        <?php //(Yii::$app->user->isGuest):     ?>
        <?=
        $this->context->renderPartial('_search_products', [
            'q' => $urlParams['q'],
            'ps' => $ps,
            'companies_addresses' => $companies_addresses,
        ]);
        ?>
    </div>
</div>
<div class="row"> 
    <div class="col-12 mb-5 mt-1">
        <?php
        $urlParamsTmp = $urlParams;
        $urlParamsTmp['page'] = $urlParamsTmp['page'] - 1;
        ?>
        <?php if ($urlParamsTmp['page'] > 0): ?>
            <a href="<?= Url::toRoute($urlParamsTmp) ?>" class="helper-font-30">
                <span class="btn btn-outline-dark btn-sm">назад</span>
            </a>
        <?php endif; ?>
        <?php
        $urlParamsTmp = $urlParams;
        $urlParamsTmp['page'] = $urlParamsTmp['page'] + 1;
        ?>
        <a href="<?= Url::toRoute($urlParamsTmp) ?>" class="float-right helper-font-30">
            <span class="btn btn-outline-dark btn-sm">дальше</span>
        </a>

    </div>
</div>


<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        $('input[zero2null]').each(function () {
            let v = parseInt($(this).val());
            if (v === 0) {
                $(this).val('');
            }
        });

        search_sticker();
    });

    function search_sticker() {
        $(window).scroll(function () {
            let t = parseInt($('#search_sticker').offset().top) - parseInt($(window).scrollTop());
            if (t <= 1) {
                $('#search_sticker_header').show();
            } else {
                $('#search_sticker_header').hide();
            }
        });
    }
</script>

