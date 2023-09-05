<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;

use app\components\Supplier\Domain\Entity\Supplier;


$this->title = 'Поставщики';

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
                <h1 class="d-inline-block">
                    Поставщики
                </h1>
                <input type="text" id="supplier_list__filter_input" placeholder="Поиск..." class="form-control d-inline-block" style="width: 200px;">

                <a href="<?= Url::to(['supplier/cr']); ?>" class="btn btn-outline-success ml-3">
                    создать
                </a>
            </div>
            <div class="card-body">
                <table class="table table-sm table-bordered">

                    <?php foreach ($suppliers as $supplier) : ?>
                        <?php /** @var null|Supplier $supplier */ ?>
                        <tr>
                            <td>
                                <a href="<?= Url::to(['supplier/view', 'id' => $supplier->getId()->getId()]); ?>" class="">
                                    <img src="<?= Html::encode(HelperY::getRelativeUrl($supplier->getImgUrl()->getImgUrl())) ?>" style="height: 48px;" class="">
                                </a>
                            </td>
                            <td>
                                <a href="<?= Url::to(['supplier/view', 'id' => $supplier->getId()->getId()]); ?>" class="helper-no-decor text-dark helper-font-18 font-weight-lighter">
                                    <?= Html::encode($supplier->getName()->getName()) ?>
                                </a>
                            </td>
                            <td>
                                <?= Html::encode($supplier->getCityName()->getCityName()) ?>
                            </td>
                            <td style="width: 10px;">
                                <a href="<?= Url::to(['supplier/u', 'id' => $supplier->getId()->getId()]); ?>" class="helper-no-decor text-dark helper-font-14 font-weight-lighter">
                                    <span class="icon-edit"></span>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        supplier_list__filter();
    });

    function supplier_list__filter() {
        document.getElementById("supplier_list__filter_input").addEventListener('keyup', (e) => {
            const q = e.currentTarget.value.toLowerCase();
            const bs = Array.from(document.querySelectorAll('div.supplier_list__item_container'));
            for (b of bs) {
                const name = b.dataset.name.toLowerCase();
                if (name.indexOf(q) !== -1) {
                    b.style.display = "block";
                } else {
                    b.style.display = "none";
                }
            }
        });
    }
</script>