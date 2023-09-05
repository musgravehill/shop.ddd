<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;
 
// $brandIdRawSelected
// $brandCategoryIdRawSelected 

// $brandIdsNames
// $brandCategoryIdsNamesBrands 

$componentId = uniqid();
?>

<select name="brandId" class="form-control mr-1" id="bMaster_bcSlave__brandId__<?= $componentId ?>">
    <option <?= ($brandIdRawSelected == 0) ? 'selected' : '' ?> value="0">
        Производитель
    </option>
    <?php foreach ($brandIdsNames as $brandId => $name) : ?>
        <option <?= ($brandIdRawSelected == $brandId) ? 'selected' : '' ?> value="<?= $brandId ?>">
            <?= Html::encode($name) ?>
        </option>
    <?php endforeach; ?>
</select>

<select name="brandCategoryId" class="form-control mt-1">
    <option data-brand-id="0" data-component="bMaster_bcSlave__<?= $componentId ?>" data-purpose="brandCategoryId" <?= ($brandCategoryIdRawSelected == 0) ? 'selected' : '' ?> value="0">
        Категория
    </option>
    <?php foreach ($brandCategoryIdsNamesBrands as $brandCategoryId => ['name' => $name, 'brandId' => $brandId,]) : ?>
        <option data-brand-id="<?= $brandId ?>" data-component="bMaster_bcSlave__<?= $componentId ?>" data-purpose="brandCategoryId" <?= ($brandCategoryIdRawSelected == $brandCategoryId) ? 'selected' : '' ?> value="<?= $brandCategoryId ?>">
            <?= Html::encode($name) ?>
        </option>
    <?php endforeach; ?>
</select>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {

        bMaster_bcSlave__render('<?= $brandIdRawSelected ?>');

        const brandSelect = document.querySelector('select#bMaster_bcSlave__brandId__<?= $componentId ?>');
        const brandId = parseInt(brandSelect.value);
        brandSelect.addEventListener('change', (e) => {
            const brandId = parseInt(e.currentTarget.value);
            bMaster_bcSlave__render(brandId);
        });

        function bMaster_bcSlave__render(brandId) {
            brandId = parseInt(brandId);
            const options = Array.from(document.querySelectorAll('option[data-component="bMaster_bcSlave__<?= $componentId ?>"][data-purpose="brandCategoryId"]'));
            let isFirstRawSelected = false;
            for (option of options) {
                const option_brandId = parseInt(option.dataset.brandId);
                if (option_brandId === brandId) {
                    option.classList.remove("d-none");
                    if (!isFirstRawSelected) {
                        option.selected = true;
                        isFirstRawSelected = true;
                    }
                } else {
                    option.classList.add("d-none");
                }
            }
        }

    });
</script>