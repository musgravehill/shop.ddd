<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;

$this->title = 'Производители';

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
                <h1 class="d-inline-block">Производители</h1>
                <input type="text" id="brand_list__filter_input" placeholder="Поиск..." class="form-control d-inline-block" style="width: 200px;">
            </div>
            <div class="card-body">
                <div class="row">
                    <?php foreach ($brands as $brand) : ?>
                        <?=
                        $this->context->renderPartial('/brand/_item', [
                            'brand' => $brand,
                        ]);
                        ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        brand_list__filter();
    });

    function brand_list__filter() {
        document.getElementById("brand_list__filter_input").addEventListener('keyup', (e) => {
            const q = e.currentTarget.value.toLowerCase();
            const bs = Array.from(document.querySelectorAll('div[data-filter-name]'));
            for (b of bs) {
                const name = b.dataset.filterName.toLowerCase();
                if (name.indexOf(q) !== -1) {
                    b.style.display = "block";
                } else {
                    b.style.display = "none";
                }
            }
        });
    }
</script>