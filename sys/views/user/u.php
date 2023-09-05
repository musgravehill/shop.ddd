<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;
//
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Данные';
?>

<div class="row">
    <div class="col-12">
        <?php
        $form = ActiveForm::begin([
            //'layout' => 'horizontal',
            'options' => [
                'class' => '',
                //'enctype' => 'multipart/form-data', if use Files[]
            ],
            'fieldConfig' => [
                'template' => '
                        <div class="row">
                            <div class="col-3">
                                <b>{label}</b>
                            </div>
                            <div class="col-9">
                                {input}
                                {hint}
                                <div class="text-danger">
                                    {error}
                                </div>
                            </div>
                        </div>', //{beginWrapper} {endWrapper}
                /* 'horizontalCssClasses' => [
                      'label' => 'col-sm-4',
                      'offset' => 'zcol-sm-offset-4',
                      'wrapper' => 'col-sm-4',
                      'error' => 'text-danger',
                      'hint' => 'text-danger'
                      ] */
            ]
        ]);
        ?>
        <div class="card">
            <div class="card-header">
                <h1 class="d-inline-block">
                    Данные
                </h1>
                <a href="<?= Url::to(['auth/accessrecoveryinit']) ?>" class="btn btn-outline-secondary float-right btn-sm">Сменить пароль</a>
            </div>
            <div class="card-body">
                <?= $form->field($formUserUpdate, 'username')->textInput([
                    'autofocus' => true,
                    'placeholder' => $formUserUpdate->getAttributeLabel('username'),
                ]) ?>
                <?= $form->field($formUserUpdate, 'email')->textInput([
                    'autofocus' => true,
                    'placeholder' => $formUserUpdate->getAttributeLabel('email'),
                    'disabled' => true,
                ]) ?>
                <?= $form->field($formUserUpdate, 'phone')->textInput([
                    'autofocus' => true,
                    'placeholder' => $formUserUpdate->getAttributeLabel('phone'),
                    'data-component' => 'mobilePhone',
                    'autocomplete' => 'off',
                ]) ?>

                <?= $form->field($formUserUpdate, 'cityName')->textInput([
                    'autofocus' => true,
                    'placeholder' => $formUserUpdate->getAttributeLabel('cityName'),
                    'autocomplete' => 'off',
                ]) ?>
                <?= $form->field($formUserUpdate, 'address')->textInput([
                    'autofocus' => true,
                    'placeholder' => $formUserUpdate->getAttributeLabel('address'),
                    'autocomplete' => 'off',
                ]) ?>

                <?= '' /*
          $form->field($formUserUpdate, 'verifyCode')->widget(Captcha::className(), [
          'template' => "{image}{input}",
          'options' => [//HTML attributes for the input tag
          'class' => 'form-control',
          'style' => 'width: 160px; display: inline;',
          'placeholder' => 'Введите символы',
          'autocomplete' => 'false',
          ],
          'imageOptions' => [
          'title' => 'Клик, чтобы сменить буквы',
          'style' => 'cursor:pointer;',
          //'class' => '',
          ],
          'captchaAction' => 'site/captcha',
          ]); //->label('Введите код');  label set by model
         */
                ?>

            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-12 m-0">
                        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success', 'name' => 'save-button']) ?>
                    </div>
                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>

<div class="row mt-5">
    <div class="col-12">
        <?php
        $form = ActiveForm::begin([
            //'layout' => 'horizontal',
            'options' => [
                'class' => '',
                //'enctype' => 'multipart/form-data', if use Files[]
            ],
            'fieldConfig' => [
                'template' => '
                        <div class="row">
                            <div class="col-3">
                                <b>{label}</b>
                            </div>
                            <div class="col-9">
                                {input}
                                {hint}
                                <div class="text-danger">
                                    {error}
                                </div>
                            </div>
                        </div>', //{beginWrapper} {endWrapper}
                /* 'horizontalCssClasses' => [
                      'label' => 'col-sm-4',
                      'offset' => 'zcol-sm-offset-4',
                      'wrapper' => 'col-sm-4',
                      'error' => 'text-danger',
                      'hint' => 'text-danger'
                      ] */
            ]
        ]);
        ?>
        <div class="card">
            <div class="card-header">
                <h1 class="d-inline-block">Компания</h1>
            </div>
            <div class="card-body">
                <?= $form->field($formUserCompanyCru, 'name')->textInput(['autofocus' => true, 'placeholder' => '',]) ?>
                <?= $form->field($formUserCompanyCru, 'inn')->textInput(['autofocus' => true, 'placeholder' => '',]) ?>
                <?= $form->field($formUserCompanyCru, 'kpp')->textInput(['autofocus' => true, 'placeholder' => '',]) ?>
                <?= $form->field($formUserCompanyCru, 'rs')->textInput(['autofocus' => true, 'placeholder' => '',]) ?>
                <?= $form->field($formUserCompanyCru, 'bik')->textInput(['autofocus' => true, 'placeholder' => '',]) ?>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-12 m-0">
                        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success', 'name' => 'save-button']) ?>
                    </div>
                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>