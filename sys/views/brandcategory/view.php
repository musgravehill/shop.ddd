<?php

use app\components\BrandCategory\Domain\Entity\BrandCategory;
use app\components\BrandCategory\Infrastructure\BrandCategoryImgService;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;

/** @var BrandCategory $brandCategory */

$this->title =  Html::encode($brandCategory->getName()->getName());

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
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <img src="<?= BrandCategoryImgService::getPublicUrlRelative($brandCategory->getLogoFn()) ?>" style="height: 64px; max-width: 100px;">
                    <h1 class="d-inline-block">
                        <!--a href="<?= Url::to(['brand/list']); ?>" class="text-dark">
                            Производители
                        </a-->
                        <?= Html::encode($brandCategory->getName()->getName()) ?>
                        <a href="<?= Url::to(['brand/view', 'id' => $brand->getId()->getId(), 'ufu' => $brand->getUfu()->getUfu()]); ?>" class="text-dark helper-underline">
                            <?= Html::encode($brand->getName()->getName()) ?>
                        </a>
                    </h1>
                    
                </div>
            </div>
            <div class="card-body">

                <form method="GET" class="d-block mt-1">
                    <div class="d-block d-md-inline-block mr-1 mb-1">
                        <input value="<?= Html::encode($filterQueryClient) ?>" type="text" name="filterQueryClient" class="form-control" placeholder="Поиск" style="width: 344px;">
                    </div>
                    <div class="d-block d-md-inline-block mr-1 mb-1">
                        <button type="submit" class="btn btn-outline-secondary">
                            <span class="icon-search"></span>
                        </button>
                    </div>
                </form>

                <?php $searchOffers = explode(',', $brandCategory->getSearchOffers()->getSearchOffers()); ?>
                <?php if (isset($searchOffers[0]) && isset($searchOffers[0][0])) : ?>
                    <a href="?" class="btn btn-outline-secondary mt-1">
                        Все
                    </a>
                    <?php foreach ($searchOffers as $searchOffer) : ?>
                        <?php if (isset($searchOffer[2])) : ?>
                            <a href="?filterQueryClient=<?= Html::encode($searchOffer) ?>" class="btn btn-outline-secondary mt-1">
                                <?= $searchOffer ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
                <?=
                $this->context->renderPartial('/product/_search_brandcategory', [
                    'searchProductsSeo__url' => $searchProductsSeo__url,
                    'jsOnChunkLoader' => null,
                ]);
                ?>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3 d-none">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center p-0 m-0">
                    <h2 class="d-inline-block">
                        <a href="<?= Url::to(['brand/list']); ?>" class="text-dark">
                            Производители
                        </a> /
                        <a href="<?= Url::to(['brand/view', 'id' => $brand->getId()->getId(), 'ufu' => $brand->getUfu()->getUfu()]); ?>" class="text-dark">
                            <?= Html::encode($brand->getName()->getName()) ?>
                        </a> /
                        <?= Html::encode($brandCategory->getName()->getName()) ?>
                    </h2>
                    <img src="<?= BrandCategoryImgService::getPublicUrlRelative($brandCategory->getLogoFn()) ?>" style="height: 48px; max-width: 100px;">
                </div>
            </div>
            <div class="card-body">
                <?= $brandCategory->getDsc()->getDsc() ?>
            </div>
        </div>
    </div>
</div>