<?php

use app\components\Delivery\Domain\ValueObject\DeliveryTypeId;
use yii\helpers\Html;
use app\components\HelperY;
use app\components\Product\Domain\Entity\Product;
use yii\helpers\Url;

$this->title = 'Корзина';

\Yii::$app->view->registerMetaTag([
    'name' => 'keywords',
    'content' => 'СпецДилер - агрегатор компаний и скидок'
]);
\Yii::$app->view->registerMetaTag([
    'name' => 'description',
    'content' => 'СпецДилер - агрегатор компаний и скидок'
]);



?>

<?= Html::beginForm([Url::toRoute('order/place')], 'POST'); ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h1>
                    Корзина
                </h1>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <?php foreach ($cartItemsData as $item) : ?>
                        <?php
                        /** @var Product $product */
                        $product = $item['product'];
                        $imgUrls = $item['imgUrls'];
                        ?>
                        <tr data-component="cart" data-purpose="item" data-product-id="<?= $product->getId()->getId() ?>">
                            <td class="align-middle pl-3">
                                <div class="d-block">
                                    <a href="<?= Url::to(['product/view', 'id' => $product->getId()->getId(), 'ufu' => $product->getUfu()->getUfu()]); ?>" class="text-dark">
                                        <b><?= Html::encode($product->getName()->getName()) ?></b>
                                    </a>
                                </div>
                                <div class="d-block mt-1">
                                    <div class="d-inline-block">
                                        <?=
                                        $this->context->renderPartial('/cart/_common_cart_btn', [
                                            'productId' => $product->getId()->getId(),
                                            'btn_submit_title' => '',
                                            'btn_class' => 'btn btn-outline-success btn-sm',
                                        ]);
                                        ?>
                                    </div>
                                    <div class="d-inline-block ml-2 text-secondary">
                                        <span data-component="cart" data-purpose="priceInitial" data-product-id="<?= $product->getId()->getId() ?>">
                                        </span>
                                        <span data-component="cart" data-purpose="priceFinal" data-product-id="<?= $product->getId()->getId() ?>">
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td style="width: 100px;">
                                <div class="d-flex justify-content-center" style="height: 48px;">
                                    <img class="img-responsive" style="max-height: 48px; max-width: 100px;" src="<?= Html::encode($imgUrls[0]) ?>" alt="" />
                                </div>
                                <div class="d-flex justify-content-center">
                                    <div data-component="cart" data-purpose="controlBtn" data-action="del" data-todo="1" data-product-id="<?= (int) $product->getId()->getId() ?>">
                                        <span title="Удалить" class="icon-circledelete text-muted helper-cursor-pointer helper-font-16"></span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <hr>
                <?php foreach (DeliveryTypeId::cases() as $delivery) : ?>
                    <span class="mr-4">
                        <input name="deliveryTypeId" value="<?= $delivery->value ?>" type="radio">
                        <span data-component="cart" data-purpose="deliveryName" data-todo="1" data-delivery-type-id="<?= $delivery->value ?>"></span>
                    </span>
                <?php endforeach; ?>
                <input name="cityName" placeholder="Город доставки" type="text" class="form-control mt-2">
                <input name="comment" placeholder="Комментарий" type="text" class="form-control mt-4">
            </div>
            <div class="card-footer">
                <div>
                    <span class="helper-font-30">
                        Итого: <span cart-count-total=""></span> шт., <span cart-price-total=""></span> р.
                    </span>
                </div>
                <div class="mt-2">
                    <?php if (is_null($user)) : ?>
                        <a href="<?= Url::toRoute('auth/in') ?>" class="btn btn-success btn-lg">
                            Оплатить
                        </a>
                    <?php else : ?>
                        <?php if ($canUserPlaceOrder) : ?>
                            <button type="submit" class="btn btn-success btn-lg">Оплатить</button>
                        <?php else : ?>
                            <div class="alert-danger p-3">
                                Для оформления заказа заполните свой профиль, пожалуйста.
                                <a class="helper-underline" href="<?= Url::toRoute(['user/u', 'id' => $user->getId()->getId(),]) ?>" style="color:#2E406B;">
                                    Заполнить.
                                </a>
                            </div>
                        <?php endif; ?>
                </div>
            <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= Html::endForm(); ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementsByName('deliveryTypeId')[0].checked = "checked";
    });
</script>