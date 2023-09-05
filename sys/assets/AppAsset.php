<?php

namespace app\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/bs/bootstrap.min.css',
        'css/whhg-font/css/whhg.css',        
        //last чтобы перекрыть всех
        'css/site.css',
        'css/helper.css',
    ];
    public $js = [
        'js/moment/moment-with-locales.min.js',
        'js/numeral/numeral.js',
        'js/imask/imask.js',
        'js/bs/bootstrap.bundle.min.js',        
        'js/helper.js',
        //'js/brand.js',
        //'js/brandCat.js',
        //'js/salePersonalBrand.js',
        'js/cart.js',
    ];
    public $depends = [
        //'yii\web\JqueryAsset',
        'yii\web\YiiAsset', //add jQuery.2 and yii.js for   jQuery().yiiActiveForm
            //'yii\bootstrap\BootstrapAsset', //css
            //'yii\bootstrap\BootstrapPluginAsset', //js
    ];
    public $jsOptions = [
        'position' => \yii\web\View::POS_END,
        //'async' => 'async',
        'defer' => true,
    ];
    public $cssOptions = [
        'async' => 'async',
            //'position' => \yii\web\View::POS_END
    ];

}
