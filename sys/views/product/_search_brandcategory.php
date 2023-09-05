<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;
use app\components\Search\Domain\SortId;

?>

<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body pt-0 pl-0 pr-0">
                <table class="table table-hover productInfoSearch__table mt-1" id="productInfoSearch__table">
                    <tbody id="productInfoSearch__container">

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
        const productSearch__progressBar = document.getElementById('productSearch__progressBar');
        let productSearch__isTabulaRasa = true;

        let searchProductsSeo__url = '<?= Url::toRoute($searchProductsSeo__url) ?>';
        searchProductsSeo__url = helper_removeParamFromUrl('page', searchProductsSeo__url);

        const productInfoSearch__table = document.getElementById('productInfoSearch__table');
        const productInfoSearch__container = document.getElementById('productInfoSearch__container');
        const productInfoSearch__chunkLoader_f = productInfoSearch__chunkLoader(searchProductsSeo__url, productInfoSearch__container, productSearch__progressBar);

        const searchQuery = ('<?= Html::encode($searchProductsSeo__url['searchQuery']) ?>');

        Promise.all([
            productInfoSearch__chunkLoader_f()
        ]).then(() => {
            helper_scrollEndless_functionAdd(productInfoSearch__chunkLoader_f);
        });

        function productInfoSearch__chunkLoader(url, container, progressBar) {
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

                const urlFull = url + '&page=' + page;

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