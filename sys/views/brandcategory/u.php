<?php

use app\components\BrandCategory\Domain\Entity\BrandCategory;
use app\components\BrandCategory\Infrastructure\BrandCategoryImgService;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;
//
use yii\widgets\ActiveForm;

/** @var BrandCategory $brandCategory */

$this->title = 'Категория';
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center p-0 m-0">
                    <h1 class="d-inline-block">
                        <a href="<?= Url::to(['brand/list']); ?>" class="text-dark">
                            Производители
                        </a> /
                        <a href="<?= Url::to(['brand/view', 'id' => $brand->getId()->getId(), 'ufu' => $brand->getUfu()->getUfu()]); ?>" class="text-dark">
                            <?= Html::encode($brand->getName()->getName()) ?>
                        </a> /
                        <?= Html::encode($brandCategory->getName()->getName()) ?>
                    </h1>
                    <img src="<?= BrandCategoryImgService::getPublicUrlRelative($brandCategory->getLogoFn()) ?>" style="height: 48px; max-width: 100px;">
                </div>
            </div>
            <div class="card-body">
                <?php
                $form = ActiveForm::begin([
                    'options' => [
                        'class' => '',
                        'enctype' => 'multipart/form-data',
                    ],
                    'fieldConfig' => [
                        'template' => '    
                            <div class="row">
                                <div class="col-4">    
                                    <b>{label}</b>   
                                </div>                   
                                <div class="col-8">
                                    {input}
                                    {hint}
                                    <div class="text-danger">
                                        {error}
                                    </div>                            
                                </div>
                            </div>   
                                ',
                        'options' => [
                            'class' => 'form-group',
                        ],
                    ]
                ]);
                ?>

                <?=
                $form->field($formBrandCategoryU, 'brandId')->dropDownList($brandIdsNames, [
                    'separator' => ' ',
                    'itemOptions' => ['class' => ' '],
                ]); //->label('')
                ?>

                <?= $form->field($formBrandCategoryU, 'name')->textInput(['autofocus' => true, 'autocomplete' => 'off', 'placeholder' => $formBrandCategoryU->getAttributeLabel('name'),]) ?>
                <?= $form->field($formBrandCategoryU, 'dsc')->textInput(['autofocus' => true, 'autocomplete' => 'off', 'placeholder' => $formBrandCategoryU->getAttributeLabel('dsc'),]) ?>
                <?= $form->field($formBrandCategoryU, 'searchQuery')->textInput(['autofocus' => true, 'autocomplete' => 'off', 'placeholder' => $formBrandCategoryU->getAttributeLabel('searchQuery'),]) ?>
                <?= $form->field($formBrandCategoryU, 'searchPriceMin')->textInput(['autofocus' => true, 'autocomplete' => 'off', 'placeholder' => $formBrandCategoryU->getAttributeLabel('searchPriceMin'),]) ?>
                <?= $form->field($formBrandCategoryU, 'searchPriceMax')->textInput(['autofocus' => true, 'autocomplete' => 'off', 'placeholder' => $formBrandCategoryU->getAttributeLabel('searchPriceMax'),]) ?>
                <?= $form->field($formBrandCategoryU, 'searchOffers')->textInput(['autofocus' => true, 'autocomplete' => 'off', 'placeholder' => $formBrandCategoryU->getAttributeLabel('searchOffers'),]) ?>

                <?= $form->field($formBrandCategoryU, 'imageFile')->fileInput(['multiple' => false, 'accept' => 'image/*']) ?>

                <?= Html::submitButton('Ok', ['class' => 'btn btn-success',]) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>