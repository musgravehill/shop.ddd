<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;
//
use app\components\Supplier\Domain\Entity\Supplier;
/** @var null|Supplier $supplier */
?>
<div data-filter-name="<?= Html::encode($supplier->getName()->getName()) ?>" class="col-6 col-md-2 col-lg-2">
    <div class="common_list__item">
        <a href="<?= Url::to(['supplier/view', 'id' => $supplier->getId()->getId(),]); ?>" class="helper-no-decor text-dark helper-font-14">
            <div class="common_list__item_img_container">
                <img src="<?= Html::encode(HelperY::getRelativeUrl($supplier->getImgUrl()->getImgUrl())) ?>" alt="<?= Html::encode($supplier->getName()->getName()) ?>" class="common_list__item_img">
            </div>
            <div class="common_list__item_ttl">
                <?= Html::encode($supplier->getName()->getName()) ?>                
            </div>
        </a>
    </div>
</div>
