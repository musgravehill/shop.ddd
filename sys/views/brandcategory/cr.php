<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;
//
use yii\widgets\ActiveForm;

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
                        </a>
                        / Новая категория
                    </h1>
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
                $form->field($formBrandCategoryCr, 'brandId')->dropDownList($brandIdsNames, [
                    'separator' => ' ',
                    'itemOptions' => ['class' => ' '],
                ]); //->label('')
                ?>

                <?= $form->field($formBrandCategoryCr, 'name')->textInput(['autofocus' => true, 'autocomplete' => 'off', 'placeholder' => $formBrandCategoryCr->getAttributeLabel('name'),]) ?>
                <?= $form->field($formBrandCategoryCr, 'dsc')->textInput(['autofocus' => true, 'autocomplete' => 'off', 'placeholder' => $formBrandCategoryCr->getAttributeLabel('dsc'),]) ?>
                <?= $form->field($formBrandCategoryCr, 'searchQuery')->textInput(['autofocus' => true, 'autocomplete' => 'off', 'placeholder' => $formBrandCategoryCr->getAttributeLabel('searchQuery'),]) ?>
                <?= $form->field($formBrandCategoryCr, 'searchPriceMin')->textInput(['autofocus' => true, 'autocomplete' => 'off', 'placeholder' => $formBrandCategoryCr->getAttributeLabel('searchPriceMin'),]) ?>
                <?= $form->field($formBrandCategoryCr, 'searchPriceMax')->textInput(['autofocus' => true, 'autocomplete' => 'off', 'placeholder' => $formBrandCategoryCr->getAttributeLabel('searchPriceMax'),]) ?>
                <?= $form->field($formBrandCategoryCr, 'searchOffers')->textInput(['autofocus' => true, 'autocomplete' => 'off', 'placeholder' => $formBrandCategoryCr->getAttributeLabel('searchOffers'),]) ?>

                <?= $form->field($formBrandCategoryCr, 'imageFile')->fileInput(['multiple' => false, 'accept' => 'image/*']) ?>

                <?= Html::submitButton('Ok', ['class' => 'btn btn-success',]) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>