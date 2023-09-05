<?php

use app\components\Brand\Domain\Entity\Brand;
use app\components\Brand\Infrastructure\BrandImgService;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;

/** @var Brand $brand */
?>

<div data-filter-name="<?= Html::encode($brand->getName()->getName()) ?>" class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
    <div class="common_list__item">
        <a href="<?= Url::to(['brand/view', 'id' => $brand->getId()->getId(), 'ufu' => $brand->getUfu()->getUfu()]); ?>" class="helper-no-decor text-dark helper-font-14">
            <div class="common_list__item_img_container">
                <img src="<?= BrandImgService::getPublicUrlRelative($brand->getLogoFn()) ?>" alt="<?= Html::encode($brand->getName()->getName()) ?>" class="common_list__item_img">
            </div>
            <div class="common_list__item_ttl">
                <?= Html::encode($brand->getName()->getName()) ?> 
            </div>
        </a>
    </div>
</div>
