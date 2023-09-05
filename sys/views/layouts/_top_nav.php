<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;
use app\components\IAA\Authentication\Service\IdentityService;

?>

<nav class="navbar navbar-expand-md navbar-light helper-box-shadow-bottom mb-2">
    <div class="container">
        <div class="d-flex mr-2" style="height: 38px;">
            <a href="/" class="helper-no-decor">
                <img src="/img/nav-logo-38.jpg" alt="" style="height: 38px;" />
            </a>
        </div>
        <div class="d-flex mr-auto" style="height: 38px; width: 130px; line-height: 14px;">
            <a href="/" class="helper-no-decor">
                <span class="helper-font-17 helper-font-bold text-danger">Безналом</span>
                <span class="helper-font-12 text-dark">Проф.маркетплейс</span>
            </a>
        </div>
        <div class="d-none d-lg-flex mr-auto p-0 " style="height: 38px;">
            <span class="helper-font-30 text-dark">Маркетплейс для организаций</span>
        </div>
        <div class="d-flex">
            <span data-toggle="modal" data-target="#modalMessage" title="Отправить сообщение" class="mr-3 helper-no-decor d-inline-block helper-cursor-pointer">
                <span class="icon-envelope"></span>
            </span>
            <a href="<?= Url::toRoute(['page/view', 'id' => 7,]) ?>" class="mr-3 helper-no-decor d-inline-block; helper-font-bold" style="color:#2E406B;">
                <span class="icon-phonealt"></span>
            </a>
            <a href="<?= Url::toRoute('cart/index') ?>" class="mr-3 helper-no-decor d-inline-block" style="color:#2E406B;">
                <span cart__top_nav_count_total class="badge badge-secondary" style="vertical-align:text-top;padding:0.1em 0.25em;background-color:#ff8d00; display: none;">
                </span>
                <span cart__top_nav_icon class="icon-shopping-cart helper-font-20 helper-font-bold" style="display: none;">
                </span>
            </a>
            <?php if (!IdentityService::userIsGuest()) : ?>
                <?php if (IdentityService::userIsAdmin()) : ?>
                    <a title="Admin" class="helper-no-decor helper-font-20 d-inline-block mr-1" href="<?= Url::toRoute('adm/index') ?>" style="color:#2E406B;">
                        <span class="icon-websitebuilder"></span>
                    </a>
                <?php endif; ?>
                <a nav_top__my_profile title="Данные" class="helper-no-decor helper-font-20 d-inline-block mr-1" href="<?= Url::toRoute('user/settings') ?>" style="color:#2E406B;">
                    <span class="icon-home"></span>
                </a>
            <?php else : ?>
                <a href="<?= Url::toRoute('auth/in') ?>" style="margin-top: 5px;color:#2E406B;" class="ml-2 helper-cursor-pointer helper-font-16 helper-font-bold d-inline-block">
                    Войти
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>
