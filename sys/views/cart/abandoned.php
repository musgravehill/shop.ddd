<?php

use yii\helpers\Html;
use app\components\HelperY;
use yii\helpers\Url;
//
$this->title = 'Корзины';

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
            <div class="card-header bg-white">
                <h1>
                    Корзины
                </h1>
            </div>
            <div class="card-body">
                <table class="table table-sm table-bordered mb-4">
                    <?php foreach ($items as $cart) : ?>
                        <tr>
                            <td>
                                <a href="<?= Url::to(['product/view', 'id' => $cart['cart_productId'], 'ufu' => $cart['product_ufu']]); ?>" class="helper-no-decor text-dark helper-font-14">
                                    <?= Html::encode($cart['product_name']) ?>
                                </a>
                            </td>
                            <td>
                                <div class="d-inline-block text-nowrap">
                                    <span numeral="my10k"><?= (float) ($cart['product_priceSelling'] / 100) ?></span>
                                    р.
                                </div>
                            </td>
                            <td>
                                <div class="d-inline-block" title="в корзине">
                                    <?= $cart['cart_quantity'] ?>
                                    <span class="icon-shopping-cart helper-font-20 helper-font-bold"></span>
                                </div>
                                <div class="d-inline-block" title="наличие">
                                    <div class="d-inline-block text-nowrap">
                                        <?php if ($cart['product_quantityAvailable']  > 0) : ?>
                                            <span class="badge bg-success text-white"><?= $cart['product_quantityAvailable']  ?></span>
                                        <?php else : ?>
                                            <span class="helper-font-12 text-secondary text-nowrap">под заказ</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?= $cart['supplier_name'] ?>
                            </td>
                            <td>
                                <a href="<?= Url::to(['user/profile', 'id' => $cart['cart_userId'],]) ?>" class="text-dark">
                                    <?= Html::encode($cart['user_username']) ?>
                                </a>
                                <?= Html::encode($cart['user_email']) ?>
                                <?= Html::encode($cart['user_phone']) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 mb-5 mt-1">
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