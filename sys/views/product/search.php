<?php

use yii\helpers\Html;
use app\components\HelperY;
use yii\helpers\Url;
//
use app\models\ProductHelper;

$this->title = 'Поиск: ' . HelperY::sanitizeWDS($searchProductsCommon__url['searchQuery']);

\Yii::$app->view->registerMetaTag([
    'name' => 'keywords',
    'content' => HelperY::sanitizeWDS($searchProductsCommon__url['searchQuery']) . ' СпецДилер купить'
]);
\Yii::$app->view->registerMetaTag([
    'name' => 'description',
    'content' => HelperY::sanitizeWDS($searchProductsCommon__url['searchQuery']) . ' СпецДилер купить'
]);
?>
<?=
$this->context->renderPartial('/product/_search_common', [
    'searchProductsSeo__url' => $searchProductsSeo__url,
    'searchProductsCommon__url' => $searchProductsCommon__url,
    'isShowFormFilters' => true,
    'title' => '',
    'jsOnChunkLoader' => null,
]);
?>
