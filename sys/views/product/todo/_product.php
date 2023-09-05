<?php

use yii\helpers\Html;
use app\components\HelperY;
use yii\helpers\Url;
//
use app\models\ProductHelper;
use app\models\CartHelper;
use app\models\SaleHelper;

/*$sale_id = isset($sale) ? $sale['id'] : 0;
$saleData = SaleHelper::getProductSaleParams($p['id'], $sale_id, $p['price']);
$saleData['sale_info'];
$saleData['sale_dt_to'];
$saleData['sale_url'];
$saleData['price_with_sale'];
$saleData['is_actual'];
$saleData['is_available'];*/
?>

<div class="col-6 col-md-4 col-lg-3" product__item product_id="<?= (int) $p['id'] ?>" >
    <div class="product__item">
        <?php if ($p['photo_url_1']): ?>
            <span  todo common_modal__setUrl="<?= Url::to(['product/view', 'id' => $p['id'], 'layout' => '1',]); ?>" class="helper-cursor-pointer">
                <img
                    data-src="<?= Html::encode($p['photo_url_1']) ?>"
                    src="/img/white.png"
                    class="lazy product__item-img"
                    />
            </span>
        <?php endif; ?>
        <div class="product__item-content">
            <div class="product__item-title">
                <a class="product__item-title-a text-dark" href="<?= Url::to(['product/view', 'id' => $p['id'],]); ?>"   >
                    <?= Html::encode($p['name']) ?>
                </a>
            </div>
            <div class="product__item-dsc">
                <a class="text-dark" href="<?= Url::to(['product/view', 'id' => $p['id'],]); ?>"   >
                    <?= $p['dsc'] ?>
                </a>
            </div>
            <div class="product__item-price">
                <?php if ($p['price'] > 0): ?>
                    <?php if ($saleData['is_actual'] && $saleData['is_available']): ?>
                        <strike class="text-secondary helper-font-13">
                            <span numeral="my10k"><?= (float) $p['price'] ?></span> р.
                        </strike>
                        <span class="text-danger ml-1 helper-font-13">-<?= $saleData['sale_info'] ?></span>
                        <br>
                    <?php endif; ?>
                    <span numeral="my10k"><?= (float) $saleData['price_with_sale'] ?></span> р.
                <?php endif; ?>
                <div class="d-inline-block float-right mb-1">
                    <?php if (!$company_sys_is_hidden): ?>
                        <?=
                        $this->context->renderPartial('/cart/_common_cart_btn', [
                            'productId' => $p['id'],
                            'btn_submit_title' => '',                            
                            'btn_class' => 'btn btn-outline-success btn-sm',                            
                        ]);
                        ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
