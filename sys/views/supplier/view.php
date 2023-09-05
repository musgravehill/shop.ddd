<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;
//
use app\components\Supplier\Domain\Entity\Supplier;

/** @var null|Supplier $supplier */

$this->title = $supplier->getName()->getName();
\Yii::$app->view->registerMetaTag([
    'name' => 'keywords',
    'content' => $this->title
]);
\Yii::$app->view->registerMetaTag([
    'name' => 'description',
    'content' => $this->title
]);
?>
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <img src="<?= Html::encode(HelperY::getRelativeUrl($supplier->getImgUrl()->getImgUrl())) ?>" style="height: 48px;" class="mr-2">
                <h1 class="d-inline-block">
                    <?= Html::encode($supplier->getName()->getName()) ?>
                </h1>
                <a href="<?= Url::to(['supplier/list']); ?>" class="text-dark float-right helper-font-20 mt-1 helper-font-bold helper-underline">
                    Поставщики
                </a>
            </div>
            <div class="card-body">
                <?= Html::encode($supplier->getDsc()->getDsc()) ?>
            </div>
        </div>
    </div>
</div>