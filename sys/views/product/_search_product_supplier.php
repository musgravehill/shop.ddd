<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;
use app\components\Brand\Domain\Entity\Brand;
use app\components\BrandCategory\Domain\Entity\BrandCategory;
use app\components\Product\Domain\Entity\Product;
use app\components\Search\Domain\SearchProductDto;

/** @var Product $product */
/** @var Brand $brand */
/** @var BrandCategory $brandCategory */
/** @var SearchProductDto $searchProductDto */
?>

<tr data-component="productSearchCommon" data-purpose="item" data-name="<?= Html::encode($product->getName()->getName()) ?>" data-price="<?= (int) $product->getPriceSelling()->getFractionalCount() ?>" data-relevance="<?= $searchProductDto->getRelevance() ?>" title="<?= $searchProductDto->getRelevance() ?>">
    <!--td style="width: 70px;" class="d-none d-md-table-cell">
        <a href="<?= Url::to(['product/view', 'id' => $product->getId()->getId(), 'ufu' => $product->getUfu()->getUfu()]); ?>">
            <img src="<?= Html::encode($imgUrls[0]) ?>" alt="<?= Html::encode($product->getName()->getName()) ?>" class="img-responsive" style="max-height: 48px; max-width: 64px;">
        </a>
    </td-->
    <td>
        <a class="d-block text-dark helper-font-16" href="<?= Url::to(['product/view', 'id' => $product->getId()->getId(), 'ufu' => $product->getUfu()->getUfu()]); ?>">
            <span data-component="productSearchCommon" data-purpose="itemName"><?= Html::encode($product->getName()->getName()) ?></span>
        </a>         
        <div class="d-block helper-font-italic helper-font-13 text-secondary">
            <span><?= Html::encode($supplier->getCityName()->getCityName()) ?></span>
        </div>
    </td>
    <td style="width: 110px;" class="text-center">
        <div class="d-block text-nowrap">
            <span numeral="my10k"><?= (float) ($product->getPriceSelling()->getFractionalCount() / 100) ?></span>
            р.
        </div>
        <div class="d-inline-block ">
            <div class="d-inline-block text-nowrap mb-1">
                <?=
                $this->context->renderPartial('/cart/_common_cart_btn', [
                    'productId' => $product->getId()->getId(),
                    'btn_submit_title' => '',
                    'btn_class' => 'btn btn-sm text-success helper-font-16 p-0',
                ]);
                ?>
            </div>
        </div>
        <div class="d-inline-block text-nowrap">
            <?php if ($product->getQuantityAvailable()->getQuantity() > 0) : ?>
                <span class="badge bg-success text-white" title="В наличии у поставщика"><?= $product->getQuantityAvailable()->getQuantity() ?></span>
            <?php else : ?>
                <span class="helper-font-12 text-secondary text-nowrap">под заказ</span>
            <?php endif; ?>
        </div>
    </td>
</tr>