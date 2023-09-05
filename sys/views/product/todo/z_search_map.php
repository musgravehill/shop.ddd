<?php

use yii\helpers\Html;
use app\components\HelperY;
use yii\helpers\Url;
?>

<div class="mb-3">
    <span class="helper-font-bold">
        <span class="icon-checkin text-success"></span>
        Вокруг:
    </span>
    <span todo common_modal__setUrl="<?= Url::toRoute('user/map') ?>" class="helper-cursor-pointer helper-dashed">
        <?= Html::encode($geoUserData['addr']) ?>
    </span>
</div>
<div id="company_address_map__wrapp">
    <div id="company_address_map__block" style="width: 100%; height: 300px; margin: 4px 0px;"></div>
</div>

<script src="//api-maps.yandex.ru/2.1/?lang=ru_RU&apikey=ea0860a1-d606-43c4-88f4-93529deb4edb" type="text/javascript"></script>
<script type="text/javascript">
    var company_addresses = JSON.parse('<?= addslashes(json_encode($companies_addresses, JSON_HEX_QUOT | JSON_HEX_APOS)) ?>');
    
    var company_address_map__myPlacemark;
    var company_address_map__map;

    var prod_search_map__point_lat =<?= (float) $geoUserData['lat'] ?>;
    var prod_search_map__point_long =<?= (float) $geoUserData['long'] ?>;

    document.addEventListener('DOMContentLoaded', function () {
        ymaps.ready(company_address_map__init);
    });
    function company_address_map__init() {
        //user has a point
        if (prod_search_map__point_lat > 0 && prod_search_map__point_long > 0) {
            company_address_map__createMap({
                center: [prod_search_map__point_lat, prod_search_map__point_long],
                zoom: 10
            });
        } else {
            //user not login or user not have a point
            ymaps.geolocation.get().then(function (res) {
                var mapContainer = $('#company_address_map__block'),
                        bounds = res.geoObjects.get(0).properties.get('boundedBy'),
                        // Рассчитываем видимую область для текущей положения пользователя.
                        mapState = ymaps.util.bounds.getCenterAndZoom(
                                bounds,
                                [mapContainer.width(), mapContainer.height()]
                                );
                mapState.zoom = 10;
                company_address_map__createMap(mapState);
            }, function (e) {
                // Если местоположение невозможно получить, то просто создаем карту.
                company_address_map__createMap({
                    center: [55, 37],
                    zoom: 10
                });
            });
        }
    }

    function company_address_map__createMap(state) {
        company_address_map__map = new ymaps.Map('company_address_map__block', state);

        var clusterer = new ymaps.Clusterer({
            /**
             * Через кластеризатор можно указать только стили кластеров,
             * стили для меток нужно назначать каждой метке отдельно.
             * @see https://api.yandex.ru/maps/doc/jsapi/2.1/ref/reference/option.presetStorage.xml
             */
            preset: 'islands#redClusterIcons',
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

        var pms = [];
        $.each(company_addresses, function (i, addr) {
            var pm = new ymaps.Placemark([addr.geo_lat, addr.geo_long], {
                balloonContent: '<a href="' + companies_urls[addr.company_id] + '" class="text-dark helper-font-bold">' + addr.c_name + '</a><br>'
                        + '<span class="icon-map-marker"></span> ' + addr.dsc + '<br>'
                        + '<span class="icon-time"></span> ' + addr.work_time + '<br>',
                iconContent: (addr.c_name).substring(0, 128)
            }, {
                //preset: 'islands#icon',
                //preset: 'islands#violetDotIconWithCaption',
                preset: 'islands#redStretchyIcon'
                        //iconColor: '#0095b6'
            });
            pms.push(pm);
        });
        clusterer.add(pms);
        company_address_map__map.geoObjects.add(clusterer);
        ///company_address_map__map.setZoom(2);

        /*if (prod_search_map__distance > 0 && prod_search_map__point_lat > 0 && prod_search_map__point_long > 0) {
         var circle = new ymaps.Circle(
         [[prod_search_map__point_lat, prod_search_map__point_long], 1000 * prod_search_map__distance],
         {},
         {
         fillColor: '111111',
         opacity: 0.2
         }
         );
         company_address_map__map.geoObjects.add(circle);
         }*/

    }

</script>

