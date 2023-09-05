<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;
use app\components\Product\Domain\Entity\Product;

$this->title = 'Товары';

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
                <h1 class="d-inline-block">
                    Товары
                </h1>
                <div class="d-block mt-3">
                    <form class="form-inline" method="GET" action="">
                        <a href="<?= Url::to(['product/list_adm']); ?>" class="btn btn-outline-secondary mr-3">
                            <span class="icon-home"></span>
                        </a>
                        <input value="<?= Html::encode($urlParams['searchQuery']) ?>" type="text" name="searchQuery" class="form-control mr-1" placeholder="Поиск">

                        <div class="d-inline-block">
                            <?=
                            $this->context->renderPartial('/supplier/_supplier_select', [
                                'supplierIdRawSelected' => $urlParams['supplierId'],
                                'suppliers' => $suppliers,
                            ]);
                            ?>
                        </div>

                        <input type="checkbox" name="BBcEmptyOnly" <?= 1 === intval($urlParams['BBcEmptyOnly']) ? ' checked="true" ' : '' ?> value="1" class="ml-3 mr-1"> только товары без привязки

                        <div class="d-inline-block">
                            <?=
                            $this->context->renderPartial('/brand/_brand_brandcategory_select', [
                                'brandIdRawSelected' => $urlParams['brandId'],
                                'brandCategoryIdRawSelected' => $urlParams['brandCategoryId'],
                                'brandIdsNames' => $brandIdsNames,
                                'brandCategoryIdsNamesBrands' => $brandCategoryIdsNamesBrands,
                            ]);
                            ?>
                        </div>

                        <button type="submit" class="btn btn-outline-primary ml-3">
                            <span class="icon-search"></span>
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <?= Html::beginForm('', 'post') ?>
                <table class="table table-striped table-sm table-bordered">
                    <?php foreach ($products as $product) : ?>
                        <?php /** @var Product $product */ ?>
                        <?php
                        $imgUrl = null;
                        if (isset($productsImgs[$product->getId()->getId()]) && isset($productsImgs[$product->getId()->getId()][0])) {
                            $imgUrl = $productsImgs[$product->getId()->getId()][0];
                        }
                        ?>
                        <tr>
                            <td>
                                <span class="helper-no-decor text-secondary helper-font-14">
                                    <?=
                                    isset($supplierIdsNames[$product->getSupplierId()->getId()]) ? $supplierIdsNames[$product->getSupplierId()->getId()] : '<span class="badge badge-danger">?</span>';
                                    ?>                                    
                                </span>
                            </td>
                            <td style="width: 50px;" title="Популярность">
                                <?= Html::encode($product->getViewIdx()->getIdx()) ?>
                            </td>
                            <td>
                                <a href="<?= Url::to(['product/view', 'id' => $product->getId()->getId(), 'ufu' => $product->getUfu()->getUfu()]); ?>" class="helper-no-decor text-dark helper-font-14">
                                    <img src="<?= $imgUrl ?>" alt="<?= Html::encode($product->getName()->getName()) ?>" style="height: 48px; max-width: 128px;">
                                </a>
                            </td>
                            <td>
                                <a href="<?= Url::to(['product/view', 'id' => $product->getId()->getId(), 'ufu' => $product->getUfu()->getUfu()]); ?>" class="helper-no-decor text-dark helper-font-14">
                                    <?= Html::encode($product->getName()->getName()) ?>
                                </a>
                                <div class="helper-no-decor text-secondary helper-font-14">
                                    <?=
                                    isset($brandIdsNames[$product->getBrandId()->getId()]) ? $brandIdsNames[$product->getBrandId()->getId()] : '<span class="badge badge-danger">?</span>';
                                    ?>
                                    /
                                    <?=
                                    isset($brandCategoryIdsNamesBrands[$product->getBrandCategoryId()->getId()]) ? $brandCategoryIdsNamesBrands[$product->getBrandCategoryId()->getId()]['name'] : '<span class="badge badge-danger">?</span>';
                                    ?>
                                </div>
                            </td>
                            <td style="width: 110px;" class="text-center">
                                <div class="d-block text-nowrap">
                                    <span numeral="my10k"><?= (float) ($product->getPriceSelling()->getFractionalCount() / 100) ?></span>
                                    р.
                                </div>
                                <div class="d-inline-block text-nowrap">
                                    <?php if ($product->getQuantityAvailable()->getQuantity() > 0) : ?>
                                        <span class="badge bg-success text-white"><?= $product->getQuantityAvailable()->getQuantity() ?></span>
                                    <?php else : ?>
                                        <span class="helper-font-12 text-secondary text-nowrap">под заказ</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td style="width: 10px;">
                                <input type="checkbox" name="productIds[]" value="<?= Html::encode($product->getId()->getId()) ?>">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="4">
                            <div class="d-inline-block">
                                <?=
                                $this->context->renderPartial('/brand/_brand_brandcategory_select', [
                                    'brandIdRawSelected' => $urlParams['brandId'],
                                    'brandCategoryIdRawSelected' => $urlParams['brandCategoryId'],
                                    'brandIdsNames' => $brandIdsNames,
                                    'brandCategoryIdsNamesBrands' => $brandCategoryIdsNamesBrands,
                                ]);
                                ?>
                            </div>
                            <button type="submit" class="btn btn-success">Установить Бренд\Категорию</button>
                        </td>
                        <td style="width: 10px;">
                            <input type="checkbox" id="brandBrandCategory__set_checkAll">
                        </td>
                    </tr>
                </table>
                <?= Html::endForm() ?>
            </div>
            <div class="card-footer">
                <?php
                $urlParamsTmp = $urlParams;
                $urlParamsTmp['page'] = $urlParamsTmp['page'] - 1;
                ?>
                <?php if ($urlParamsTmp['page'] > 0) : ?>
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
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("brandBrandCategory__set_checkAll").addEventListener('click', (e) => {
            const isCheckedAll = e.currentTarget.checked;
            const bs = Array.from(document.querySelectorAll('input[type="checkbox"][name="productIds[]"]'));
            for (b of bs) {
                if (isCheckedAll) {
                    b.checked = true;
                } else {
                    b.checked = false;
                }
            }
        });
    });
</script>