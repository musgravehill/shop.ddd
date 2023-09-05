<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;
?>

<script type="text/javascript">
    const commonData__csrfTokenKey = '<?= Yii::$app->request->csrfParam ?>';
    const commonData__csrfTokenVal = '<?= Yii::$app->request->csrfToken ?>';

    const commonData__cartGet_url = '<?= Url::to(['cart/getdata']); ?>';
    const commonData__cartSet_url = '<?= Url::to(['cart/set']); ?>';
</script>
