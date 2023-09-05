<?php

namespace app\commands;

use app\components\Brand\Infrastructure\BrandRepository;
use Yii;
use yii\console\Controller;
//
use app\components\HelperY;
use app\components\Product\Infrastructure\ProductImgRepository;
use app\components\Product\Infrastructure\ProductImgService;
use app\components\Product\Infrastructure\ProductRepository;
use app\components\Supplier\Infrastructure\SupplierRepository;
use app\components\SupplierImport\SupplierImport;
use yii\helpers\Url;
use yii\helpers\Html;

class CronController extends Controller
{
    public function actionEveryminute()
    {
        if ((int) date('H') === (int) 20 && (int) date('i') === (int) 59) {
            // \app\components\SitemapGen::generate();
        }

        $supplierImport = new SupplierImport(
            productRepository: new ProductRepository,
            supplierRepository: new SupplierRepository,
            brandRepository: new BrandRepository,
            productImgRepository: new ProductImgRepository,
            productImgService: new ProductImgService
        );

        if (intval(date('i') == 10)) {
            $supplierImport->importProducts();
        } else {            
            $supplierImport->importImgs();
        }

        echo PHP_EOL . round(memory_get_usage(true) / 1048576, 2) . "_MB " . PHP_EOL;
    }
}

// crontab -e
// @reboot /usr/bin/searchd --config /etc/sphinxsearch/conf/sphinx.conf  >/dev/null 2>&1
// 0 */1 * * * indexer --all --rotate --quiet --config /etc/sphinxsearch/conf/sphinx.conf   >/dev/null 2>&1
// * * * * * /var/www/beznalom.com/sys/yii cron/everyminute
// 00 03 * * * /usr/local/sbin/backup-tar.sh
// * * * * * /var/www/vot-tut.ru/shop/yii cron/everyminute

// indexer --all --rotate --config /etc/sphinxsearch/conf/sphinx.conf
