<?php

use yii\helpers\Html;
use app\components\HelperY;
use yii\helpers\Url;
//
use \app\models\ProductHelper;

$this->title = 'Товары';

\Yii::$app->view->registerMetaTag([
    'name' => 'keywords',
    'content' => $urlParams['q'] . ' СпецДилер купить'
]);
\Yii::$app->view->registerMetaTag([
    'name' => 'description',
    'content' => $urlParams['q'] . ' СпецДилер купить'
]);

$brandsRaw = \app\models\Brand::find()->all();
$bcsRaw = \app\models\BrandCategory::find()->all();

?>

<div class="row">
    <div class="col-sm-12 col-md-12">
        <h1>
            Товары
        </h1>
    </div>
    <div class="col-sm-12 col-md-12">
        <form class="form-inline mb-3" method="GET" action="">
            <input value="<?= Html::encode($urlParams['q']) ?>" type="text" name="q" class="form-control mr-1" placeholder="название, ID">
            <?php $guid = HelperY::GUID(false) ?>
            <select name="brand_id" class="form-control mr-1" data-brand-master-guid="<?= $guid ?>">
                <option <?= ($urlParams['brand_id'] == -1) ? 'selected' : '' ?> value="-1">
                    Производитель любой
                </option>
                <option <?= ($urlParams['brand_id'] == 0) ? 'selected' : '' ?> value="0">
                    Без бренда
                </option>
                <?php foreach ($brandsRaw as $brandRaw) : ?>
                    <option <?= ($urlParams['brand_id'] == $brandRaw['id']) ? 'selected' : '' ?> value="<?= $brandRaw['id'] ?>">
                        <?= Html::encode($brandRaw['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <select name="brand_category_id" class="form-control mr-1">
                <option data-brand-id="-1" data-brand-master-guid="<?= $guid ?>" <?= ($urlParams['brand_category_id'] == -1) ? 'selected' : '' ?> value="-1">
                    Категории любая
                </option>
                <option data-brand-id="0" data-brand-master-guid="<?= $guid ?>" <?= ($urlParams['brand_category_id'] == 0) ? 'selected' : '' ?> value="0">
                    Без категории
                </option>
                <?php foreach ($bcsRaw as $bc) : ?>
                    <option data-brand-id="<?= (int) $bc['brand_id'] ?>" data-brand-master-guid="<?= $guid ?>" <?= ($urlParams['brand_category_id'] == $bc['id']) ? 'selected' : '' ?> value="<?= $bc['id'] ?>">
                        <?= Html::encode($bc['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <input value="<?= ($urlParams['price_min'] > 0) ? (float) $urlParams['price_min'] : '' ?>" type="number" name="price_min" class="form-control mr-1" placeholder="цена от" style="width: 130px;">
            <input value="<?= ($urlParams['price_max'] > 0) ? (float) $urlParams['price_max'] : '' ?>" type="number" name="price_max" class="form-control mr-1" placeholder="цена до" style="width: 130px;">

            <select name="del_option" class="form-control mr-1">
                <option <?= ($urlParams['del_option'] == ProductHelper::FILTER_DEL_ANY) ? 'selected' : '' ?> value="<?= ProductHelper::FILTER_DEL_ANY ?>">все</option>
                <option <?= ($urlParams['del_option'] == ProductHelper::FILTER_DEL_NOT_ONLY) ? 'selected' : '' ?> value="<?= ProductHelper::FILTER_DEL_NOT_ONLY ?>">не удаленные</option>
                <option <?= ($urlParams['del_option'] == ProductHelper::FILTER_DEL_ONLY) ? 'selected' : '' ?> value="<?= ProductHelper::FILTER_DEL_ONLY ?>">удаленные</option>
            </select>

            <select name="type_ids[]" multiple="true" class="form-control mr-1">
                <option <?= in_array(ProductHelper::TYPE_PRODUCT, $urlParams['type_ids']) ? 'selected' : '' ?> value="<?= ProductHelper::TYPE_PRODUCT ?>">
                    <?= ProductHelper::getTypeName(ProductHelper::TYPE_PRODUCT) ?>
                </option>
                <option <?= in_array(ProductHelper::TYPE_SERVICE, $urlParams['type_ids']) ? 'selected' : '' ?> value="<?= ProductHelper::TYPE_SERVICE ?>">
                    <?= ProductHelper::getTypeName(ProductHelper::TYPE_SERVICE) ?>
                </option>
                <option <?= in_array(ProductHelper::TYPE_BZN, $urlParams['type_ids']) ? 'selected' : '' ?> value="<?= ProductHelper::TYPE_BZN ?>">
                    <?= ProductHelper::getTypeName(ProductHelper::TYPE_BZN) ?>
                </option>
                <option <?= in_array(ProductHelper::TYPE_INFO, $urlParams['type_ids']) ? 'selected' : '' ?> value="<?= ProductHelper::TYPE_INFO ?>">
                    <?= ProductHelper::getTypeName(ProductHelper::TYPE_INFO) ?>
                </option>
            </select>

            <button type="submit" class="btn btn-primary my-2 my-sm-0 d-none d-sm-block">
                фильтр
            </button>
            <button type="submit" class="btn btn-primary my-2 my-sm-0 d-block d-sm-none">
                <span class="icon-search"></span>
            </button>
        </form>
    </div>
    <div class="col-12">
        <table class="table table-sm mt-2">
            <tbody>
                <tr>
                    <th>
                        <span class="icon-eye-view"></span>
                    </th>
                    <th>Фото</th>
                    <th>Название</th>
                    <th>Цена</th>
                    <th>Производитель</th>
                    <th>Категория</th>
                    <td>Тип</td>
                    <td></td>
                </tr>
                <?php foreach ($ps as $p) : ?>
                    <?php
                    $title = '';
                    $class = '';
                    if ($p['is_deleted']) {
                        $title = 'Товар удален.';
                        $class = 'helper-muted-semitransparent';
                    }
                    ?>
                    <tr class="<?= $class ?>" title="<?= $title ?>">
                        <td style="width: 50px;">
                            <?= Html::encode($p['counter_view']) ?>
                        </td>
                        <td>
                            <a href="<?= Url::to(['product/view', 'id' => $p['id'],]); ?>" class="">
                                <img src="<?= Html::encode($p['photo_url_1']) ?>" alt="" class="img-fluid" style="max-width: 64px; max-height: 48px;" />
                            </a>
                        </td>
                        <td>
                            <a href="<?= Url::to(['product/view', 'id' => $p['id'],]); ?>" class="helper-font-bold helper-font-15 text-dark helper-underline">
                                <?= Html::encode($p['name']) ?>
                            </a>
                            <span class="ml-3 text-secondary" title="ID поставщика">
                                <?= Html::encode($p['sku']) ?>
                            </span>
                            <br>
                            <span class="helper-font-10"><?= strip_tags(mb_substr($p['dsc'], 0, 128)) ?>..</span>
                        </td>
                        <td style="width: 120px;">
                            <span numeral="0,0.00" class="helper-font-16"><?= (float) $p['price'] ?></span>
                        </td>
                        <td>
                            <a href="<?= Url::to(['brand/view', 'id' => $p['brand_id'],]); ?>" class="text-dark">
                                <span data-brand-name-render data-brand-id="<?= Html::encode($p['brand_id']) ?>"></span>
                            </a>
                        </td>
                        <td>
                            <a href="<?= Url::to(['brandcategory/view', 'id' => $p['brand_category_id'],]); ?>" class="text-dark">
                                <span data-brand-category-name-render data-brand-category-id="<?= Html::encode($p['brand_category_id']) ?>"></span>
                            </a>
                        </td>
                        <td>
                            <?= ProductHelper::getTypeName((int)$p['type_id']) ?>
                        </td>
                        <td>
                            <?php if ($p['is_deleted']) : ?>
                                <a href="<?= Url::to(['product/undelete', 'id' => $p['id'],]); ?>" class="text-dark">
                                    <span class="icon-circledelete"></span>
                                </a>
                            <?php else : ?>
                                <a href="<?= Url::to(['product/delete', 'id' => $p['id'],]); ?>" class="text-dark">
                                    <span class="icon-circledelete"></span>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="col-12 mb-5 mt-1">
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

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        brandMaster_brandCategorySlave_init();
    });

    function brandMaster_brandCategorySlave_init() {
        const brandSelects = Array.from(document.querySelectorAll('select[data-brand-master-guid]'));
        for (brandSelect of brandSelects) {
            brandMaster_brandCategorySlave(brandSelect);
        }

        function brandMaster_brandCategorySlave(brandSelect) {
            const guid = brandSelect.dataset.brandMasterGuid;
            const brandId = parseInt(brandSelect.value) || 0;
            brandMaster_brandCategorySlave_render(brandId, guid);

            brandSelect.addEventListener('change', (e) => {
                const brandId = parseInt(e.currentTarget.value) || 0;
                brandMaster_brandCategorySlave_render(brandId, guid);
            });
        }

        function brandMaster_brandCategorySlave_render(brandId, guid) {
            const options = Array.from(document.querySelectorAll('option[data-brand-master-guid="' + guid + '"]'));
            if (brandId <= 0) {
                for (option of options) {
                    const option_brandId = parseInt(option.dataset.brandId) || 0;
                    if (option_brandId <= 0) {
                        option.classList.remove("d-none");
                    } else {
                        option.classList.add("d-none");
                    }
                }
                return;
            }

            for (option of options) {
                const option_brandId = parseInt(option.dataset.brandId) || 0;
                if (option_brandId === brandId || option_brandId <= 0) {
                    option.classList.remove("d-none");
                } else {
                    option.classList.add("d-none");
                }
            }
        }
    }
</script>