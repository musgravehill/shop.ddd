<?php

use yii\helpers\Html;
use app\components\HelperY;
use yii\helpers\Url;
//
use yii\bootstrap\ActiveForm;
//
use vova07\imperavi\Widget;

$this->title = 'Персональная скидка на бренд';

\Yii::$app->view->registerMetaTag([
    'name' => 'keywords',
    'content' => 'СпецДилер - агрегатор компаний и скидок'
]);
\Yii::$app->view->registerMetaTag([
    'name' => 'description',
    'content' => 'СпецДилер - агрегатор компаний и скидок'
]);
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h1>
                    Персональная скидка на бренд
                </h1>
            </div>
            <div class="card-body">
                <?= Html::beginForm(Url::current(), 'post') ?>
                <div class="row">
                    <div class="col-12 col-md-3">
                        <b>Клиент</b>
                    </div>
                    <div class="col-12 col-md-9">
                        <input value="<?= Html::encode($userIdRawSelected) ?>" type="text" name="userId" class="form-control mr-1" placeholder="userId">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 col-md-3">
                        <b>Производитель</b>
                    </div>
                    <div class="col-12 col-md-9">
                        <?=
                        $this->context->renderPartial('/brand/_brand_brandcategory_select', [                            
                            'brandIdRawSelected' => $brandIdRawSelected,
                            'brandCategoryIdRawSelected' => $brandCategoryIdRawSelected,
                            'brandIdsNames' => $brandIdsNames,
                            'brandCategoryIdsNamesBrands' => $brandCategoryIdsNamesBrands,
                        ]);
                        ?>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 col-md-3">
                        <b>Скидка %</b>
                    </div>
                    <div class="col-12 col-md-9">
                        <input value="<?= Html::encode($salePercentRawSelected) ?>" type="number" step="1" name="salePercent" class="form-control mr-1" placeholder="salePercent">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 col-md-3">

                    </div>
                    <div class="col-12 col-md-9">
                        <button type="submit" class="btn btn-success">Сохранить</button>
                        <?= Html::endForm() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>