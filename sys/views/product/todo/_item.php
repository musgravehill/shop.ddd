<?php

use yii\helpers\Html;
use app\components\HelperY;
use yii\helpers\Url;
?>

<div class="col-6 col-md-3 col-lg-3">
    <div class="common_list__item">
        <a href="<?= Url::to(['product/view', 'id' => $item['id'],]); ?>" class="helper-no-decor text-dark helper-font-14">
            <div class="common_list__item_img_container">
                <img src="<?= Html::encode($item['photo_url_1']) ?>" alt="<?= Html::encode($item['name']) ?>" class="common_list__item_img">
            </div>
            <div class="common_list__item_ttl" style="height: 6.5em;">
                <?= Html::encode($item['name']) ?>
            </div>
        </a>
    </div>
</div>