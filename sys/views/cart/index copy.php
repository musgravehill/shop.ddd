<?php

use yii\helpers\Html;
use app\components\HelperY;
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
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h1>
                    Корзина
                    <span class="text-secondary">
                        (<span cart-count-total=""></span> шт., <span cart-price-total=""></span> р.)
                    </span>
                </h1>
                <?php if (Yii::$app->user->isGuest) : ?>
                    <span todo common_modal__setUrl="<?= Url::toRoute('auth/in') ?>">
                        <span class="btn btn-danger">Оплатить</span>
                    </span>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <?php foreach ($cart as $item) : ?>
                        <tr cart__product_id="<?= $item['product_id'] ?>">
                            <td class="align-middle pl-3">
                                <div class="d-block">
                                    <a href="<?= Url::to(['product/view', 'id' => $item['product_id'],]); ?>" class="text-dark">
                                        <b><?= Html::encode($item['product']['name']) ?></b>
                                    </a>

                                </div>
                                <div class="d-block mt-1">
                                    <div class="d-inline-block">
                                        <?=
                                        $this->context->renderPartial('/cart/_common_cart_btn', [
                                            'productId' => $item['product']['id'],
                                            'btn_submit_title' => '',                                            
                                            'btn_class' => 'btn btn-outline-success btn-sm',
                                        ]);
                                        ?>
                                        <span common_cart__item_data product_id="<?= $item['product_id'] ?>" product_price="<?= (float) $item['product']['price'] ?>" company_id="<?= Html::encode($item['product']['company_id']) ?>"></span>
                                    </div>
                                    <div class="d-inline-block ml-2 text-secondary">
                                        <span numeral="my10k"><?= (float) $item['product']['price'] ?></span> р.
                                    </div>
                                    <div class="d-inline-block ml-2">
                                        <span data-target="salePersonalBrand_badge" data-brand-id="<?= (int)$item['product']['brand_id'] ?>" data-brand-category-id="<?= (int)$item['product']['brand_category_id'] ?>" class="d-none badge badge-success" title="Ваша персональная скидка"></span>
                                    </div>
                                </div>
                            </td>
                            <td style="width: 100px;">
                                <div class="d-flex justify-content-center" style="height: 48px;">
                                    <img class="img-responsive" style="max-height: 48px; max-width: 100px;" src="<?= Html::encode($item['product']['photo_url_1']) ?>" alt="" />
                                </div>
                                <div class="d-flex justify-content-center">
                                    <span cart__item_delete todo cart__product_id="<?= $item['product_id'] ?>" title="Удалить" class="icon-circledelete text-muted helper-cursor-pointer helper-font-16"></span>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</div>

<?php if (!Yii::$app->user->isGuest) : ?>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h2 class="d-inline-block">
                        Оплата
                    </h2>
                </div>
                <div class="card-body">
                    <div class="d-block">
                        <span data-clienttype-selector data-clienttype-id="<?= ClientTypeEnum::NATURAL_PERSON->value ?>" class="btn mr-3 mb-2">
                            Физ.лицо
                        </span>
                        <span data-clienttype-selector data-clienttype-id="<?= ClientTypeEnum::LEGAL_ENTITY->value ?>" class="btn mr-3 mb-2">
                            Юр.лицо
                        </span>
                    </div>

                    <div data-clienttype-selector-div data-clienttype-id="<?= ClientTypeEnum::NATURAL_PERSON->value ?>">
                        <table class="table table-borderless table-sm mb-0">
                            <tr data-clienttype-field="u_username">
                                <td style="width: 160px">
                                    <b>ФИО</b>
                                </td>
                                <td>
                                    <?= Html::encode($user['username']) ?>
                                </td>
                            </tr>
                            <tr data-clienttype-field="u_email">
                                <td style="width: 160px">
                                    <b>Email</b>
                                </td>
                                <td>
                                    <?= Html::encode($user['email']) ?>
                                </td>
                            </tr>
                            <tr data-clienttype-field="u_phone">
                                <td style="width: 160px">
                                    <b>Телефон</b>
                                </td>
                                <td>
                                    <?= Html::encode($user['phone']) ?>
                                </td>
                            </tr>
                        </table>
                        <a data-clienttype-edit data-clienttype-edit-warning="1" data-clienttype-id="<?= ClientTypeEnum::NATURAL_PERSON->value ?>" class="btn btn-outline-danger" href="<?= Url::toRoute(['user/profile']) ?>">
                            Исправить
                        </a>
                    </div>


                    <div data-clienttype-selector-div data-clienttype-id="<?= ClientTypeEnum::LEGAL_ENTITY->value ?>">
                        <table class="table table-borderless table-sm mb-0">
                            <tr data-clienttype-field="u_username">
                                <td style="width: 160px">
                                    <b>ФИО</b>
                                </td>
                                <td>
                                    <?= Html::encode($user['username']) ?>
                                </td>
                            </tr>
                            <tr data-clienttype-field="u_email">
                                <td style="width: 160px">
                                    <b>Email</b>
                                </td>
                                <td>
                                    <?= Html::encode($user['email']) ?>
                                </td>
                            </tr>
                            <tr data-clienttype-field="u_phone">
                                <td style="width: 160px">
                                    <b>Телефон</b>
                                </td>
                                <td>
                                    <?= Html::encode($user['phone']) ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 160px">
                                    <span data-clienttype-field="c_okopf_id">
                                        <span data-clienttype-field="c_name">
                                            <b>Наименование</b>
                                        </span>
                                    </span>
                                </td>
                                <td>
                                    <span data-clienttype-field="c_okopf_id">
                                        <?= Html::encode(\app\models\CompanyHelper::getOkopfName(($c ? $c['okopf_id'] : 0))) ?>
                                    </span>
                                    <span data-clienttype-field="c_name">
                                        <?= Html::encode($c ? $c['name'] : '') ?>
                                    </span>
                                </td>
                            </tr>
                            <tr data-clienttype-field="c_inn">
                                <td>
                                    <b>
                                        ИНН
                                    </b>
                                </td>
                                <td>
                                    <?= Html::encode($c ? $c['inn'] : '') ?>
                                </td>
                            </tr>
                            <tr data-clienttype-field="c_kpp">
                                <td>
                                    <b>
                                        КПП
                                    </b>
                                </td>
                                <td>
                                    <?= Html::encode($c ? $c['kpp'] : '') ?>
                                </td>
                            </tr>
                            <tr data-clienttype-field="c_bik">
                                <td>
                                    <b>
                                        БИК
                                    </b>
                                </td>
                                <td>
                                    <?= Html::encode($c ? $c['bik'] : '') ?>
                                </td>
                            </tr>
                            <tr data-clienttype-field="c_rs">
                                <td>
                                    <b>
                                        Р/С
                                    </b>
                                </td>
                                <td>
                                    <?= Html::encode($c ? $c['rs'] : '') ?>
                                </td>
                            </tr>
                        </table>
                        <a data-clienttype-edit data-clienttype-edit-warning="1" data-clienttype-id="<?= ClientTypeEnum::LEGAL_ENTITY->value ?>" class="btn btn-outline-danger" href="<?= Url::toRoute(['user/profile']) ?>">
                            Исправить
                        </a>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h2 class="d-inline-block">Доставка</h2>
                </div>
                <div class="card-body">
                    <div class="d-block">
                        <?php foreach ($deliveries as $d) : ?>
                            <span cart-delivery-btn data-delivery-is-city="<?= Html::encode($d['is_city']) ?>" data-delivery-id="<?= Html::encode($d['id']) ?>" class="btn mr-3 mb-2">
                                <?= Html::encode($d['name']) ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                    <div class="d-block">
                        <input id="cart__input_delivery_id" type="hidden">
                        <input id="cart__input_delivery_city" placeholder="Напишите город доставки" title="Напишите город доставки" type="text" class="form-control border border-danger">
                    </div>
                    <div class="d-block mt-4 helper-font-13">
                        <?php foreach ($deliveries as $d) : ?>
                            <span cart-delivery-info data-delivery-id="<?= Html::encode($d['id']) ?>" class="d-none">
                                <?= $d['dsc'] ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h2 class="d-inline-block">Оформление</h2>
                </div>
                <div class="card-body">
                    <div class="d-block">
                        <input id="cart__input_comment" placeholder="Комментарий" title="Комментарий" type="text" class="form-control">
                    </div>
                    <div class="d-block mt-3">
                        <span class="helper-font-20 helper-font-bold text-dark">
                            Итого:
                            <span cart-price-total=""></span> р.
                        </span>
                    </div>
                    <div class="d-block">
                        <span class="helper-font-20 helper-font-bold text-dark">
                            Количество:
                            <span cart-count-total=""></span>
                        </span>
                    </div>
                    <div class="d-block mt-3">
                        <span id="cart__btn_placeOrder" class="btn btn-danger">Заказать</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', () => {
            cart_clientType_selector();
            cart_clientType_errorFields();
            cart_delivery();
            cart_placeOrder();
        });

        let cart_clienttype_id = <?= ClientTypeEnum::NATURAL_PERSON->value ?>;
        let clientTypes = JSON.parse('<?= json_encode($clientTypes) ?>');
        let cart_clienttype_isOk = false;

        function cart_clientType_selector() {
            process();

            const selectors = Array.from(document.querySelectorAll('span[data-clienttype-selector]'));
            for (selector of selectors) {
                selector.addEventListener('click', (e) => {
                    cart_clienttype_id = parseInt(e.currentTarget.dataset.clienttypeId) || 0;
                    process();
                });
            }

            function process() {
                //show selected
                const divs = Array.from(document.querySelectorAll('div[data-clienttype-selector-div]'));
                for (div of divs) {
                    if (cart_clienttype_id == parseInt(div.dataset.clienttypeId)) {
                        div.classList.remove("d-none");
                    } else {
                        div.classList.add("d-none");
                    }
                }
                //render selectors
                const selectors = Array.from(document.querySelectorAll('span[data-clienttype-selector]'));
                for (selector of selectors) {
                    if (cart_clienttype_id == parseInt(selector.dataset.clienttypeId)) {
                        selector.classList.add("btn-success");
                        selector.classList.remove("btn-outline-secondary");
                    } else {
                        selector.classList.remove("btn-success");
                        selector.classList.add("btn-outline-secondary");
                    }
                }
                //cart_clienttype_isOk
                for (const [idx, clientType] of Object.entries(clientTypes)) {
                    //console.log(clientType);
                    if (cart_clienttype_id === clientType.clientType) {
                        cart_clienttype_isOk = clientType.isOk;
                    }
                }
            }
        }

        function cart_clientType_errorFields() {
            for (const [idx, clientType] of Object.entries(clientTypes)) {
                //show edit btns
                const edBtns = Array.from(document.querySelectorAll('[data-clienttype-edit][data-clienttype-id="' + (clientType.clientType) + '"]'));
                for (edBtn of edBtns) {
                    if (clientType.isOk == true) {
                        edBtn.classList.add("d-none");
                        edBtn.dataset.clienttypeEditWarning = 0;
                    } else {
                        edBtn.classList.remove("d-none");
                        edBtn.dataset.clienttypeEditWarning = 1;
                    }
                }
                //danger for client fields
                if (Symbol.iterator in Object(clientType.errorFields)) {
                    for (errorField of clientType.errorFields) {
                        const fieldTags = Array.from(document.querySelectorAll('[data-clienttype-field="' + errorField + '"]'));
                        for (fieldTag of fieldTags) {
                            fieldTag.classList.add("text-danger");
                        }
                    }
                }
            }
        }

        function cart_placeOrder() {
            const btn = document.getElementById('cart__btn_placeOrder');
            btn.addEventListener('click', (e) => {
                if (cart_clienttype_isOk) {
                    btn.style.display = "none";
                    btn.insertAdjacentHTML('afterend', cart__waitingHtml);
                    site__cart_onOrder();
                } else {
                    document.querySelector('[data-clienttype-edit][data-clienttype-edit-warning="1"][data-clienttype-id="' + cart_clienttype_id + '"]').scrollIntoView();
                }
            });

            function site__cart_onOrder() {
                console.log('cart_clienttype_isOk=', cart_clienttype_isOk);
                console.log('cart_clienttype_id=', cart_clienttype_id);

                const payload = {
                    comment_client: document.getElementById('cart__input_comment').value,
                    delivery_id: document.getElementById('cart__input_delivery_id').value,
                    delivery_city: document.getElementById('cart__input_delivery_city').value,
                    clienttype_id: cart_clienttype_id
                };
                $.post(commonData__cartOrder_url, payload).done(function(response) {
                    document.location.href = commonData__cartOrdersClient_url;
                });
            }
        }


        function cart_isNeedToComplete() {
            const clientItems = Array.from(document.querySelectorAll('[data-client-item]'));
            for (clientItem of clientItems) {
                if (parseInt(clientItem.dataset.isNeedToComplete) === 1) {
                    clientItem.classList.add("text-danger");
                }
            }

            const userEditBtn = document.getElementById('cart__user_edit_btn');
            if (userEditBtn) {
                if (parseInt(userEditBtn.dataset.isNeedToComplete) === 1) {
                    userEditBtn.classList.add("btn-outline-danger");
                    userEditBtn.textContent = 'Заполнить';
                } else {
                    userEditBtn.classList.add("btn-outline-secondary");
                    userEditBtn.textContent = 'Изменить';
                }
            }

            const cEditBtn = document.getElementById('cart__c_edit_btn');
            if (cEditBtn) {
                if (parseInt(cEditBtn.dataset.isNeedToComplete) === 1) {
                    cEditBtn.classList.add("btn-outline-danger");
                    cEditBtn.textContent = 'Заполнить';
                } else {
                    cEditBtn.classList.add("btn-outline-secondary");
                    cEditBtn.textContent = 'Изменить';
                }
            }
        }

        function cart_delivery() {
            const cartDeliveryBtns = Array.from(document.querySelectorAll('span[cart-delivery-btn]'));
            const cartDeliveryInfos = Array.from(document.querySelectorAll('span[cart-delivery-info]'));
            for (cartDeliveryBtn of cartDeliveryBtns) {
                cartDeliveryBtn.addEventListener('click', (e) => {
                    const deliveryId = parseInt(e.currentTarget.dataset.deliveryId) || 0;
                    cartDeliveryInfoRender(deliveryId, cartDeliveryBtns, cartDeliveryInfos);
                });
            }
            cartDeliveryInfoRender(parseInt(cartDeliveryBtns[0].dataset.deliveryId), cartDeliveryBtns, cartDeliveryInfos);

            const input_delivery_city = document.getElementById('cart__input_delivery_city');
            input_delivery_city.addEventListener('keyup', (e) => {
                if (e.currentTarget.value.length > 0) {
                    e.currentTarget.classList.remove("border", "border-danger");
                } else {
                    e.currentTarget.classList.add("border", "border-danger");
                }
            });

            function cartDeliveryInfoRender(deliveryId, cartDeliveryBtns, cartDeliveryInfos) {
                const input_delivery_city = document.getElementById('cart__input_delivery_city');

                document.getElementById('cart__input_delivery_id').value = deliveryId;

                for (cartDeliveryBtn of cartDeliveryBtns) {
                    if (parseInt(cartDeliveryBtn.dataset.deliveryId) === deliveryId) {
                        cartDeliveryBtn.classList.add("btn-success");
                        cartDeliveryBtn.classList.remove("btn-outline-secondary");
                        const deliveryIsCity = parseInt(cartDeliveryBtn.dataset.deliveryIsCity) || 0;
                        if (1 === deliveryIsCity) {
                            input_delivery_city.classList.remove("d-none");
                        } else {
                            input_delivery_city.classList.add("d-none");
                        }
                    } else {
                        cartDeliveryBtn.classList.add("btn-outline-secondary");
                        cartDeliveryBtn.classList.remove("btn-success");
                    }
                }
                for (cartDeliveryInfo of cartDeliveryInfos) {
                    if (parseInt(cartDeliveryInfo.dataset.deliveryId) === deliveryId) {
                        cartDeliveryInfo.classList.remove("d-none");
                    } else {
                        cartDeliveryInfo.classList.add("d-none");
                    }
                }
            }
        }
    </script>

<?php endif; ?>

 