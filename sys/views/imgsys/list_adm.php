<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;
use app\components\Imgsys\Domain\Entity\Imgsys;
use app\components\Imgsys\Infrastructure\HelperImgsys;
use yii\bootstrap\ActiveForm;

$this->title = 'Картинки';

\Yii::$app->view->registerMetaTag([
    'name' => 'keywords',
    'content' => 'СпецДилер - агрегатор компаний и скидок'
]);
\Yii::$app->view->registerMetaTag([
    'name' => 'description',
    'content' => 'СпецДилер - агрегатор компаний и скидок'
]);
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h1>Картинки</h1>
            </div>
            <div class="card-body">
                <?php
                $form = ActiveForm::begin([
                    //'layout' => 'horizontal',
                    'options' => [
                        'class' => '',
                        'enctype' => 'multipart/form-data',
                    ],
                    'fieldConfig' => [
                        'template' => '                              
                            <div class="m-0 p-0">
                                {input}
                                {hint}
                                <div class="text-danger">
                                    {error}
                                </div>                            
                            </div>',
                        'options' => [
                            'class' => 'form-group',
                        ],
                        //{beginWrapper} {endWrapper}
                        /* 'horizontalCssClasses' => [
                      'label' => 'col-sm-4',
                      'offset' => 'zcol-sm-offset-4',
                      'wrapper' => 'col-sm-4',
                      'error' => 'text-danger',
                      'hint' => 'text-danger'
                      ] */
                    ]
                ]);
                ?>
                <?= $form->field($formImgUpload, 'imageFiles[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>
                <?= $form->field($formImgUpload, 'tags')->textInput(['autofocus' => true, 'autocomplete' => 'off', 'placeholder' => $formImgUpload->getAttributeLabel('tags'),]) ?>
                <?= Html::submitButton('Закачать', ['class' => 'btn btn-info',]) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-header">
                <?= Html::beginForm(['imgsys/list_adm'], 'get', ['class' => 'form-inline']) ?>
                <input name="tags" value="<?= $urlParams['tags'] ?>" title="Ключевые слова" placeholder="Ключевые слова" type="text" autocomplete="off" class="form-control form-control-sm">
                <button type="submit" class="btn btn-outline-secondary ml-2 btn-sm">найти</button>
                <a class="btn btn-outline-secondary ml-4 btn-sm" href="<?= Url::to(['imgsys/list_adm',]) ?>">сброс</a>
                <?= Html::endForm() ?>
            </div>
            <div class="card-body">
                <table class="table table-striped table-sm mt-2">
                    <?php foreach ($imgs as $img) : ?>
                        <?php /** @var Imgsys $img */ ?>
                        <tr>
                            <td>
                                <img src="<?= HelperImgsys::getPublicUrlRelative($img->getId()) ?>" style="height: 64px; max-width: 128px;" />
                            </td>
                            <td>
                                <div class="d-block">
                                    <input imgsys-tags-input type="text" data-id="<?= $img->getId()->getId() ?>" class="d-inline-block border" placeholder="Ключевые слова" value="<?= Html::encode($img->getTags()->getTags()) ?>" style="width: 440px;" />
                                    <span imgsys-tags-btn data-id="<?= $img->getId()->getId() ?>" class="btn btn-outline-success btn-sm">ok</span>
                                </div>
                                <?= HelperImgsys::getPublicUrlAbsolute($img->getId()) ?>
                            </td>
                            <td>
                                <?= Html::beginForm(['imgsys/list_adm'], 'post', ['class' => 'form-inline']) ?>
                                <input name="imgsysIdDel" type="hidden" value="<?= $img->getId()->getId() ?>">
                                <button type="submit" class="btn btn-outline-secondary btn-sm">удалить</button>
                                <?= Html::endForm() ?>
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
                    <a href="<?= Url::toRoute($urlParamsTmp) ?>" class="helper-font-16">
                        <span class="btn btn-outline-dark btn-sm">назад</span>
                    </a>
                <?php endif; ?>
                <?php
                $urlParamsTmp = $urlParams;
                $urlParamsTmp['page'] = $urlParamsTmp['page'] + 1;
                ?>
                <a href="<?= Url::toRoute($urlParamsTmp) ?>" class="float-right helper-font-16">
                    <span class="btn btn-outline-dark btn-sm">дальше</span>
                </a>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        imgSys();

        function imgSys() {
            imgSys_init();

            function imgSys_init() {
                const items = Array.from(document.querySelectorAll('span[imgsys-tags-btn]'));
                for (item of items) {
                    item.addEventListener('click', (e) => {
                        const id = e.currentTarget.dataset.id;
                        const tags = document.querySelector('input[imgsys-tags-input][data-id="' + id + '"]').value;
                        imgSys_tags_save(id, tags);
                    });
                }
            }

            async function imgSys_tags_save(id, tags) {
                const inp = document.querySelector('input[imgsys-tags-input][data-id="' + id + '"]');
                inp.classList.remove('border-success');

                const url = '<?= Url::toRoute(['imgsys/settags']) ?>';
                const data = {
                    id: id,
                    tags: tags,
                    [commonData__csrfTokenKey]: commonData__csrfTokenVal
                };

                const response = await helper_postData(url, data);
                const res = await response.json();
                if (parseInt(res) === 1) {
                    inp.classList.add('border-success');
                } else {
                    inp.classList.add('border-danger');
                }
            }

        }
    });
</script>