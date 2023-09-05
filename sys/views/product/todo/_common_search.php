<?php

use yii\helpers\Html;
use app\components\HelperY;
use yii\helpers\Url;
//
use app\models\ProductHelper;
use app\models\SaleHelper;
?>

<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white p-2 pb-1">
                <?php if ($title) : ?>
                    <h2 class="mb-2">
                        <?= $title ?>
                    </h2>
                <?php endif; ?>
                <?php if ($isShowFormFilters) : ?>
                    <form method="GET" action="<?= Url::toRoute(['product/searchendlessbzn']) ?>" class="">
                        <div class="d-block d-md-inline-block mr-1 mb-1">
                            <input value="<?= Html::encode($productSearch__url['q']) ?>" type="text" name="q" class="form-control" placeholder="Поиск" style="width: 344px;">
                        </div>
                        <div class="d-block d-md-inline-block mr-1 mb-1">
                            <div class="d-inline-block">
                                <div class="input-group" style="width: 186px;">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">Цена от</div>
                                    </div>
                                    <input zero2null title="от" placeholder="" name="price_min" type="number" class="form-control" value="<?= (int) $productSearch__url['price_min'] ?>" step="1" />
                                </div>
                            </div>
                            <div class="d-inline-block">
                                <div class="input-group" style="width: 150px;">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">до</div>
                                    </div>
                                    <input zero2null title="до" placeholder="" name="price_max" type="number" class="form-control" value="<?= (int) $productSearch__url['price_max'] ?>" step="1" />
                                </div>
                            </div>
                        </div>
                        <div class="d-block d-md-inline-block mr-1 mb-1">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" name="is_available_only" <?= $productSearch__url['is_available_only'] == 1 ? 'checked' : '' ?>>
                                <label class="form-check-label">
                                    в наличии на складе
                                </label>
                            </div>
                        </div>
                        <div class="d-block d-md-inline-block mr-1 mb-1">
                            <button type="submit" class="btn btn-outline-secondary">
                                <span class="icon-search"></span>
                            </button>
                        </div>
                    </form>

                <?php endif; ?>
            </div>
            <div class="card-body pt-0 pl-0 pr-0">
                <table class="table table-hover productInfoSearch__table mt-1" id="productInfoSearch__table">
                    <tbody id="productInfoSearch__container">

                    </tbody>
                </table>
                <table class="table table-hover productSearch__table">
                    <thead>
                        <tr>
                            <th>
                                <span data-component="productSearchCommon" data-purpose="sort" data-sort-id="<?= ProductHelper::SORT_TYPE_RELEVANT ?>" data-sort-selected="true" class="productSearch-sort-container helper-font-14 badge text-nowrap helper-cursor-pointer mt-1">
                                    <!--span class="product-search-sort-icon helper-font-10" data-sort-id="<?= ProductHelper::SORT_TYPE_RELEVANT ?>"></span-->
                                    Предложения поставщиков
                                </span>
                            </th>
                            <th class="d-none d-md-table-cell text-center text-nowrap">
                                <div class="form-check">
                                    <input data-component="productSearchCommon" data-purpose="filter" data-filter="isAavailableOnly" class="form-check-input" type="checkbox" value="1" <?= $productSearch__url['is_available_only'] == 1 ? 'checked' : '' ?>>
                                    <label class="form-check-label">
                                        наличие
                                    </label>
                                </div>
                            </th>
                            <th class="text-center">
                                <span data-component="productSearchCommon" data-purpose="sort" data-sort-id="<?= ProductHelper::SORT_TYPE_PRICE_ASC ?>" data-sort-selected="false" class="productSearch-sort-container helper-font-14 badge text-nowrap helper-cursor-pointer mt-1">
                                    <!--span class="product-search-sort-icon helper-font-10" data-sort-id="<?= ProductHelper::SORT_TYPE_PRICE_ASC ?>"></span-->
                                    Цена
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="productSearch__container">

                    </tbody>
                </table>
                <div id="productSearch__progressBar" class="d-flex justify-content-center">
                    <div class="spinner-border text-danger" role="status">
                        <span class="sr-only"></span>
                    </div>
                    <span class="helper-font-22 ml-3">
                        загружаем результаты...
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', () => {
        productSearch();
    });

    function productSearch() {

        const productTypeId_hideOnLowRelevance = <?= (int) ProductHelper::TYPE_INFO ?>;
        const productInfoSearch__url = '<?= Url::toRoute($productInfoSearch__url) ?>';
        const productInfoSearch__table = document.getElementById('productInfoSearch__table');
        const productInfoSearch__container = document.getElementById('productInfoSearch__container');
        const productInfoSearch__chunkLoader_f = productInfoSearch__chunkLoader(productInfoSearch__url, productInfoSearch__container);
        const productInfoSearch__isHideOnLowRelevance = <?= $isHideOnLowRelevance ? 'true' : 'false' ?>;
        const productInfoSearch__isSort = <?= $productInfoSearch__isSort ? 'true' : 'false' ?>;

        let productSearch__url = '<?= Url::toRoute($productSearch__url) ?>';
        productSearch__url = helper_removeParamFromUrl('page', productSearch__url);
        productSearch__url = helper_removeParamFromUrl('sort_id', productSearch__url);
        productSearch__url = helper_removeParamFromUrl('is_available_only', productSearch__url);

        const productSearch__container = document.getElementById('productSearch__container');
        const productSearch__progressBar = document.getElementById('productSearch__progressBar');
        const productSearch__chunkLoader_f = productSearch__chunkLoader(productSearch__url, productSearch__container, productSearch__progressBar);

        let productSearch__isTabulaRasa = true;

        Promise.all([
            productInfoSearch__chunkLoader_f(),
            productSearch__chunkLoader_f()
        ]).then(() => {
            helper_scrollEndless_functionAdd(productSearch__chunkLoader_f);
            if (productInfoSearch__isHideOnLowRelevance) {
                productSearch__hideOnLowRelevance_f();
            }
            if (productInfoSearch__isSort) {
                productInfoSearch__sort();
            }
            productSearch__sort();
            productSearch__filter();
            <?= $js_loadOk_fns ?? '' ?>
        });

        function productInfoSearch__chunkLoader(url, container) {
            return async () => {
                return fetch(url)
                    .then(response => response.json())
                    .then((data) => {
                        container.insertAdjacentHTML('beforeend', data.html);
                        if ((data.html).length < 10) {
                            productInfoSearch__table.remove();
                        }
                        site__salePersonalBrands();
                        site__brands();
                        site__cart_render_init();
                    });
            };
        }

        function productSearch__chunkLoader(url, container, progressBar) {
            let isAllowLoad = true;
            let page = 1;

            // closure, состояние в замыкании будет храниться.
            return async () => {
                if (productSearch__isTabulaRasa) {
                    container.innerHTML = '';
                    page = 1;
                    isAllowLoad = true;
                    productSearch__isTabulaRasa = false;
                }

                if (!isAllowLoad) {
                    return true;
                }

                let sortId = parseInt(document.querySelector('[data-component="productSearchCommon"][data-purpose="sort"][data-sort-selected="true"]').dataset.sortId) || 0;
                let filterIsAavailableOnly = document.querySelector('input[data-component="productSearchCommon"][data-purpose="filter"][data-filter="isAavailableOnly"]').checked;

                const urlFull = url + '&page=' + page + '&sort_id=' + sortId + '&is_available_only=' + (filterIsAavailableOnly ? '1' : '0');
                page++;
                progressBar.style.visibility = 'visible';
                return fetch(urlFull)
                    .then(response => response.json())
                    .then((data) => {
                        if ((data.html).length < 100) {
                            isAllowLoad = false;
                        }
                        container.insertAdjacentHTML('beforeend', data.html);
                        progressBar.style.visibility = 'hidden';
                        productSearch__addressRender(data.companies_addresses);
                        site__cart_render_init();
                        helper_numeral();
                        helper_moment();
                        helper_modal();
                    });
            };
        }

        function productInfoSearch__sort() {
            function comparator(a, b) {
                if (parseInt(a.dataset.productCounterView) < parseInt(b.dataset.productCounterView))
                    return 1;
                if (parseInt(a.dataset.productCounterView) > parseInt(b.dataset.productCounterView))
                    return -1;
                return 0;
            }

            const tbl = document.getElementById("productInfoSearch__container");
            if (tbl) {
                let trs = Array.from(tbl.querySelectorAll('[data-component="productSearchCommon"][data-purpose="item"][data-product-counter-view]'));
                trs.sort(comparator);

                const [trsPriceNotZero, trsPriceZero] =
                trs.reduce((result, element) => {
                        const idx = parseInt(element.dataset.price) > 0 ? 0 : 1;
                        result[idx].push(element);
                        return result;
                    },
                    [
                        [],
                        []
                    ]);

                trsPriceNotZero.forEach(tr => tbl.appendChild(tr));
                trsPriceZero.forEach(tr => tbl.appendChild(tr));
            }
        }

        function productSearch__sort() {
            const sortId = parseInt(document.querySelector('span[data-component="productSearchCommon"][data-purpose="sort"][data-sort-selected="true"]').dataset.sortId) || 0;
            sort_process(sortId);

            const btns = Array.from(document.querySelectorAll('[data-component="productSearchCommon"][data-purpose="sort"]'));
            for (btn of btns) {
                btn.addEventListener('click', (e) => {
                    const sortId = parseInt(e.currentTarget.dataset.sortId) || 0;
                    sort_process(sortId);
                    productSearch__isTabulaRasa = true;
                    productSearch__chunkLoader_f();
                });
            }

            function sort_process(sortId) {
                const btns = Array.from(document.querySelectorAll('[data-component="productSearchCommon"][data-purpose="sort"]'));
                for (btn of btns) {
                    if (parseInt(btn.dataset.sortId) === sortId) {
                        btn.dataset.sortSelected = 'true';
                        btn.classList.add('productSearch-sort-container-selected');
                    } else {
                        btn.dataset.sortSelected = 'false';
                        btn.classList.remove('productSearch-sort-container-selected');
                    }
                }

                /*const icons = Array.from(document.querySelectorAll('span.product-search-sort-icon'));
                for (icon of icons) {
                    if (parseInt(icon.dataset.sortId) === sortId) {
                        icon.classList.add('icon-loadingeight');
                        icon.classList.remove('icon-circleloaderempty');
                    } else {
                        icon.classList.add('icon-circleloaderempty');
                        icon.classList.remove('icon-loadingeight');
                    }
                }*/
            }
        }

        function productSearch__filter() {
            const btns = Array.from(document.querySelectorAll('[data-component="productSearchCommon"][data-purpose="filter"]'));
            for (btn of btns) {
                btn.addEventListener('click', (e) => {
                    productSearch__isTabulaRasa = true;
                    productSearch__chunkLoader_f();
                });
            }
        }

        function productSearch__hideOnLowRelevance_f() {
            const items = Array.from(document.querySelectorAll('[data-component="productSearchCommon"][data-purpose="item"][data-product-type-id][data-relevance]'));
            const relevanceMax = 0.50 * items.reduce((memo, curr) => {
                if (parseInt(curr.dataset.productTypeId) !== productTypeId_hideOnLowRelevance) {
                    return Math.max(memo, parseInt(curr.dataset.relevance));
                }
                return memo;
            }, -9999);
            const items2 = Array.from(document.querySelectorAll('[data-component="productSearchCommon"][data-purpose="item"][data-product-type-id="' + productTypeId_hideOnLowRelevance + '"][data-relevance]'));
            for (item of items2) {
                if (parseInt(item.dataset.relevance) < relevanceMax) {
                    item.style.display = 'none';
                }
            }
        }

        function productSearch__addressRender(companies_addresses) {
            for (const addr of companies_addresses) {
                const address_id = parseInt(addr.id) || 0;
                const geo_city = addr.geo_city;
                const geo_region = addr.geo_region;
                const spans = Array.from(document.querySelectorAll('span[productSearch__city_todo][address_id="' + address_id + '"]'));
                for (span of spans) {
                    span.removeAttribute('productSearch__city_todo');
                    span.innerHTML = geo_city;
                    span.setAttribute('title', geo_region);
                }

                const as = Array.from(document.querySelectorAll('a[productSearch__company_todo][address_id="' + address_id + '"]'));
                for (a of as) {
                    a.removeAttribute('productSearch__company_todo');
                    a.innerHTML = addr.c_name;
                    a.setAttribute('href', addr.c_url);
                }
            }
        }

    }
</script>

<style type="text/css">
    .productInfoSearch__table td {
        border-top: 1px solid #ffffff;
        padding-top: 3px;
        padding-bottom: 4px;
    }

    .productInfoSearch__table tr {
        background-color: #efefef !important;
    }

    .productSearch__table td {
        padding-top: 3px;
        padding-bottom: 4px;
    }

    .productSearch__table th {
        font-weight: 200;
        padding: 0px 10px 3px 10px !important;
    }

    .productSearch-sort-container {
        color: black;
        background-color: #efefef;
    }

    .productSearch-sort-container-selected {
        color: white !important;
        background-color: #dc3545 !important;
    }

    #productInfoSearch__container tr,
    #productSearch__container tr {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
</style>