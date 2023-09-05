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
                        <a href="<?= Url::to(['brand/view', 'id' => $brand->getId()->getId(), 'ufu' => $brand->getUfu()->getUfu()]); ?>" class="text-dark">
                            <?= Html::encode($brand->getName()->getName()) ?>
                        </a>
                    </h1>
                    <img src="<?= BrandImgService::getPublicUrlRelative($brand->getLogoFn()) ?>" style="height: 48px; max-width: 100px;">
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
                <?= $form->field($formBrandU, 'externalId')->textInput(['autofocus' => true, 'autocomplete' => 'off', 'placeholder' => $formBrandU->getAttributeLabel('externalId'),]) ?>
                <?= $form->field($formBrandU, 'name')->textInput(['autofocus' => true, 'autocomplete' => 'off', 'placeholder' => $formBrandU->getAttributeLabel('name'),]) ?>
                <?= $form->field($formBrandU, 'dsc')->textInput(['autofocus' => true, 'autocomplete' => 'off', 'placeholder' => $formBrandU->getAttributeLabel('dsc'),]) ?>
                <?= $form->field($formBrandU, 'imageFile')->fileInput(['multiple' => false, 'accept' => 'image/*']) ?>
                <?= Html::submitButton('Ok', ['class' => 'btn btn-success',]) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>