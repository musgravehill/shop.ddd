<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;
?>

<?php
if ($isLimitMobile) {
    if ($indexNumber == 0) {
        $class = "col-12 col-md-3";
    } else {
        $class = "d-none d-md-block col-12 col-md-3";
    }
} else {
    $class = "col-12 col-md-3";
}
?>

<div class="<?= $class ?>">
    <div class="page__card">
        <a href="<?= Url::toRoute(['page/view', 'id' => $page['id'],]) ?>" class="helper-no-decor text-dark helper-font-14">
            <img src="<?= Html::encode($page['imgUrl1']) ?>" alt="<?= Html::encode($page['imgAlt1']) ?>" class="page__card_img">
            <div class="page__card_label">
                <span class="page__card_label_title">
                    <?= Html::encode($page['title']) ?>
                </span>
                <span class="page__card_label_desc">
                    <?= Html::encode(mb_substr($page['seoKey'], 0, 355, "utf-8")) ?>
                </span>
            </div>
        </a>
    </div>
</div>
