<?php

use yii\helpers\Html;
use app\components\HelperY;
use app\components\User\Domain\Entity\User;
use app\components\UserCompany\Domain\Entity\UserCompany;
use yii\helpers\Url;

$this->title = 'Клиенты';

\Yii::$app->view->registerMetaTag([
    'name' => 'keywords',
    'content' => 'СпецДилер - агрегатор компаний и скидок ',
]);
\Yii::$app->view->registerMetaTag([
    'name' => 'description',
    'content' => 'СпецДилер - агрегатор компаний и скидок ',
]);
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h1>Клиенты</h1>
            </div>
            <div class="card-body">
                <table class="table table-sm table-bordered">
                    <tr>
                        <th>Клиент</th>
                        <th>Контакты</th>
                        <th>Компания</th>
                        <th>Создан</th>
                    </tr>
                    <?php foreach ($items as $item) : ?>
                        <?php
                        /** @var User $user */
                        $user = $item['user'];
                        /** @var UserCompany $company */
                        $company = $item['company'];
                        ?>
                        <tr>
                            <td>
                                <a href="<?= Url::to(['user/profile', 'id' => $user->getId()->getId(),]) ?>" class="text-dark">
                                    <?= Html::encode($user->getUsername()->getUsername()) ?>
                                </a>
                            </td>
                            <td>
                                <?= Html::encode($user->getEmail()->getEmail()) ?><br>
                                <?= Html::encode($user->getPhone()->getPhone()) ?>
                            </td>
                            <td>
                                <?= Html::encode($user->getCityName()->getCityName()) ?><br>
                                <?php if (!is_null($company)) : ?>
                                    <table class="table table-striped table-sm">
                                        <tr>
                                            <td colspan="2"><?= $company->getName()->getName() ?></td>
                                        </tr>
                                        <tr>
                                            <td>ИНН</td>
                                            <td><?= $company->getInn()->getInn() ?></td>
                                        </tr>
                                        <tr>
                                            <td>КПП</td>
                                            <td><?= $company->getKpp()->getKpp() ?></td>
                                        </tr>
                                        <tr>
                                            <td>БИК</td>
                                            <td><?= $company->getBik()->getBik() ?></td>
                                        </tr>
                                        <tr>
                                            <td>РС</td>
                                            <td><?= $company->getRs()->getRs() ?></td>
                                        </tr>
                                    </table>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="helper-font-14 text-secondary">
                                    <span moment="DD.MM.YY HH:mm"><?= $user->getCreatedAt()->getTimestamp() ?></span>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            <div class="card-footer">

                <?php
                $urlParamsTmp = $urlParams;
                $urlParamsTmp['page'] = $urlParamsTmp['page'] - 1;
                ?>
                <?php if ($urlParamsTmp['page'] > 0) : ?>
                    <a href="<?= Url::toRoute($urlParamsTmp) ?>" class="helper-font-30">
                        <span class="btn btn-outline-dark btn-sm">назад</span>
                    </a>
                <?php endif; ?>
                <?php
                $urlParamsTmp = $urlParams;
                $urlParamsTmp['page'] = $urlParamsTmp['page'] + 1;
                ?>
                <a href="<?= Url::toRoute($urlParamsTmp) ?>" class="float-right helper-font-30">
                    <span class="btn btn-outline-dark btn-sm">дальше</span>
                </a>

            </div>
        </div>

    </div>
</div>