<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;

// $supplierIdRawSelected 
// $suppliers Supplier 
?>
<select name="supplierId" class="form-control mr-1">
    <option <?= ($supplierIdRawSelected == 0) ? 'selected' : '' ?> value="0">
        Поставщик
    </option>
    <?php foreach ($suppliers as $supplier) : ?>
        <?php /** @var null|Supplier $supplier */ ?>
        <option <?= ($supplierIdRawSelected == $supplier->getId()->getId()) ? 'selected' : '' ?> value="<?= Html::encode($supplier->getId()->getId()) ?>">
            <?= Html::encode($supplier->getName()->getName()) ?>
        </option>
    <?php endforeach; ?>
</select>