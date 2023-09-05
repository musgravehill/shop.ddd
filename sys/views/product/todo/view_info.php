<?php

use yii\helpers\Html;
use app\components\HelperY;
use yii\helpers\Url;
use app\models\ProductHelper;
use app\models\SaleHelper;

$this->title = Html::encode(HelperY::sanitizeWDS($p->name));

\Yii::$app->view->registerMetaTag([
    'name' => 'keywords',
    'content' => $this->title
]);
\Yii::$app->view->registerMetaTag([
    'name' => 'description',
    'content' => $this->title
]);
?>
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h1>
                    <?= Html::encode($p->name) ?>
                </h1>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <h2>
                            <?php if ($p['brand_id']) : ?>
                                <div class="helper-font-16 d-inline-block">
                                    <a href="<?= Url::to(['brand/view', 'id' => $p['brand_id'],]); ?>" class="text-dark">
                                        <span data-brand-name-render data-brand-id="<?= Html::encode($p['brand_id']) ?>"></span>
                                    </a>
                                    /
                                    <a href="<?= Url::to(['brandcategory/view', 'id' => $p['brand_category_id'],]); ?>" class="text-dark">
                                        <span data-brand-category-name-render data-brand-category-id="<?= Html::encode($p['brand_category_id']) ?>"></span>
                                    </a>
                                </div>
                                <span class="helper-cursor-pointer helper-font-19" title="Можно получить индивидуальную скидку по этой группе товаров">
                                    <span class="icon-sale text-success"></span>
                                </span>
                            <?php else : ?>
                                <a href="<?= Url::to(['brand/list']); ?>" class="text-dark helper-font-16">
                                    Производители
                                </a>
                            <?php endif; ?>
                        </h2>
                    </div>
                    <div class="col-12 col-md-4 mb-3">
                        <?=
                        $this->context->renderPartial('/product/_view_carousel_img', [
                            'p' => $p,
                        ]);
                        ?>
                    </div>
                    <div class="col-12 col-md-8">
                        <?php if ($p->price > 0) : ?>
                            <div class="d-block">
                                <span class="helper-font-bold helper-font-22">
                                    <span data-target="salePersonalBrand_pricePrev" data-brand-id="<?= (int)$p['brand_id'] ?>" data-brand-category-id="<?= (int)$p['brand_category_id'] ?>">
                                        <span numeral="my10k"><?= (float) $p->price ?></span>
                                    </span> р.
                                </span>
                                <span data-target="salePersonalBrand_badge" data-brand-id="<?= (int)$p['brand_id'] ?>" data-brand-category-id="<?= (int)$p['brand_category_id'] ?>" class="d-none badge badge-success" title="Ваша персональная скидка"></span>
                                <span data-target="salePersonalBrand_priceNew_wrapper" data-brand-id="<?= (int)$p['brand_id'] ?>" data-brand-category-id="<?= (int)$p['brand_category_id'] ?>" class="d-none">
                                    <br>
                                    <span class="helper-font-bold helper-font-22">
                                        <span data-target="salePersonalBrand_priceNew" data-brand-id="<?= (int)$p['brand_id'] ?>" data-brand-category-id="<?= (int)$p['brand_category_id'] ?>" data-price="<?= (float) $p->price ?>"></span>
                                        р.
                                    </span>
                                </span>
                            </div>
                            <div class="d-block mt-3">
                                <?=
                                $this->context->renderPartial('/cart/_common_cart_btn', [
                                    'productId' => $p['id'],
                                    'btn_submit_title' => 'В корзину',                                     
                                    'btn_class' => 'btn btn-danger',
                                ]);
                                ?>
                            </div>
                        <?php endif; ?>
                        <div class="d-block mt-3">
                            <?= $p->dsc ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?=
$this->context->renderPartial('/product/_common_search', [
    'productSearch__url' => $productSearch__url,
    'productInfoSearch__url' => $productInfoSearch__url,
    'isShowFormFilters' => true,
    'title' => '',
    'isHideOnLowRelevance' => true,
    'productInfoSearch__isSort' => false,
]);
?>

<div itemtype="https://schema.org/Product" itemscope class="d-none">
    <meta itemprop="mpn" content="<?= HelperY::sanitizeMeta($p['id']) ?>" />
    <meta itemprop="name" content="<?= HelperY::sanitizeMeta($p['name']); ?>" />
    <link itemprop="image" href="<?= HelperY::sanitizeMeta($p->photo_url_1) ?>" />
    <meta itemprop="description" content="<?= HelperY::sanitizeMeta($p['dsc']); ?>" />
    <div itemprop="offers" itemtype="https://schema.org/Offer" itemscope>
        <link itemprop="url" href="<?= Url::to(['product/view', 'id' => $p['id'],], true) ?>" />
        <meta itemprop="availability" content="https://schema.org/InStock" />
        <meta itemprop="priceCurrency" content="RUB" />
        <meta itemprop="itemCondition" content="https://schema.org/NewCondition" />
        <meta itemprop="price" content="<?= HelperY::sanitizeMeta($p->price) ?>" />
        <meta itemprop="priceValidUntil" content="<?= date('Y-m-d', strtotime('+100 days')) ?>" />
    </div>
    <meta itemprop="sku" content="<?= (int) $p['id']; ?>" />
    <div itemprop="brand" itemtype="https://schema.org/Brand" itemscope>
        <meta itemprop="name" content="<?= HelperY::sanitizeMeta(app\models\BrandHelper::getName($p['brand_id'])); ?>" />
    </div>
</div>

 