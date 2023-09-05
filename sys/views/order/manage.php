<?php

use yii\helpers\Html;
use app\components\HelperY;
use yii\helpers\Url;

/** @var \app\components\Order\App\DTO\OrderResponce $order */

$this->title = 'Заказ ' . Html::encode($order->getOrderId());

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
                    Заказ <?= Html::encode($order->getUserFriendlyOrderId()) ?>
                    <span class="float-right">
                        <span moment="DD.MM.YY HH:mm"><?= Html::encode($order->getCreatedAt()->getTimestamp()) ?></span>
                    </span>
                </h1>
            </div>
            <div class="card-body">
                <table class="table table-striped table-sm mt-4">
                    <?php foreach ($items as $item) : ?>
                        <?php /** @var \app\components\Order\App\DTO\OrderItemResponce $item */ ?>
                        <tr>
                            <td>
                                <a href="<?= Url::to(['product/view', 'id' => $item->getProductId(), 'ufu'=>$item->getProductUfu(), ]); ?>" class="text-dark">
                                    <b><?= Html::encode($item->getProductName()) ?></b>
                                </a>
                            </td>
                            <td>
                                <?= round($item->getPriceFinalFractionalCount() / 100, 2) ?></span> р.
                            </td>
                            <td>
                                <?= Html::encode($item->getQuantity()) ?> шт.
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            <div class="card-footer">
                <b>Клиент:</b> 
                <a href="<?= Url::to(['user/profile', 'id' => $order->getUserId(),]) ?>" class="text-dark">
                    <?= Html::encode($order->getCustomerName()) ?>
                    <?= Html::encode($order->getCustomerEmail()) ?>
                    <?= Html::encode($order->getCustomerPhone()) ?>
                </a><br>
                <b>Итого:</b> <?= round($order->getPriceTotalFractionalCount() / 100, 2) ?> р.<br>
                <b>Комментарий:</b> <?= Html::encode($order->getOrderComment()) ?><br>
                <b>Доставка:</b> <?= Html::encode($order->getDeliveryTypeId()) ?>
                <?= Html::encode($order->getDeliveryCityName()) ?>
            </div>
        </div>
    </div>
</div>