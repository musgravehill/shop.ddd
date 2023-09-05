<?php

use app\components\BrandCategory\Domain\Entity\BrandCategory;
use app\components\BrandCategory\Infrastructure\BrandCategoryImgService;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;

$this->title = 'Категории брендов';

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
                <h1 class="d-inline-block">
                    Категории брендов
                </h1>
                <a href="<?= Url::to(['brandcategory/cr']); ?>" class="btn btn-outline-success ml-3">
                    создать
                </a>
                <div class="d-block mt-3">
                    <form class="form-inline" method="GET" action="">
                        <a href="<?= Url::to(['brandcategory/list_adm']); ?>" class="btn btn-outline-secondary mr-3">
                            <span class="icon-home"></span>
                        </a>
                        <input value="<?= Html::encode($urlParams['searchQuery']) ?>" type="text" name="searchQuery" class="form-control mr-1" placeholder="категория">
                        <select name="brandId" class="form-control mr-1">
                            <option <?= ($urlParams['brandId'] == 0) ? 'selected' : '' ?> value="0">
                                Производитель
                            </option>
                            <?php foreach ($brandIdsNames as $id => $name) : ?>
                                <option <?= ($urlParams['brandId'] == $id) ? 'selected' : '' ?> value="<?= $id ?>">
                                    <?= Html::encode($name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-outline-primary ml-3">
                            <span class="icon-search"></span>
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped table-sm table-bordered">
                    <?php foreach ($brandCategorys as $brandCategory) : ?>
                        <?php /** @var BrandCategory $brandCategory */ ?>
                        <tr>
                            <td style="width: 50px;" title="Популярность">
                                <?= Html::encode($brandCategory->getViewIdx()->getIdx()) ?>
                            </td>
                            <td style="width: 120px;">
                                <img src="<?= BrandCategoryImgService::getPublicUrlRelative($brandCategory->getLogoFn()) ?>" style="height: 48px; max-width: 100px;">
                            </td>
                            <td>
                                <a href="<?= Url::to(['brandcategory/view', 'id' => $brandCategory->getId()->getId(), 'ufu' => $brandCategory->getUfu()->getUfu(),]); ?>" class="text-dark">
                                    <?= Html::encode($brandCategory->getName()->getName()) ?>
                                </a>
                            </td>
                            <td style="width: 10px;">
                                <a href="<?= Url::to(['brandcategory/u', 'id' => $brandCategory->getId()->getId(),]); ?>" class="text-dark">
                                    <span class="icon-edit"></span>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            <div class="card-footer">
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
    </div>
</div>