<?php

use app\components\Brand\Domain\Entity\Brand;
use app\components\BrandCategory\Domain\Entity\BrandCategory;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;
use app\components\Product\Domain\Entity\Product;

/** @var Product $product */
/** @var Brand $brand */
/** @var BrandCategory $brandCategory */
?>

<?php if (is_null($brand)) : ?>
    <a href="<?= Url::to(['brand/list']); ?>" class="text-dark helper-font-16">
        Производители
    </a>
<?php else : ?>
    <div class="helper-font-16 d-inline-block">
        <a href="<?= Url::to(['brand/view', 'id' => $brand->getId()->getId(), 'ufu' => $brand->getUfu()->getUfu(),]); ?>" class="text-dark">
            <?= Html::encode($brand->getName()->getName()) ?>
        </a>
        <?php if (!is_null($brandCategory)) : ?>
            /
            <a href="<?= Url::to(['brandcategory/view', 'id' => $brandCategory->getId()->getId(), 'ufu' => $brandCategory->getUfu()->getUfu(),]); ?>" class="text-dark">
                <?= Html::encode($brandCategory->getName()->getName()) ?>
            </a>
        <?php endif; ?>
    </div>
<?php endif; ?>