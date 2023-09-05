<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;
use app\components\IAA\Authentication\Service\IdentityService;

$userIsGuest = IdentityService::userIsGuest();
?>

<div class="modal fade" id="modalMessage" tabindex="-1" role="dialog" aria-labelledby="common_modal__label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close mb-2" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <?= Html::beginForm(['message/send'], 'POST'); ?>
                <?php if ($userIsGuest) : ?>
                    <div class="form-group">
                        <input name="username" placeholder="ФИО" type="text" class="form-control" required="true">
                    </div>
                    <div class="form-group">
                        <input name="email" placeholder="Email" type="email" class="form-control" required="true">
                    </div>
                    <div class="form-group">
                        <input name="phone" data-component="mobilePhone" placeholder="Телефон" type="text" class="form-control" required="true">
                    </div>
                <?php endif; ?>
                <div class="form-group">
                    <textarea name="txt" placeholder="Ваш вопрос" class="form-control" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-success">Отправить</button>
                <?= Html::endForm(); ?>
            </div>
        </div>
    </div>
</div>