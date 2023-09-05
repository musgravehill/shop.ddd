<?php

use yii\helpers\Html;
use app\components\HelperY;
use yii\helpers\Url;

$this->title = 'Новости';
?>

<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white p-2 pb-1">
                <h1>Новости</h1>
            </div>
            <div class="card-body pt-0 pl-0 pr-0">
                <div class="row" id="pageSearch__container"></div>
                <div id="pageSearch__progressBar" class="d-flex justify-content-center">
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
        pageSearch();
    });

    function pageSearch() {
        let searchPages__url = '<?= Url::toRoute($searchPages__url) ?>';
        searchPages__url = helper_removeParamFromUrl('page', searchPages__url);

        const pageSearch__container = document.getElementById('pageSearch__container');
        const pageSearch__progressBar = document.getElementById('pageSearch__progressBar');
        const pageSearch__chunkLoader_f = pageSearch__chunkLoader(searchPages__url, pageSearch__container, pageSearch__progressBar);

        let pageSearch__isTabulaRasa = true;

        Promise.all([
            pageSearch__chunkLoader_f()
        ]).then(() => {
            helper_scrollEndless_functionAdd(pageSearch__chunkLoader_f);
        });

        function pageSearch__chunkLoader(url, container, progressBar) {
            let isAllowLoad = true;
            let page = 1;

            // closure, состояние в замыкании будет храниться.
            return async () => {
                if (pageSearch__isTabulaRasa) {
                    container.innerHTML = '';
                    page = 1;
                    isAllowLoad = true;
                    pageSearch__isTabulaRasa = false;
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
                    });
            };
        }
    }
</script>