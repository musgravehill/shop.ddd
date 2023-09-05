<?php

use yii\helpers\Html;
use app\components\HelperY;
use yii\helpers\Url;
use app\models\UserHelper;
//
use app\models\ProductHelper;

?>

<tr data-component="productSearchCommon" data-purpose="item" data-name="<?= Html::encode($p['name']) ?>" data-price="<?= (float) $p['price'] ?>" data-product-counter-view="<?= Html::encode($p['counter_view']) ?>" data-product-type-id="<?= Html::encode($p['type_id']) ?>" data-relevance="<?= Html::encode($productSphinx['relevant']) ?>" titlez="<?= Html::encode($productSphinx['relevant']) ?>">
    <td style="width: 70px;" class="d-none d-md-table-cell">
        <?php if ($p['photo_url_1']) : ?>
            <a href="<?= Url::to(['product/view', 'id' => $p['id'],]); ?>">
                <img src="<?= Html::encode($p['photo_url_1']) ?>" alt="<?= Html::encode($p['name']) ?>" class="img-responsive" style="max-height: 48px; max-width: 64px;">
            </a>
        <?php endif; ?>
    </td>
    <td>
        <a class="text-dark helper-font-16" href="<?= Url::to(['product/view', 'id' => $p['id'],]); ?>">
            <?= Html::encode($p['name']) ?>
        </a>
        <br>
        <a href="<?= Url::to(['brand/view', 'id' => $p['brand_id'],]); ?>" class="text-secondary helper-font-italic">
            <span data-brand-name-render data-brand-id="<?= Html::encode($p['brand_id']) ?>"></span>
        </a>
    </td>
    <td style="width: 110px;" class="text-center">
        <?php if ($p['photo_url_1']) : ?>
            <div style="width: 70px;" class="d-block d-md-none mb-3">
                <a href="<?= Url::to(['product/view', 'id' => $p['id'],]); ?>">
                    <img src="<?= Html::encode($p['photo_url_1']) ?>" alt="<?= Html::encode($p['name']) ?>" class="img-responsive" style="max-height: 48px; max-width: 64px;">
                </a>
            </div>
        <?php endif; ?>
        <?php if ($p['price'] > 0) : ?>
            <div class="d-block text-nowrap">
                <span data-target="salePersonalBrand_priceIncludingPossibleDiscount" data-brand-id="<?= (int)$p['brand_id'] ?>" data-brand-category-id="<?= (int)$p['brand_category_id'] ?>" data-price="<?= (float) $p['price'] ?>">
                    <span numeral="my10k"><?= (float) $p['price'] ?></span>
                </span> р.
            </div>
            <div class="d-block">
                <div class="d-inline-block text-nowrap mb-1">
                    <?=
                    $this->context->renderPartial('/cart/_common_cart_btn', [
                        'productId' => $p['id'],
                        'btn_submit_title' => '',                         
                        'btn_class' => 'btn btn-sm text-success helper-font-16 p-0',
                    ]);
                    ?>
                </div>
                <div class="d-inline-block">
                    <span data-target="salePersonalBrand_badge" data-brand-id="<?= (int)$p['brand_id'] ?>" data-brand-category-id="<?= (int)$p['brand_category_id'] ?>" class="d-none badge badge-success" title="Ваша персональная скидка"></span>
                </div>
            </div>
        <?php endif; ?>
    </td>
</tr>
