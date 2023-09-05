<?php


use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;
use app\components\Product\Domain\Entity\Product;

/** @var Product $product */
?>

<div data-filter-name="<?= Html::encode($product->getName()->getName()) ?>" class="col-6 col-md-3 col-lg-3">
    <div class="common_list__item">
        <a href="<?= Url::to(['product/view', 'id' => $product->getId()->getId(), 'ufu' => $product->getUfu()->getUfu()]); ?>" class="helper-no-decor text-dark helper-font-14">
            <div class="common_list__item_img_container">
                <img src="<?= $imgUrls[0] ?>" alt="<?= Html::encode($product->getName()->getName()) ?>" class="common_list__item_img">
            </div>
            <div class="common_list__item_ttl" style="height: 8.2em;">
                <?= Html::encode($product->getName()->getName()) ?>
            </div>
        </a>
    </div>
</div>
