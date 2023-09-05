<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;

use app\components\User\Domain\Entity\User;
use app\components\UserCompany\Domain\Entity\UserCompany;

/** @var User $user */
/** @var UserCompany $userCompany */

$this->title = 'Данные';

\Yii::$app->view->registerMetaTag([
    'name' => 'keywords',
    'content' => 'СпецДилер - агрегатор компаний и скидок'
]);
\Yii::$app->view->registerMetaTag([
    'name' => 'description',
    'content' => 'СпецДилер - агрегатор компаний и скидок'
]);
?>

<div class="row mt-2">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h1><?= $user->getUsername()->getUsername() ?></h1>
            </div>
            <div class="card-body">
                <b>Email:</b> <?= $user->getEmail()->getEmail() ?><br>
                <b>Телефон:</b> <?= $user->getPhone()->getPhone() ?><br>
                <b>Дата регистрации:</b> <span moment="DD.MM.YY HH:mm"><?= $user->getCreatedAt()->getTimestamp() ?></span><br>
            </div>
            <div class="card-footer">
            </div>
        </div>
    </div>
</div>

<?php if (!is_null($userCompany)) : ?>
    <div class="row mt-2">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h1><?= $userCompany->getName()->getName() ?></h1>
                </div>
                <div class="card-body">
                    <b>ИНН:</b> <?= $userCompany->getInn()->getInn() ?><br>
                    <b>КПП:</b> <?= $userCompany->getKpp()->getKpp() ?><br>
                    <b>БИК:</b> <?= $userCompany->getBik()->getBik() ?><br>
                    <b>РС:</b> <?= $userCompany->getRs()->getRs() ?><br>
                </div>
                <div class="card-footer">
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
