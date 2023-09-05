<?php

use yii\helpers\Html;
use app\components\HelperY;
use yii\helpers\Url;
?>

<div class="d-inline-block">
    <div data-component="cart" data-purpose="controlsContainerPut" data-product-id="<?= $productId ?>" class="d-none">
        <div data-component="cart" data-purpose="controlBtn" data-action="put" data-todo="1" data-product-id="<?= $productId ?>">
            <button class="<?= Html::encode($btn_class) ?>" title="Купить" type="button">
                <?php if ($btn_submit_title) : ?>
                    <?= Html::encode($btn_submit_title) ?>
                <?php else : ?>
                    <span class="icon-shoppingcartalt"></span>
                <?php endif; ?>
            </button>
        </div>
    </div>
    <div data-component="cart" data-purpose="controlsContainerSet" data-product-id="<?= $productId ?>" class="d-none">
        <div data-component="cart" data-purpose="controlBtn" data-action="dec" data-todo="1" data-product-id="<?= $productId ?>" class="d-inline-block">
            <button class="btn btn-outline-secondary btn-sm align-middle" title="Уменьшить" type="button">
                <span class="icon-minus"></span>
            </button>
        </div>
        <input data-component="cart" data-purpose="controlBtn" data-action="set" data-todo="1" data-product-id="<?= $productId ?>" type="number" class="form-control form-control-sm d-inline-block border-secondary align-middle" style="width: 70px;">
        <div data-component="cart" data-purpose="controlBtn" data-action="inc" data-todo="1" data-product-id="<?= $productId ?>" class="d-inline-block">
            <button class="btn btn-outline-secondary btn-sm align-middle" title="Увеличить" type="button">
                <span class="icon-plus"></span>
            </button>
        </div>
    </div>
</div>
<div class="d-inline-block">
    <div data-component="cart" data-purpose="inProcessSpinnerContainer" data-product-id="<?= $productId ?>" class="d-none">
        <span data-component="cart" data-purpose="inProcessSpinner" class="spinner-border text-danger spinner-border-sm" role="status"></span>
    </div>
</div>

<!--div data-component="cart" data-purpose="controlBtn" data-action="del" data-todo="1" data-product-id="???"></div-->