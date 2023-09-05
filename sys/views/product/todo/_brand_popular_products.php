<?php

use yii\helpers\Html;
use app\components\HelperY;
use yii\helpers\Url;

$name = \app\components\CacheHelper::BRAND_VIEW_POPULAR_PRODUCTS;
$params = [(int) $brand['id']];
$cacheId = \app\components\CacheHelper::getId($name, $params);
?>
<?php if ($this->beginCache($cacheId, ['duration' => 3600])) : ?>
    <?php $ps = app\models\BrandHelper::getPopularProducts((int) $brand['id']); ?>
    <div class="row">
        <?php foreach ($ps as $item) : ?>
            <?=
            $this->context->renderPartial('/product/_item', [
                'item' => $item,
            ]);
            ?>
        <?php endforeach; ?>
    </div>
    <?php $this->endCache(); ?>
<?php endif; ?>