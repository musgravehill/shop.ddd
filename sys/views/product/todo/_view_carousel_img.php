<?php

use yii\helpers\Html;
use app\components\HelperY;
use yii\helpers\Url;
use app\models\ProductHelper;
use app\models\SaleHelper;
?>

<?php if ($p->photo_url_1) : ?>
    <img src="<?= Html::encode($p->photo_url_1) ?>" style="max-height: 500px; width: 100%;" />
<?php endif; ?>