<?php

use yii\helpers\Html;
use app\components\HelperY;
use yii\helpers\Url;

$this->title = $page->title;
\Yii::$app->view->registerMetaTag([
    'name' => 'keywords',
    'content' => $page->seoKey
]);
\Yii::$app->view->registerMetaTag([
    'name' => 'description',
    'content' => $page->seoDesc
]);
?>

<div class="row">
    <div class="col">
        <div itemscope itemtype="https://schema.org/Article">
            <link itemprop="mainEntityOfPage" href="<?= Url::toRoute(['page/view', 'id' => $page->id,], true) ?>" />
            <meta itemprop="headline name" content="<?= $page->title ?>">
            <meta itemprop="description" content="<?= $page->seoDesc ?>">            
            <div itemprop="articleBody" class="bg-white rounded p-4">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <?php if (strlen($page->imgUrl1) > 5): ?>
                            <img src="<?= Html::encode($page->imgUrl1) ?>" alt="<?= Html::encode($page->imgAlt1) ?>" class="img-fluid img-thumbnail"> 
                        <?php endif; ?>
                    </div>
                    <div class="col-12 col-md-4">
                        <?php if (strlen($page->imgUrl2) > 5): ?>
                            <img src="<?= Html::encode($page->imgUrl2) ?>" alt="<?= Html::encode($page->imgAlt2) ?>" class="img-fluid img-thumbnail"> 
                        <?php endif; ?>
                    </div>
                    <div class="col-12 col-md-4">
                        <?php if (strlen($page->imgUrl3) > 5): ?>
                            <img src="<?= Html::encode($page->imgUrl3) ?>" alt="<?= Html::encode($page->imgAlt3) ?>" class="img-fluid img-thumbnail"> 
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <?= $page->txt ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>