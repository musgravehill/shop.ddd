<?php

use yii\helpers\Html;
use app\components\HelperY;
use yii\helpers\Url;
use app\models\UserHelper;
//
use app\models\ProductHelper;

$message = 'Какие сроки на 1шт.? ' . PHP_EOL
    . Url::to(['product/view', 'id' => $p['id'],], true)
    . ' ' . PHP_EOL . Html::encode($p['name'])
    . ' ' . $p['price'] . 'р.';
?>

<tr data-product-type-id="<?= Html::encode($p['type_id']) ?>" data-relevance="<?= Html::encode($productSphinx['relevant']) ?>" titlez="<?= Html::encode($productSphinx['relevant']) ?>">
    <!--td style="width: 108px;">
    <?php if ($p['photo_url_1']) : ?>
                            <img src="<?= Html::encode($p['photo_url_1']) ?>" alt="<?= Html::encode($p['name']) ?>" class="img-responsive" style="max-height: 48px; max-width: 100px;" >
    <?php endif; ?>
    </td-->
    <td>
        <a class="text-dark" href="<?= Url::to(['product/view', 'id' => $p['id'],]); ?>">
            <?= Html::encode($p['name']) ?>
        </a>
        <br>
        <span class="text-secondary helper-font-italic">
            <span address_id="<?= Html::encode($productSphinx['address_id']) ?>" productSearch__city productSearch__city_todo></span>
        </span>

    </td>
    <td title="Наличие" class="d-none d-md-table-cell text-center">
        <span class="helper-font-14 text-secondary"><span moment="DD.MM.YY"><?= $p['dt_upd'] ?></span></span>
        <br>
        <?php if ($p['count_available'] > 0) : ?>
            <span class="badge bg-success text-white"><?= (int) $p['count_available'] ?></span>
        <?php else : ?>
            <span common_modal__seturl="<?= Url::to(['support/create', 'ref_url' => yii\helpers\Url::current([], false), 'message' => urlencode($message),]); ?>" todo class="helper-no-decor d-inline-block; helper-font-bold helper-cursor-pointer" style="color:#2E406B;">
                <span class="helper-font-12 text-secondary helper-dashed text-nowrap">под заказ</span>
            </span>
        <?php endif; ?>
    </td>
    <td class="text-center">
        <div class="d-block text-nowrap">
            <span numeral="my10k"><?= (float) $p['price'] ?></span> р.
        </div>
        <div class="d-block text-nowrap">
            <?=
            $this->context->renderPartial('/cart/_common_cart_btn', [
                'productId' => $p['id'],
                'btn_submit_title' => '',                
                'btn_class' => 'btn btn-sm text-success helper-font-16',
            ]);
            ?>
        </div>
        <div class="d-block text-nowrap d-md-none">
            <?php if ($p['count_available'] > 0) : ?>
                <span class="badge bg-success text-white"><?= (int) $p['count_available'] ?></span>
            <?php else : ?>
                <span common_modal__seturl="<?= Url::to(['support/create', 'ref_url' => yii\helpers\Url::current([], false), 'message' => urlencode($message),]); ?>" todo class="helper-no-decor d-inline-block; helper-font-bold helper-cursor-pointer" style="color:#2E406B;">
                    <span class="helper-font-12 text-secondary helper-dashed text-nowrap">под заказ</span>
                </span>
            <?php endif; ?>
        </div>
    </td>
</tr>