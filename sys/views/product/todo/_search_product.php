<?php

use yii\helpers\Html;
use app\components\HelperY;
use yii\helpers\Url;
use app\models\UserHelper;
//
use app\models\ProductHelper;
?>

<div class="col-12 mb-4 products_search__item-container">
    <div class="row">
        <div class="col-12 col-sm-12 col-md-10 col-lg-10 col-xl-10">
            <div class="d-block">
                <a class="products_search__item-title" href="<?= Url::to(['product/view', 'id' => $p['id'],]); ?>"   >
                    <span products_search__item-title><?= Html::encode($p['name']) ?></span>
                </a>
            </div>            
            <div class="d-block">

                <span class="text-muted helper-font-italic" company_id="<?= Html::encode($p['company_id']) ?>" products_search__item-company-cities >

                </span>

                <?php if (!Yii::$app->user->isGuest): ?>
                    <?php if ((int) Yii::$app->user->identity->userEntity->role === (int) UserHelper::ROLE_ADMIN): ?>
                        <a href="<?= Url::to(['product/cru', 'id' => $p['id'], 'company_id' => $p['company_id'],]); ?>" class="helper-font-15 text-dark">
                            <span class="icon-edit"></span>
                        </a>  
                        <span class="text-secondary helper-font-9"><?= round($productSphinx['relevant'], 2) ?></span>
                    <?php endif; ?>
                <?php endif; ?>
            </div>   
            <div class="d-block">
                <div class="products_search__item-desc">
                    <?= Html::encode(strip_tags($p['dsc'])) ?>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-2 col-lg-2 col-xl-2">
            <div class="d-block products_search__item-price mt-2">
                <span numeral="my10k"><?= (float) $p['price'] ?></span> р.                 
            </div>
            <div class="d-block">
                <?=
                $this->context->renderPartial('/cart/_common_cart_btn', [
                    'productId' => $p['id'],
                    'btn_submit_title' => '',                    
                    'btn_class' => 'btn btn-outline-success btn-sm',
                ]);
                ?>                   
            </div>
        </div>
    </div>
</div>


