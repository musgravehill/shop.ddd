<?php

use yii\helpers\Html;
use app\components\HelperY;
use yii\helpers\Url;
//
use app\assets\AppAsset;
//
use app\models\User;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!doctype html>
<html lang="ru" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <?= Html::csrfMetaTags() ?>

    <link rel="icon" href="/favicon.svg" sizes="any" type="image/svg+xml">
    <title><?= Html::encode($this->title) ?></title>

    <?php $this->head() ?>
</head>

<body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <?= $this->render('_top_nav') ?>

    <main role="main" class="flex-shrink-0" style="padding-bottom: 30px;">
        <div class="container">
            <?php
            if (!Yii::$app->user->isGuest) {
                /*
                      $tokens = app\models\NotifyPush::getUserTokens(HelperY::userId());
                      $countTokens = 0;
                      foreach ($tokens as $token) {
                      $countTokens++;
                      }
                      if ($countTokens == 0) {
                      $msg = '<a class="btn btn-warning"   href="' . app\models\NotifyPush::getUrlSubscribe(HelperY::userId()) . '" >
                      получать оповещения
                      </a>';
                      Yii::$app->session->addFlash('warning', $msg);
                      } */


                /* $phone_is_confirm = Yii::$app->user->identity->userEntity->phone_is_confirm;
                      if (!$phone_is_confirm) {
                      $msg = '<a class="btn btn-warning" href="' . Url::to(['site/confirmphoneinit']) . '">подтвердить телефон</a>';
                      Yii::$app->session->addFlash('warning', $msg);
                      } */

                /* $email_is_confirm = Yii::$app->user->identity->userEntity->email_is_confirm;
                      if (!$email_is_confirm) {
                      $msg = '<a class="btn btn-warning" href="' . Url::to(['site/confirmemailinit']) . '">подтвердить email</a>';
                      Yii::$app->session->addFlash('warning', $msg);
                      } */

                /* $geo_lat = (float) Yii::$app->user->identity->userEntity->geo_lat;
                      $geo_long = (float) Yii::$app->user->identity->userEntity->geo_long;
                      if ($geo_lat === 0.0 && $geo_long === 0.0) {
                      echo $this->render('/user/_user_map_alert');
                      } */
            }
            ?>
            <div class="row">
                <?php $noflash = HelperY::getGet('noflash', 0); ?>
                <?php if (!$noflash) : ?>
                    <?php foreach (Yii::$app->session->getAllFlashes() as $key => $messages) : ?>
                        <?php foreach ($messages as $message) : ?>
                            <div class="col">
                                <div class="m-1 alert alert-<?= $key ?> alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <?= $message ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <?= $content ?>
        </div>
    </main><!-- /.container -->

    <footer class="footer mt-auto py-3">
        <div class="container text-center">
            <a href="<?= Url::toRoute(['page/view', 'id' => 5,]) ?>" class="text-dark">Безналом &reg; <?= date('Y') ?></a>
            <a href="<?= Url::toRoute(['page/view', 'id' => 7,]) ?>" class="text-dark ml-5">Контакты</a>
        </div>
    </footer>

    <?= $this->render('/layouts/_common_data') ?>

    <?= $this->render('/layouts/_message_send') ?>

    <?php $this->endBody() ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const imgs = Array.from(document.querySelectorAll('img'));
            for (img of imgs) {
                img.addEventListener('error', (e) => {
                    e.currentTarget.style.display = "none";
                });
            }
        });
    </script>

</body>

</html>
<?php $this->endPage() ?>