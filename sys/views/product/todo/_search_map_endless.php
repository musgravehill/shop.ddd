<?php

use yii\helpers\Html;
use app\components\HelperY;
use yii\helpers\Url;
?>

<div id="productSearchMap__div_wrapper">
    <div id="productSearchMap__div_map" style="width: 100%; height: 300px; margin: 4px 0px;"></div>
</div>

<script src="//api-maps.yandex.ru/2.1/?lang=ru_RU&apikey=ea0860a1-d606-43c4-88f4-93529deb4edb" type="text/javascript"></script>
<script type="text/javascript">
    let productSearchMap__map;
    let productSearchMap__cluster;
    let productSearchMap__isReady = false;
    let productSearchMap__companyAdressIds_rendered = [];

    document.addEventListener('DOMContentLoaded', function () {
        ymaps.ready(productSearchMap__init);
    });

    const productSearchMap_onReady_promise = new Promise((resolve, reject) => {
        const timerId = setInterval(function () {
            if (productSearchMap__isReady) {
                clearInterval(timerId);                
                resolve();
            }
        }, 1000);
    });

    function productSearchMap__init() {
        //user has a point
        if (commonData__user_lat > 0 && commonData__user_long > 0) {
            productSearchMap__createMap({
                center: [commonData__user_lat, commonData__user_long],
                zoom: 11
            });
        } else {
            //user not login or user not have a point
            ymaps.geolocation.get().then(function (res) {
                const mapContainer = $('#productSearchMap__div_map'),
                        bounds = res.geoObjects.get(0).properties.get('boundedBy'),
                        // Рассчитываем видимую область для текущей положения пользователя.
                        mapState = ymaps.util.bounds.getCenterAndZoom(
                                bounds,
                                [mapContainer.width(), mapContainer.height()]
                                );
                mapState.zoom = 11;
                productSearchMap__createMap(mapState);
            }, function (e) {
                // Если местоположение невозможно получить, то просто создаем карту.
                productSearchMap__createMap({
                    center: [55, 37],
                    zoom: 11
                });
            });
        }
    }

    function productSearchMap__createMap(state) {
        productSearchMap__map = new ymaps.Map('productSearchMap__div_map', state);
        productSearchMap__cluster = new ymaps.Clusterer({
            /**
             * Через кластеризатор можно указать только стили кластеров,
             * стили для меток нужно назначать каждой метке отдельно.
             * @see https://api.yandex.ru/maps/doc/jsapi/2.1/ref/reference/option.presetStorage.xml
             */
            preset: 'islands#blueClusterIcons',
            /**
             * Ставим true, если хотим кластеризовать только точки с одинаковыми координатами.
             */
            groupByCoordinates: false,
            /**
             * Опции кластеров указываем в кластеризаторе с префиксом "cluster".
             * @see https://api.yandex.ru/maps/doc/jsapi/2.1/ref/reference/ClusterPlacemark.xml
             */
            clusterDisableClickZoom: false,
            clusterHideIconOnBalloonOpen: true,
            geoObjectHideIconOnBalloonOpen: true
        });

        productSearchMap__isReady = true;
    }



</script>

