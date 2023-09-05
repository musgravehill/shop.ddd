<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;
use app\components\Offer\Domain\Aggregate\Offer;
use app\components\Product\Domain\Entity\Product;
use app\components\Product\Infrastructure\ProductImgService;
use app\components\Supplier\Domain\ValueObject\SupplierId;

/** @var Product $product */
/** @var Offer $offer */

$this->title = Html::encode(HelperY::sanitizeWDS($product->getName()->getName()));

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
            <div class="card-header">
                <h1>
                    <?= Html::encode($product->getName()->getName()) ?>
                </h1>
            </div>
            <div class="card-body">
                <div class="row">

                    <div class="col-12">
                        <?php if ($product->getSupplierId()->isEqualsTo(SupplierId::fromString(HelperY::params('seoSupplierId')))) : ?>
                            <h2 class="d-inline-block">
                                <?=
                                $this->context->renderPartial('/product/_brand', [
                                    'product' => $product,
                                    'brand' => $brand,
                                    'brandCategory' => $brandCategory,
                                ]);
                                ?>
                            </h2>
                            <?php if ($salePersonalBrandCategoryPercent > 0) : ?>
                                <span class="badge badge-success">- <?= $salePersonalBrandCategoryPercent ?> %</span>
                            <?php endif; ?>
                        <?php else : ?>
                            <div class="d-inline-block text-nowrap">

                                <span class="helper-font-bold mr-3"><?= Html::encode($supplier->getCityName()->getCityName()) ?></span>

                                <?php if ($product->getQuantityAvailable()->getQuantity() > 0) : ?>
                                    <span class="badge bg-success text-white" title="В наличии у поставщика"><?= $product->getQuantityAvailable()->getQuantity() ?></span>
                                <?php else : ?>
                                    <span class="helper-font-12 text-secondary text-nowrap">под заказ</span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php
                    if ($imgUrls[0] === ProductImgService::NO_PHOTO_URL_PUBLIC) {
                        $classImgs = 'd-none';
                        $classContent = 'col-12';
                    } else {
                        $classImgs = 'col-12 col-md-4 mb-3';
                        $classContent = 'col-12 col-md-8';
                    }
                    ?>

                    <div class="<?= $classImgs ?>">
                        <?=
                        $this->context->renderPartial('/product/_view_carousel_img', [
                            'imgUrls' => $imgUrls,
                        ]);
                        ?>
                    </div>
                    <div class="<?= $classContent ?>">
                        <?php if ($product->getPriceSelling()->getFractionalCount() > 0) : ?>
                            <div class="d-block">
                                <?php if ($product->getPriceSelling()->getFractionalCount() > $offer->getTotalCost()->getFractionalCount()) : ?>
                                    <span class="helper-font-bold helper-font-22 helper-text-strike">
                                        <span numeral="my10k"><?= (float) $product->getPriceSelling()->getFractionalCount() / 100 ?></span> р.
                                    </span>
                                    <br>
                                    <span class="helper-font-bold helper-font-22">
                                        <span numeral="my10k"><?= (float) $offer->getTotalCost()->getFractionalCount() / 100 ?></span> р.
                                    </span>
                                <?php else : ?>
                                    <span class="helper-font-bold helper-font-22">
                                        <span numeral="my10k"><?= (float) $product->getPriceSelling()->getFractionalCount() / 100 ?></span>
                                        р.
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="d-block mt-3">
                                <?=
                                $this->context->renderPartial('/cart/_common_cart_btn', [
                                    'productId' => $product->getId()->getId(),
                                    'btn_submit_title' => 'В корзину',
                                    'btn_class' => 'btn btn-danger',
                                ]);
                                ?>
                            </div>
                        <?php endif; ?>
                        <div class="d-block mt-3">
                            <?= $product->getDsc()->getDsc() ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<?=
$this->context->renderPartial('/product/_search_common', [
    'searchProductsSeo__url' => $searchProductsSeo__url,
    'searchProductsCommon__url' => $searchProductsCommon__url,
    'isShowFormFilters' => true,
    'title' => '',
]);
?>


<div itemtype="https://schema.org/Product" itemscope class="d-nonez">
    <meta itemprop="mpn" content="<?= HelperY::sanitizeWDS($product->getId()->getId()) ?>" />
    <meta itemprop="name" content="<?= HelperY::sanitizeWDS($product->getName()->getName()); ?>" />
    <link itemprop="image" href="<?= Url::to($imgUrls[0], true) ?>" />
    <meta itemprop="description" content="<?= HelperY::sanitizeWDS($product->getDsc()->getDsc()); ?>" />
    <div itemprop="offers" itemtype="https://schema.org/Offer" itemscope>
        <link itemprop="url" href="<?= Url::to(['product/view', 'id' => $product->getId()->getId(), 'ufu' => $product->getUfu()->getUfu()], true); ?>" />
        <meta itemprop="availability" content="https://schema.org/InStock" />
        <meta itemprop="priceCurrency" content="RUB" />
        <meta itemprop="itemCondition" content="https://schema.org/NewCondition" />
        <meta itemprop="price" content="<?= round($product->getPriceSelling()->getFractionalCount() / 100) ?>" />
        <meta itemprop="priceValidUntil" content="<?= date('Y-m-d', strtotime('+100 days')) ?>" />
    </div>
    <meta itemprop="sku" content="<?= HelperY::sanitizeWDS($product->getId()->getId()) ?>" />
    <?php if (!is_null($brand)) : ?>
        <div itemprop="brand" itemtype="https://schema.org/Brand" itemscope>
            <meta itemprop="name" content="<?= Html::encode($brand->getName()->getName()) ?>" />
        </div>
    <?php endif; ?>
</div>