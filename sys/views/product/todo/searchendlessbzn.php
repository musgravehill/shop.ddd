<?php

use yii\helpers\Html;
use app\components\HelperY;
use yii\helpers\Url;
//
use app\models\ProductHelper;

$this->title = 'Поиск: ' . Html::encode($productSearch__url['q']);

\Yii::$app->view->registerMetaTag([
    'name' => 'keywords',
    'content' => Html::encode($productSearch__url['q']) . ' СпецДилер купить'
]);
\Yii::$app->view->registerMetaTag([
    'name' => 'description',
    'content' => Html::encode($productSearch__url['q']) . ' СпецДилер купить'
]);
?>



<?=
$this->context->renderPartial('/product/_common_search', [
    'productSearch__url' => $productSearch__url,
    'productInfoSearch__url' => $productInfoSearch__url,
    'isShowFormFilters' => true,
    'title' => '',
    'isHideOnLowRelevance'=> true,
    'productInfoSearch__isSort'=>false,
]);
?>

</script>
