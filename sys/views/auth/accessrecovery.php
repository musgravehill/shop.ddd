<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;
use app\models\UserTokenHelper;
//
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Смена пароля';
?>

<div class="row">
    <div class="col-12 offset-md-4 col-md-4">

        <?php
        $form = ActiveForm::begin([
            'options' => [
                'class' => '',
            ],
            'fieldConfig' => [
                'template' => '
                        <div class="mb-3">                                 
                                <b>{label}</b>                             
                                {input}
                                {hint}
                                <div class="text-danger">
                                    {error}
                                </div>
                             
                        </div>',
            ]
        ]);
        ?>

        <div class="card">
            <div class="card-header">
                <h1>Смена пароля</h1>
            </div>
            <div class="card-body">
                <?= $form->field($formAccessRecovery, 'password')->textInput(['autofocus' => true, 'placeholder' => $formAccessRecovery->getAttributeLabel('password'), 'autocomplete' => 'off', 'type' => 'text']) ?>
            </div>
            <div class="card-footer">
                <?= Html::submitButton('Сохранить пароль', ['class' => 'btn btn-success', 'name' => 'button']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>