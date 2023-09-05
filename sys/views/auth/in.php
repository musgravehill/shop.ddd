<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;
//
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Вход';
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
                <h1>Вход \ регистрация</h1>
            </div>
            <div class="card-body">
                <?= $form->field($formIn, 'email')->textInput(['autofocus' => true, 'placeholder' => $formIn->getAttributeLabel('email'), 'autocomplete' => 'off', 'type' => 'email']) ?>
                <?= $form->field($formIn, 'password')->passwordInput(['placeholder' => $formIn->getAttributeLabel('password'), 'autocomplete' => 'off',]) ?>
            </div>
            <div class="card-footer">
                <?= Html::submitButton('Вход \ регистрация', ['class' => 'btn btn-success', 'name' => 'button']) ?>
                <a href="<?= Url::to(['auth/accessrecoveryinit']) ?>" class="btn btn-outline-secondary float-right">Забыли пароль?</a>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>