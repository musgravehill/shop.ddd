<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;
use app\components\Search\Domain\SortId;

?>

<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <?php if ($isShowFormFilters || $title) : ?>
                <div class="card-header bg-white p-2 pb-1">
                    <?php if ($title) : ?>
                        <h2 class="mb-2">
                            <?= $title ?>
                        </h2>
                    <?php endif; ?>
                    <?php if ($isShowFormFilters) : ?>
                        <form method="GET" action="<?= Url::toRoute(['product/search']) ?>" class="">
                            <div class="d-block d-md-inline-block mr-1 mb-1">
                                <input value="<?= Html::encode($searchProductsCommon__url['searchQuery']) ?>" type="text" name="searchQuery" class="form-control" placeholder="Поиск" style="width: 344px;">
                            </div>
                            <div class="d-block d-md-inline-block mr-1 mb-1">
                                <div class="d-inline-block">
                                    <div class="input-group" style="width: 186px;">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">Цена от</div>
                                        </div>
                                        <input zero2null title="от" placeholder="" name="priceMin" type="number" class="form-control" value="<?= intval($searchProductsCommon__url['priceMin']/100) ?>" step="1" />
                                    </div>
                                </div>
                                <div class="d-inline-block">
                                    <div class="input-group" style="width: 150px;">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">до</div>
                                        </div>
                                        <input zero2null title="до" placeholder="" name="priceMax" type="number" class="form-control" value="<?= intval($searchProductsCommon__url['priceMax']/100) ?>" step="1" />
                                    </div>
                                </div>
                            </div>
                            <div class="d-none mr-1 mb-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" name="quantityAvailableMin" <?= $searchProductsCommon__url['quantityAvailableMin'] > 0 ? 'checked' : '' ?>>
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
            <?php endif; ?>
            <div class="card-body pt-0 pl-0 pr-0">
                <table class="table table-hover productInfoSearch__table mt-1" id="productInfoSearch__table">
                    <thead>
                        <tr>
                            <th colspan="3">
                                <div class="d-flex justify-content-between">
                                    <div class="d-inline-block">
                                        <h3 class="text-danger">Предложения производителей</h3>
                                    </div>
                                    <div class="d-inline-block">
                                        <h3 class="text-danger">Цена / скидка</h3>
                                    </div>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="productInfoSearch__container">

                    </tbody>
                </table>
                <table class="table table-hover productSearch__table">
                    <thead>
                        <tr>
                            <th colspan="3">
                                <div class="d-flex justify-content-between">
                                    <div class="d-inline-block">
                                        <h3 class="d-inline-block text-danger">Предложения поставщиков</h3>
                                        <span title="Сортировка по релевантности" data-component="productSearchCommon" data-purpose="sort" data-sort-id="<?= (SortId::ProductRelevantDesc)->value ?>" data-sort-selected="true" class="productSearch-sort-container helper-font-14 badge text-nowrap helper-cursor-pointer mt-1">
                                            <span class="icon-chevron-down"></span>
                                        </span>
                                    </div>
                                    <div class="d-none">
                                        <div class="form-check">
                                            <input data-component="productSearchCommon" data-purpose="filter" data-filter="quantityAvailableMin" class="form-check-input" type="checkbox" value="1" <?= $searchProductsCommon__url['quantityAvailableMin'] > 0 ? 'checked' : '' ?>>
                                            <label class="form-check-label">
                                                наличие
                                            </label>
                                        </div>
                                    </div>
                                    <div class="d-inline-block">
                                        <h3 class="d-inline-block text-danger">Цена / наличие</h3>
                                        <span title="Сортировка по цене" data-component="productSearchCommon" data-purpose="sort" data-sort-id="<?= (SortId::ProductPriceAsc)->value ?>" data-sort-selected="false" class="productSearch-sort-container helper-font-14 badge text-nowrap helper-cursor-pointer mt-1">
                                            <span class="icon-chevron-up"></span>
                                        </span>
                                    </div>
                                </div>
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
        const searchProductsSeo__url = '<?= Url::toRoute($searchProductsSeo__url) ?>';
        const productInfoSearch__table = document.getElementById('productInfoSearch__table');
        const productInfoSearch__container = document.getElementById('productInfoSearch__container');
        const productInfoSearch__chunkLoader_f = productInfoSearch__chunkLoader(searchProductsSeo__url, productInfoSearch__container);

        let searchProductsCommon__url = '<?= Url::toRoute($searchProductsCommon__url) ?>';
        searchProductsCommon__url = helper_removeParamFromUrl('page', searchProductsCommon__url);
        searchProductsCommon__url = helper_removeParamFromUrl('sortId', searchProductsCommon__url);
        searchProductsCommon__url = helper_removeParamFromUrl('quantityAvailableMin', searchProductsCommon__url);

        const productSearch__container = document.getElementById('productSearch__container');
        const productSearch__progressBar = document.getElementById('productSearch__progressBar');
        const productSearch__chunkLoader_f = productSearch__chunkLoader(searchProductsCommon__url, productSearch__container, productSearch__progressBar);

        let productSearch__isTabulaRasa = true;

        const searchQuery = ('<?= Html::encode($searchProductsCommon__url['searchQuery']) ?>');

        Promise.all([
            productInfoSearch__chunkLoader_f(),
            productSearch__chunkLoader_f()
        ]).then(() => {
            helper_scrollEndless_functionAdd(productSearch__chunkLoader_f);
            productSearch__sort();
            productSearch__filter();
        });

        function productInfoSearch__chunkLoader(url, container) {
            return async () => {
                return fetch(url)
                    .then(response => response.text())
                    .then((data) => {
                        container.insertAdjacentHTML('beforeend', data);
                        if ((data).length < 10) {
                            productInfoSearch__table.remove();
                        }

                        site__cart_render_init();
                        helper_numeral();

                        const highlightTextItems = Array.from(document.querySelectorAll('span[data-component="productSearchCommon"][data-purpose="itemName"]'));
                        helper_highlight_text(searchQuery, highlightTextItems, 'font-weight: bold;');

                        <?= $jsOnChunkLoader ?? '' ?>
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
                let filterIsAavailableOnly = document.querySelector('input[data-component="productSearchCommon"][data-purpose="filter"][data-filter="quantityAvailableMin"]').checked;

                const urlFull = url + '&page=' + page + '&sortId=' + sortId + '&quantityAvailableMin=' + (filterIsAavailableOnly ? '1' : '0');
                page++;
                progressBar.style.visibility = 'visible';
                return fetch(urlFull)
                    .then(response => response.text())
                    .then((data) => {
                        if ((data).length < 100) {
                            isAllowLoad = false;
                        }
                        container.insertAdjacentHTML('beforeend', data);
                        progressBar.style.visibility = 'hidden';

                        site__cart_render_init();
                        helper_numeral();

                        const highlightTextItems = Array.from(document.querySelectorAll('span[data-component="productSearchCommon"][data-purpose="itemName"]'));
                        helper_highlight_text(searchQuery, highlightTextItems, 'font-weight: bold;');

                        <?= $jsOnChunkLoader ?? '' ?>
                    });
            };
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
    }
</script>

<style type="text/css">
    .productInfoSearch__table th {
        font-weight: 200;
        padding: 0px 10px 3px 10px !important;
        background-color: white !important;
    }

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
        display: none !important;
    }

    #productInfoSearch__container tr,
    #productSearch__container tr {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
</style>