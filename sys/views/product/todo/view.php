<?php

use yii\helpers\Html;
use app\components\HelperY;
use yii\helpers\Url;
use app\models\ProductHelper;
use app\models\SaleHelper;

$cities_raws = \app\models\CompanyHelper::getCities($c['id']);
$cities = [];
foreach ($cities_raws as $cities_raw) {
    $cities[] = $cities_raw['geo_city'];
}

$this->title = Html::encode(HelperY::sanitizeWDS($p->name . ' ' . implode(', ', $cities) . ' ' . $c->name));

\Yii::$app->view->registerMetaTag([
    'name' => 'keywords',
    'content' => $this->title
]);
\Yii::$app->view->registerMetaTag([
    'name' => 'description',
    'content' => $this->title
]);
?>


<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h1>
                    <?= Html::encode($p->name) ?>
                </h1>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-4 mb-3">
                        <h2>
                            <?php if (isset($companyAddresses[0])) : ?>
                                <div class="helper-font-16 text-dark">
                                    <?= $companyAddresses[0]['geo_city'] ?>
                                    /
                                    <?= date('d.m.Y') ?>
                                </div>
                            <?php endif; ?>
                        </h2>
                        <?=
                        $this->context->renderPartial('/product/_view_carousel_img', [
                            'p' => $p,
                        ]);
                        ?>
                    </div>
                    <div class="col-12 col-md-8">
                        <div class="d-block">
                            <?php if ($p->price > 0) : ?>
                                <span class="helper-font-bold helper-font-22">
                                    <span numeral="my10k"><?= (float) $p['price'] ?></span> р.
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="d-block mt-1">
                            <?php if (((int) $p['type_id'] === (int) ProductHelper::TYPE_PRODUCT || (int) $p['type_id'] === (int) ProductHelper::TYPE_BZN) && !$c['sys_is_hidden']) : ?>
                                <div class="d-block mt-3">
                                    <?=
                                    $this->context->renderPartial('/cart/_common_cart_btn', [
                                        'productId' => $p['id'],
                                        'btn_submit_title' => 'В корзину',                                         
                                        'btn_class' => 'btn btn-danger',
                                    ]);
                                    ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="d-block mt-3">
                            <?= $p->dsc ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div itemscope itemtype="http://schema.org/Product" class="d-none">
    <div itemprop="name"><?= Html::encode($p->name) ?></div>
    <?php if ($p->photo_url_1) : ?>
        <a itemprop="image" href="<?= Html::encode($p->photo_url_1) ?>">
            <img src="<?= Html::encode($p->photo_url_1) ?>" />
        </a>
    <?php endif; ?>
    <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
        <meta itemprop="price" content="<?= Html::encode($p->price) ?>">
        <meta itemprop="priceCurrency" content="RUB">
        <div>В наличии</div>
        <link itemprop="availability" href="http://schema.org/InStock">
    </div>
    <div itemprop="description"><?= HelperY::sanitizeText($p->dsc) ?></div>
</div>

 