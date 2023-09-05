<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;
//
use app\components\Supplier\Domain\Entity\Supplier;

/** @var null|Supplier $supplier */

$this->title = 'Поставщик';
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h1 class="d-inline-block">
                    <a href="<?= Url::toRoute(['supplier/view', 'id' => $supplier->getId()->getId(),]) ?>" class="text-dark">
                        <?= Html::encode($supplier->getName()->getName()) ?>
                    </a>
                </h1>
                <a href="<?= Url::to(['supplier/list_adm']); ?>" class="btn btn-outline-secondary float-right btn-sm">
                    Поставщики
                </a>
            </div>
            <div class="card-body">
                <?= Html::beginForm('', 'post') ?>
                <div class="form-group mb-1">
                    <b>Фото</b>
                    <input name="imgUrl" value="<?= $supplier->getImgUrl()->getImgUrl() ?>" required="true" type="text" class="form-control">
                </div>
                <div class="form-group mb-4">
                    <b>Название</b>
                    <input name="name" value="<?= $supplier->getName()->getName() ?>" required="true" type="text" class="form-control">
                </div>
                <div class="form-group mb-4">
                    <b>Город</b>
                    <input name="cityName" value="<?= $supplier->getCityName()->getCityName() ?>" required="true" type="text" class="form-control">
                </div>
                <div class="form-group mb-4">
                    <b>Описание</b>
                    <textarea name="dsc" class="form-control" rows="10"><?= $supplier->getDsc()->getDsc() ?></textarea>
                </div>

                <br>
                <button type="submit" class="btn btn-success">Ok</button>
                <?= Html::endForm() ?>
            </div>
        </div>
    </div>
</div>