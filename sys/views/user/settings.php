<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;

use app\components\User\Domain\Entity\User;

/** @var User $user */

$this->title = 'Настройки';

\Yii::$app->view->registerMetaTag([
    'name' => 'keywords',
    'content' => 'СпецДилер - агрегатор компаний и скидок'
]);
\Yii::$app->view->registerMetaTag([
    'name' => 'description',
    'content' => 'СпецДилер - агрегатор компаний и скидок'
]);
?>
<div class="row mt-2">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h2 class="">
                    <a href="<?= Url::toRoute(['user/u', 'id' => $user->getId()->getId(),]) ?>" class="text-dark">
                        Данные
                    </a>
                </h2>
                <h2 class="">
                    <a href="<?= Url::to(['order/list_client']); ?>" class="text-dark">
                         Заказы
                    </a>
                </h2>
                <h2 class="">
                    <a href="<?= Url::to(['salepersonalbrandcategory/my']); ?>" class="text-dark">
                     Скидки
                    </a>
                </h2>
                <a href="<?= Url::toRoute('auth/logout') ?>" class="btn btn-outline-secondary btn-sm mt-5">
                    Выход
                </a>
            </div>
        </div>
    </div>
</div>
