<?php

use yii\helpers\Html;
use app\components\HelperY;
use yii\helpers\Url;

$name = \app\components\CacheHelper::POPULAR_PRODUCTS;
$params = [];
$cacheId = \app\components\CacheHelper::getId($name, $params);
?>
<?php if ($this->beginCache($cacheId, ['duration' => 3600])) : ?>
    <?php
    $filters = [
        'count_on_page' => 12,
        'page' => 1,
    ];
    $ps = app\models\ProductHelper::getPopular($filters);
    ?>
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="d-inline-block">
                        Популярные товары
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">

                        <?php foreach ($ps as $item) : ?>
                            <?=
                            $this->context->renderPartial('/product/_item', [
                                'item' => $item,
                            ]);
                            ?>
                        <?php endforeach; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->endCache(); ?>
<?php endif; ?>