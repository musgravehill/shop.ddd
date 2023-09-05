<?php

use app\components\Brand\Domain\Entity\Brand;
use app\components\Brand\Infrastructure\BrandImgService;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;
//
use yii\widgets\ActiveForm;

/** @var Brand $brand */

$this->title = 'Производитель';
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
                        Новый бренд
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
                <?= $form->field($formBrandCr, 'externalId')->textInput(['autofocus' => true, 'autocomplete' => 'off', 'placeholder' => $formBrandCr->getAttributeLabel('externalId'),]) ?>
                <?= $form->field($formBrandCr, 'name')->textInput(['autofocus' => true, 'autocomplete' => 'off', 'placeholder' => $formBrandCr->getAttributeLabel('name'),]) ?>
                <?= $form->field($formBrandCr, 'dsc')->textInput(['autofocus' => true, 'autocomplete' => 'off', 'placeholder' => $formBrandCr->getAttributeLabel('dsc'),]) ?>
                <?= $form->field($formBrandCr, 'imageFile')->fileInput(['multiple' => false, 'accept' => 'image/*']) ?>
                <?= Html::submitButton('Ok', ['class' => 'btn btn-success',]) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>