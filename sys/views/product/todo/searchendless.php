<?php

use yii\helpers\Html;
use app\components\HelperY;
use yii\helpers\Url;
//
use app\models\ProductHelper;

$this->title = 'Поиск: ' . Html::encode($urlParams['q']);

\Yii::$app->view->registerMetaTag([
    'name' => 'keywords',
    'content' => Html::encode($urlParams['q']) . ' СпецДилер купить'
]);
\Yii::$app->view->registerMetaTag([
    'name' => 'description',
    'content' => Html::encode($urlParams['q']) . ' СпецДилер купить'
]);
?>

<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-5 col-xl-5 mt-1 mb-2">
        <form method="GET" action="<?= Url::toRoute(['product/searchendless']) ?>">
            <div class="form-row">                
                <div class="col-auto">
                    <input value="<?= Html::encode($urlParams['q']) ?>" type="text" name="q" class="form-control mr-1" placeholder="товар, услуга" style="width: 315px;">
                </div>                
            </div>
            <div class="form-row mt-1">
                <div class="col-auto">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Цена от</div>
                        </div>
                        <input zero2null title="от" placeholder="" name="price_min" type="number" class="form-control" value="<?= (int) $urlParams['price_min'] ?>" style="width: 90px;" step="1" />
                    </div>
                </div>
                <div class="col-auto">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">до</div>
                        </div>
                        <input zero2null title="до" placeholder="" name="price_max" type="number" class="form-control" value="<?= (int) $urlParams['price_max'] ?>" style="width: 90px;" step="1" />
                    </div>
                </div>
            </div>            
            <!--div class="form-row mt-1">
                <div class="col-auto">                 
                    <input <?= ($urlParams['distance'] === ProductHelper::DISTANCE_km_50) ? 'checked' : '' ?> name="distance" type="radio" class="" value="<?= Html::encode(ProductHelper::DISTANCE_km_50) ?>" /> <?= Html::encode(ProductHelper::DISTANCE_km_50) ?>км
                    <input <?= ($urlParams['distance'] === ProductHelper::DISTANCE_km_15) ? 'checked' : '' ?> name="distance" type="radio" class="ml-3" value="<?= Html::encode(ProductHelper::DISTANCE_km_15) ?>" /> <?= Html::encode(ProductHelper::DISTANCE_km_15) ?>км
                    <input <?= ($urlParams['distance'] === ProductHelper::DISTANCE_km_3) ? 'checked' : '' ?> name="distance" type="radio" class="ml-3" value="<?= Html::encode(ProductHelper::DISTANCE_km_3) ?>" /> <?= Html::encode(ProductHelper::DISTANCE_km_3) ?>км
                </div>
            </div-->            
            <div class="form-row mt-2">
                <div class="col-auto">
                    <button type="submit" class="btn btn-danger zd-none zd-sm-block">
                        Поиск
                    </button>                    
                    <!--button type="submit" class="btn btn-danger d-block d-sm-none">
                        <span class="icon-search"></span>
                    </button-->
                </div>
            </div>
        </form>
    </div>

    <div class="col-12 col-sm-12 col-md-7 col-lg-7 col-xl-7">
        <?=
        $this->context->renderPartial('_search_map_endless', [
            'urlParams' => $urlParams,
        ]);
        ?>
        <hr />
    </div>
</div>

<div helper_scrollVisible="productSearch__sticker"></div>
<header class="d-none helper-box-shadow-bottom" id="productSearch__sticker">
    <nav class="bg-light p-2">
        <span class="d-inline-block helper-font-22 helper-cursor-pointer" title="Вверх" onclick=" $(window).scrollTop(0);">
            <span class="icon-arrow-left helper-font-16"></span>
            <b>
                <?= (isset($urlParams['q'][1])) ? Html::encode($urlParams['q']) : 'поиск' ?>
            </b>
        </span>
        <div class="float-right d-inline-block">
            <a href="<?= Url::toRoute('cart/index') ?>" class="mr-3 helper-no-decor d-inline-block">
                <span
                    cart__top_nav_count_total
                    class="badge badge-danger"
                    style="vertical-align: top; display: none;"
                    >
                </span>
                <span
                    cart__top_nav_icon
                    class="icon-shopping-cart helper-font-20 helper-font-bold"
                    style="display: none;"
                    >
                </span>
            </a>
        </div>
    </nav>
</header>

<div class="row products_search__row" id="productSearch__div_wrapper">
    <div id="productSearch__div_wrapper_placemark" style="height: 800px;"></div>
</div>

<div class="row" id="productSearch__div_wrapper_loading_info">
    <div class="col-12">
        <div class="d-flex justify-content-center">
            <div class="spinner-border text-danger" role="status">
                <span class="sr-only"></span>
            </div> 
            <span class="helper-font-22 ml-3">
                загружаем еще результаты...
            </span>
        </div>
    </div>
</div>


<script type="text/javascript">
    let productSearch__page = 1;
    const productSearch__url = '<?= Url::toRoute($urlParams) ?>';
    let isAllowLoad = true;

    productSearchMap_onReady_promise.then(productSearch__load_chunk_init);

    function productSearch__load_chunk_init() {
        productSearch__load_chunk();
        helper_scrollEndless_functionAdd(productSearch__load_chunk);
    }

    function productSearch__load_chunk() {
        if (!isAllowLoad) {
            return;
        }
        $('#productSearch__div_wrapper_loading_info').show();
        const url = productSearch__url + '&page=' + productSearch__page;
        productSearch__page++;

        fetch(url)
                .then(response => response.json())
                .then(function (data) {
                    if ((data.html).length < 100) {
                        isAllowLoad = false;
                    }
                    $('#productSearch__div_wrapper').append(data.html);
                    productSearch__map_add(data.companies_addresses);
                    productSearch__companies_render(data.companies_addresses);
                    $('#productSearch__div_wrapper_placemark').remove();
                    $('#productSearch__div_wrapper_loading_info').hide();
                    site__cart_render_init();
                    helper_numeral();
                });

        /* $.get(url).done(function (data) {
         $('#productSearch__div_wrapper').append(data.html);
         productSearch__map_add(data.companies_addresses);
         productSearch__companies_render(data.companies_addresses);
         $('#productSearch__div_wrapper_placemark').remove();
         $('#productSearch__div_wrapper_loading_info').hide();
         site__cart_render_init();
         
         });*/
    }


    function productSearch__map_add(companies_addresses) {
        //let companies_addresses_collection = new ymaps.GeoObjectCollection(null, {});
        //companies_addresses_collection.add(pm);   
        var pms = [];
        for (const [key, addr] of Object.entries(companies_addresses)) {
            let companyAddress_id = parseInt(addr.id);
            if (!productSearchMap__companyAdressIds_rendered.includes(companyAddress_id)) {
                let work_times = ''
                        + '' + addr.dow_1_open + '-' + addr.dow_1_close + ';'
                        + '' + addr.dow_2_open + '-' + addr.dow_2_close + ';'
                        + '' + addr.dow_3_open + '-' + addr.dow_3_close + ';'
                        + '' + addr.dow_4_open + '-' + addr.dow_4_close + ';'
                        + '' + addr.dow_5_open + '-' + addr.dow_5_close + ';'
                        + '' + addr.dow_6_open + '-' + addr.dow_6_close + ';'
                        + '' + addr.dow_7_open + '-' + addr.dow_7_close + '';

                let pm = new ymaps.Placemark([addr.geo_lat, addr.geo_long], {
                    balloonContent: '<a href="' + addr.c_url + '" class="text-dark helper-underline helper-font-bold">' + addr.c_name + '</a><br>'
                            + '<span class="icon-map-marker"> </span> ' + addr.dsc + '<br>'
                            + '<p style="height: 150px;width: 150px;">'
                            + '<span class="icon-time"></span>  '
                            + '<span work_times="' + work_times + '" todo ></span>'
                            + '</p>',
                    iconContent: addr.c_name // (addr.c_name).substring(0, 128)
                }, {
                    //preset: 'islands#icon',
                    //preset: 'islands#violetDotIconWithCaption',
                    preset: 'islands#blueStretchyIcon'
                            //iconColor: '#0095b6'
                });
                pms.push(pm);
                productSearchMap__companyAdressIds_rendered.push(companyAddress_id);
            }
        }
        productSearchMap__map.geoObjects.removeAll();
        productSearchMap__cluster.add(pms);
        productSearchMap__map.geoObjects.add(productSearchMap__cluster);
        //productSearchMap__map.geoObjects.add(companies_addresses_collection);

        //покажем положение юзера, если есть данные
        if (commonData__user_lat > 0 && commonData__user_long > 0) {
            const productSearchMap__myPlacemark = new ymaps.Placemark([commonData__user_lat, commonData__user_long], {}, {
                iconLayout: 'default#image',
                iconImageHref: "/img/map-icon-red.png",
                iconImageSize: [24, 24],
                iconImageOffset: [-12, -24]
            });
            productSearchMap__map.geoObjects.add(productSearchMap__myPlacemark);
        }

        ///productSearchMap__map.setZoom(2);
        /*if (prod_productSearchMap__distance > 0 && commonData__user_lat > 0 && commonData__user_long > 0) {
         var circle = new ymaps.Circle(
         [[commonData__user_lat, commonData__user_long], 1000 * prod_productSearchMap__distance],
         {},
         {
         fillColor: '111111',
         opacity: 0.2
         }
         );
         productSearchMap__map.geoObjects.add(circle);
         }*/

    }

    function productSearch__companies_render(companies_addresses) {
        let companies_addrs_count = [];
        for (const [key, addr] of Object.entries(companies_addresses)) {
            let company_id = addr.company_id;
            companies_addrs_count[company_id] = 0; //init element of array

            let company_name = addr.c_name;
            $('span[products_search__item-company-name][company_id=' + company_id + ']').text(company_name);
        }
        for (const [key, addr] of Object.entries(companies_addresses)) {
            let company_id = addr.company_id;
            companies_addrs_count[company_id]++;

            if (companies_addrs_count[company_id] > 1) {
                let addr_wt = 'адресов: ' + companies_addrs_count[company_id] + ' ';
                $('span[products_search__item-company-cities][company_id=' + company_id + ']').html(addr_wt);
            } else {
                let addr_wt = addr.dsc; //+ ' (' + addr.work_time + ') '
                $('span[products_search__item-company-cities][company_id=' + company_id + ']').append(addr_wt);
            }
        }
    }
</script>

