<?php

use app\components\Brand\Infrastructure\BrandRepository;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;

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
            <div class="card-header">
                <h1>Админ</h1>
            </div>
            <div class="card-body">
                <a href="<?= Url::toRoute(['user/list_adm']) ?>" class="text-dark">
                    Клиенты
                </a>
                <br>

                <a href="<?= Url::toRoute(['supplier/list_adm']) ?>" class="text-dark">
                    Поставщики
                </a>
                <br>

                <a href="<?= Url::toRoute(['imgsys/list_adm']) ?>" class="text-dark">
                    Картинки
                </a>
                <br>

                <a href="<?= Url::toRoute(['brand/list_adm']) ?>" class="text-dark">
                    Производители
                </a>
                <br>

                <a href="<?= Url::toRoute(['brandcategory/list_adm']) ?>" class="text-dark">
                    Категории брендов
                </a>
                <br>

                <a href="<?= Url::toRoute(['page/list_adm']) ?>" class="text-dark">
                    Страницы
                </a>
                <br>
                <a href="<?= Url::toRoute(['salepersonalbrandcategory/list_adm']) ?>" class="text-dark">
                    Скидки персональные
                </a>
                <br>
                <a href="<?= Url::toRoute(['order/list_adm']) ?>" class="text-dark">
                    Заказы
                </a>
                <br>
                <a href="<?= Url::toRoute(['product/list_adm']) ?>" class="text-dark">
                    Товары 
                </a>
                <br>
                <a href="<?= Url::toRoute(['cart/abandoned']) ?>" class="text-dark">
                    Корзины  
                </a>
                <br>
                


            </div>
        </div>
    </div>
</div>