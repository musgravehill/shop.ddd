<?php

use app\components\BrandCategory\Domain\Entity\BrandCategory;
use app\components\BrandCategory\Infrastructure\BrandCategoryImgService;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;

/** @var BrandCategory $brandCategory */
?>

<div data-filter-name="<?= Html::encode($brandCategory->getName()->getName()) ?>" class="col-6 col-md-2 col-lg-2">
    <div class="common_list__item">
        <a href="<?= Url::to(['brandcategory/view', 'id' => $brandCategory->getId()->getId(), 'ufu' => $brandCategory->getUfu()->getUfu(),]); ?>" class="helper-no-decor text-dark helper-font-14">
            <?php if ($salePersonalBrandCategoryPercent) : ?>
                <div title="Ваша персональная скидка" class="common_list__sale">
                    -<?= intval($salePersonalBrandCategoryPercent) ?>%
                </div>
            <?php endif; ?>
            <div class="common_list__item_img_container">
                <img src="<?= BrandCategoryImgService::getPublicUrlRelative($brandCategory->getLogoFn()) ?>" alt="<?= Html::encode($brandCategory->getName()->getName()) ?>" class="common_list__item_img">
            </div>
            <div class="common_list__item_ttl" style="height: 6.2em;">
                <?= Html::encode($brandCategory->getName()->getName()) ?>
            </div>
        </a>
    </div>
</div>