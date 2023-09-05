<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;
?>

<?php if ($imgUrls) : ?>
    <img src="<?= Html::encode($imgUrls[0]) ?>" style="max-height: 500px; width: 100%;" />
<?php endif; ?>
