<?php

/*
  $products = app\models\Product::find()->limit(10000)->all();
  foreach ($products as $product) {
  app\models\ProductSphinxHelper::event_onProductCrud($product->id);
  }
 */

/*
  $sql = " TRUNCATE RTINDEX index_bn_products_rt ";
  Yii::$app->sphinx->createCommand($sql, [])->execute();
 */

/*
  $q = app\components\HelperY::getGet('q', '');
  $r = (int) app\components\HelperY::getGet('r', 999);

  $filters = [];
  $filters['q'] = $q;
  $filters['distance'] = $r;
  $filters['point_lat'] = 57.6486;
  $filters['point_long'] = 39.9502;
  $filters['page'] = 1;
  $filters['type_id'] = \app\models\ProductHelper::TYPE_ALL;

  $ps = app\models\ProductSphinxHelper::search_products($filters, 10);
  print_r($ps);
 */



echo '<br><b> index_bn_products_rt </b><br><br>';
$sql = "SELECT
        *
        FROM index_bn_products_rt
        LIMIT 100
          ";

$params = [];
$rows = Yii::$app->sphinx->createCommand($sql, $params)->queryAll();
print_r($rows);
?>