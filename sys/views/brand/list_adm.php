<?php

use app\components\Brand\Domain\Entity\Brand;
use app\components\Brand\Infrastructure\BrandImgService;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;

$this->title = 'Производители';

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
                <h1 class="d-inline-block">Производители</h1>
                <a href="<?= Url::to(['brand/cr']); ?>" class="btn btn-outline-success ml-3">
                    создать
                </a>
            </div>
            <div class="card-body">
                <table class="table table-striped table-sm table-bordered">
                    <?php foreach ($brands as $brand) : ?>
                        <?php /** @var Brand $brand */ ?>
                        <tr>
                            <td style="width: 50px;" title="Популярность">
                                <?= Html::encode($brand->getViewIdx()->getIdx()) ?>
                            </td>
                            <td style="width: 120px;">
                                <img src="<?= BrandImgService::getPublicUrlRelative($brand->getLogoFn()) ?>" style="height: 48px; max-width: 100px;">
                            </td>
                            <td>
                                <a href="<?= Url::to(['brand/view', 'id' => $brand->getId()->getId(), 'ufu' => $brand->getUfu()->getUfu()]); ?>" class="text-dark">
                                    <?= Html::encode($brand->getName()->getName()) ?>
                                </a>
                            </td>
                            <td style="width: 10px;">
                                <a href="<?= Url::to(['brand/u', 'id' => $brand->getId()->getId(),]); ?>" class="text-dark">
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