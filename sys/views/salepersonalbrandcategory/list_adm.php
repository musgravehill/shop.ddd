<?php

use yii\helpers\Html;
use app\components\HelperY;
use app\components\SalePersonalBrandCategory\Domain\Entity\SalePersonalBrandCategory;
use yii\helpers\Url;
// 
$this->title = 'Персональные скидки';

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
                <h1>
                    Персональные скидки
                    <a href="<?= Url::to(['salepersonalbrandcategory/cru', 'id' => 0]) ?>" class="btn btn-success float-right">
                        Создать
                    </a>
                </h1>
                <div class="d-block mt-3">
                    <form class="form-inline" method="GET" action="">
                        <a href="<?= Url::to(['salepersonalbrandcategory/list_adm']); ?>" class="btn btn-outline-secondary mr-3">
                            <span class="icon-home"></span>
                        </a>
                        <input value="<?= Html::encode($urlParams['userId']) ?>" type="text" name="userId" class="form-control mr-1" placeholder="userId">
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
                <table class="table table-striped table-sm table-bordered mt-4">
                    <tr>
                        <th>Клиент</th>
                        <th>Производитель</th>
                        <th>Категория</th>
                        <th>% скидки</th>
                        <th style="width: 10px;"></th>
                    </tr>
                    <?php foreach ($items as $item) : ?>
                        <?php /** @var SalePersonalBrandCategory $salePersonalBrandCategory */
                        $salePersonalBrandCategory = $item['salePersonalBrandCategory'];
                        //   $item['brandUfu']
                        //   $item['brandCategoryUfu']
                        ?>
                        <tr>
                            <td>
                                <a href="<?= Url::to(['user/profile', 'id' => $salePersonalBrandCategory->getUserId()->getId(),]) ?>" class="text-dark">
                                    <?= Html::encode($item['customerUsername'] . ' '  .
                                        $item['customerEmail'] . ' '  .
                                        $item['customerPhone']) ?>
                                </a>
                            </td>
                            <td>
                                <a href="<?= Url::to(['brand/view', 'id' => $salePersonalBrandCategory->getBrandId()->getId(), 'ufu' => $item['brandUfu']]); ?>" class="text-dark">
                                    <?= Html::encode($item['brandName']) ?>
                                </a>
                            </td>
                            <td>
                                <a href="<?= Url::to(['brandcategory/view', 'id' => $salePersonalBrandCategory->getBrandCategoryId()->getId(), 'ufu' => $item['brandCategoryUfu'],]); ?>" class="text-dark">
                                    <?= Html::encode($item['brandCategoryName']) ?>
                                </a>
                            </td>
                            <td>
                                <?= Html::encode($salePersonalBrandCategory->getSalePercent()->getSalePercent()) ?>
                            </td>
                            <td>
                                <a href="<?= Url::to(['salepersonalbrandcategory/cru', 'id' => $salePersonalBrandCategory->getSalePersonalBrandCategoryId()->getId(),]) ?>" class="helper-font-30">
                                    <span class="icon-edit helper-font-14 text-dark"></span>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 mb-5 mt-1">
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