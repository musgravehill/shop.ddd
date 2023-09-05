<?php

use yii\helpers\Html;
use app\components\HelperY;

use yii\helpers\Url;
//

$this->title = 'Заказы товаров';

\Yii::$app->view->registerMetaTag([
    'name' => 'keywords',
    'content' => 'СпецДилер - агрегатор компаний и скидок ',
]);
\Yii::$app->view->registerMetaTag([
    'name' => 'description',
    'content' => 'СпецДилер - агрегатор компаний и скидок ',
]);
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h1>
                    Заказы товаров
                </h1>
            </div>
            <div class="card-body">
                <table class="table table-striped table-sm table-bordered">
                    <tr>
                        <th>Заказ</th>
                        <th>Цена</th>
                        <th>Создано</th>
                        <th>Клиент</th>
                    </tr>
                    <?php foreach ($orders as $order) : ?>
                        <?php /** @var \app\components\Order\App\DTO\OrderResponce $order */ ?>
                        <tr>
                            <td>
                                <a href="<?= Url::to(['order/manage', 'id' => $order->getOrderId(),]) ?>" class="text-dark">
                                    <?= Html::encode($order->getUserFriendlyOrderId()) ?>
                                </a>
                            </td>
                            <td>
                                <?= round($order->getPriceTotalFractionalCount() / 100, 2) ?> р.
                            </td>
                            <td>
                                <span class="helper-font-14 text-secondary">
                                    <span moment="DD.MM.YY HH:mm"><?= ($order->getCreatedAt()->getTimestamp()) ?></span>
                                </span>
                            </td>
                            <td>
                                <a href="<?= Url::to(['user/profile', 'id' => $order->getUserId(),]) ?>" class="text-dark">
                                    <?= Html::encode($order->getCustomerName()) ?>
                                    <?= Html::encode($order->getCustomerEmail()) ?>
                                    <?= Html::encode($order->getCustomerPhone()) ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            <div class="card-footer">
                <?php
                $urlParamsTmp = $urlParams;
                $urlParamsTmp['page'] = $urlParamsTmp['page'] - 1;
                ?>
                <?php if ($urlParamsTmp['page'] > 0) : ?>
                    <a href="<?= Url::toRoute($urlParamsTmp) ?>" class="helper-font-30">
                        <span class="btn btn-outline-dark btn-sm">назад</span>
                    </a>
                <?php endif; ?>

                <?php
                $urlParamsTmp = $urlParams;
                $urlParamsTmp['page'] = $urlParamsTmp['page'] + 1;
                ?>
                <a href="<?= Url::toRoute($urlParamsTmp) ?>" class="float-right helper-font-30">
                    <span class="btn btn-outline-dark btn-sm">дальше</span>
                </a>
            </div>
        </div>
    </div>
</div>