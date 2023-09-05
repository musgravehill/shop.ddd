<?php

use yii\helpers\Html;
use app\components\HelperY;
use app\components\SalePersonalBrandCategory\Domain\Entity\SalePersonalBrandCategory;
use yii\helpers\Url;
// 
$this->title = 'Персональные скидки';

\Yii::$app->view->registerMetaTag([
    'name' => 'keywords',
    'content' => 'СпецДилер - агрегатор компаний и скидок ',
]);
\Yii::$app->view->registerMetaTag([
    'name' => 'description',
    'content' => 'СпецДилер - агрегатор компаний и скидок ',
]);
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h1>
                    Персональные скидки
                </h1>
                <div class="d-block mt-3">
                    <form class="form-inline" method="GET" action="">
                        <a href="<?= Url::to(['salepersonalbrandcategory/my']); ?>" class="btn btn-outline-secondary mr-3">
                            <span class="icon-home"></span>
                        </a>
                        <select name="brandId" class="form-control mr-1">
                            <option <?= ($urlParams['brandId'] == 0) ? 'selected' : '' ?> value="0">
                                Производитель
                            </option>
                            <?php foreach ($brandIdsNames as $id => $name) : ?>
                                <option <?= ($urlParams['brandId'] == $id) ? 'selected' : '' ?> value="<?= $id ?>">
                                    <?= Html::encode($name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-outline-primary ml-3">
                            <span class="icon-search"></span>
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped table-sm table-bordered mt-4">
                    <tr>
                        <th>Производитель</th>
                        <th>Категория</th>
                        <th>% скидки</th>
                    </tr>
                    <?php foreach ($items as $item) : ?>
                        <?php /** @var SalePersonalBrandCategory $salePersonalBrandCategory */
                        $salePersonalBrandCategory = $item['salePersonalBrandCategory'];
                        ?>
                        <tr>
                            <td>
                                <a href="<?= Url::to(['brand/view', 'id' => $salePersonalBrandCategory->getBrandId()->getId(), 'ufu' => $item['brandUfu']]); ?>" class="text-dark">
                                    <?= Html::encode($item['brandName']) ?>
                                </a>
                            </td>
                            <td>
                                <a href="<?= Url::to(['brandcategory/view', 'id' => $salePersonalBrandCategory->getBrandCategoryId()->getId(), 'ufu' => $item['brandCategoryUfu'],]); ?>" class="text-dark">
                                    <?= Html::encode($item['brandCategoryName']) ?>
                                </a>
                            </td>
                            <td>
                                <?= Html::encode($salePersonalBrandCategory->getSalePercent()->getSalePercent()) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</div>