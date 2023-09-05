<?php

use yii\helpers\Html;
use app\components\HelperY;
use yii\helpers\Url;
//
use yii\bootstrap\ActiveForm;

$this->title = 'Страница';
?>

<div class="row bg-white rounded p-4">
    <div class="col-12">
        <h1>
            <a href="<?= Url::toRoute(['page/list_adm']) ?>" class="text-dark">
                Страницы
            </a>
            >
            <?php if ($page) : ?>
                <a href="<?= Url::toRoute(['page/view', 'id' => $page->id,]) ?>" class="text-dark">
                    <?= Html::encode($page->title) ?>
                </a>
            <?php else : ?>
                Новая
            <?php endif; ?>
        </h1>
    </div>

    <div class="col-12">
        <?= Html::beginForm(['page/cru', 'id' => ($page) ? $page->id : 0,], 'post') ?>
        <div class="form-group mb-4">
            <b>Keywords</b>
            <input name="seoKey" value="<?= ($page) ? $page->seoKey : '' ?>" required="true" type="text" class="form-control" placeholder="Keywords">
        </div>
        <div class="form-group mb-4">
            <b>Description</b>
            <input name="seoDesc" value="<?= ($page) ? $page->seoDesc : '' ?>" required="true" type="text" class="form-control" placeholder="Description">
        </div>


        <div class="form-group mb-1">
            <b>Img url 1</b>
            <input name="imgUrl1" value="<?= ($page) ? $page->imgUrl1 : '' ?>" required="true" type="text" class="form-control" placeholder="Url">
        </div>
        <div class="form-group mb-4">
            <b>Img alt 1</b>
            <input name="imgAlt1" value="<?= ($page) ? $page->imgAlt1 : '' ?>" type="text" class="form-control" placeholder="Alt">
        </div>

        <div class="form-group mb-1">
            <b>Img url 2</b>
            <input name="imgUrl2" value="<?= ($page) ? $page->imgUrl2 : '' ?>" type="text" class="form-control" placeholder="Url">
        </div>
        <div class="form-group mb-4">
            <b>Img alt 2</b>
            <input name="imgAlt2" value="<?= ($page) ? $page->imgAlt2 : '' ?>" type="text" class="form-control" placeholder="Alt">
        </div>

        <div class="form-group mb-1">
            <b>Img url 3</b>
            <input name="imgUrl3" value="<?= ($page) ? $page->imgUrl3 : '' ?>" type="text" class="form-control" placeholder="Url">
        </div>
        <div class="form-group mb-4">
            <b>Img alt 3</b>
            <input name="imgAlt3" value="<?= ($page) ? $page->imgAlt3 : '' ?>" type="text" class="form-control" placeholder="Alt">
        </div>


        <div class="form-group mb-4">
            <b>Title</b>
            <input name="title" value="<?= ($page) ? $page->title : '' ?>" required="true" type="text" class="form-control" placeholder="Title">
        </div>
        <div class="form-group mb-4">
            <b>Body</b>
            <!--textarea name="txt" class="form-control" rows="10" ><?= ($page) ? $page->txt : '' ?></textarea-->
            <?=
            \vova07\imperavi\Widget::widget([
                'name' => 'txt',
                'value' => ($page) ? $page->txt : '',
                'settings' => [
                    'lang' => 'ru',
                    'minHeight' => 200,
                    'imageLink' => true,
                    'buttons' => [
                        'html', 'formatting', 'bold', 'italic', 'deleted',
                        'link', 'alignment', 'horizontalrule',
                    ],
                    'plugins' => [
                        'clips',
                        'fontcolor',
                    ],
                    'clips' => [
                        ['primary', '<span class="badge badge-primary">primary</span>'],
                        ['success', '<span class="badge badge-success">success</span>'],
                        ['danger', '<span class="badge badge-danger">danger</span>'],
                        ['warning', '<span class="badge badge-warning">warning</span>'],
                        ['info', '<span class="badge badge-info">info</span>'],
                        ['dark', '<span class="badge badge-dark">dark</span>'],
                    ],
                ],
            ]);
            ?>
        </div>
        <br>
        <button type="submit" class="btn btn-success">Ok</button>
        <?= Html::endForm() ?>
    </div>
</div>
