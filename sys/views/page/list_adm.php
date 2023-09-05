<?php

use yii\helpers\Html;
use app\components\HelperY;
use yii\helpers\Url;
//
use yii\bootstrap\ActiveForm;
use app\models\UserHelper;

$this->title = 'Страницы';
?>

<div class="row bg-white rounded p-4">
    <div class="col-md-12">
        <h1><?= Html::encode($this->title) ?>
            <a class="btn btn-info" href="<?= Url::toRoute(['page/cru', 'id' => 0]) ?>" style="letter-spacing: 0px;">
                Создать
            </a>
        </h1>
    </div>    
    <div class="col-md-12">
        <table class="table table-bordered table-sm table-striped">
            <tr>
                <th>ID</th>
                <th>Img</th>
                <th>Title</th>
                <th style="width: 10px;"></th>                             
            </tr>            
            <?php foreach ($pages as $page): ?>
                <tr>
                    <td>
                        <?= Html::encode($page->id) ?>
                    </td>
                     <td>
                         <img src="<?= Html::encode($page->imgUrl1) ?>" alt="img" style="height: 32px; max-width: 64px;"> 
                    </td>
                    <td>
                        <a href="<?= Url::toRoute(['page/view', 'id' => $page->id,]) ?>" class="text-dark">
                            <?= Html::encode($page->title) ?>
                        </a>
                    </td>
                    <td>
                        <a href="<?= Url::toRoute(['page/cru', 'id' => $page->id,]) ?>">
                            <span class="icon-edit"></span>
                        </a>
                    </td>                   
                </tr>
            <?php endforeach; ?>
        </table>
        <br>
        <a class="btn btn-info" href="<?= Url::toRoute(['page/cru', 'id' => 0]) ?>">
            Создать
        </a><br>
    </div>
</div>