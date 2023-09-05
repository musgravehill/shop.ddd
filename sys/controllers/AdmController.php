<?php

namespace app\controllers;

use app\components\Brand\Infrastructure\BrandRepository;
use app\components\Currency\CurrencyService;
use Yii;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\components\IAA\Authorization\AccessRule;
use app\components\IAA\Authentication\Model\ValueObject\RoleTypeId;
use app\components\IAA\Authentication\Service\IdentityService;
//
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;
//
use app\components\Product\Infrastructure\ProductImgRepository;
use app\components\Product\Infrastructure\ProductImgService;
use app\components\Product\Infrastructure\ProductMigration;
use app\components\Product\Infrastructure\ProductRepository;
use app\components\Shared\Domain\ValueObject\Money;
use app\components\Shared\Domain\ValueObject\MoneyСurrency;
use app\components\Sphinx\SphinxSearch;
use app\components\Supplier\Domain\ValueObject\SupplierId;
use app\components\Supplier\Infrastructure\SupplierRepository;
use app\components\SupplierImport\Parser\Parser_0188f85ca3ff72edbcc72e1d8cffc836;
use app\components\SupplierImport\Parser\ParserFactory;
use app\components\SupplierImport\Parser\ParserItemDto;
use app\components\SupplierImport\SupplierImport;

class AdmController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class,
                ],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => [RoleTypeId::Admin],
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [ //for config ErrorHandler 'site/error'
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength' => 3,
                'maxLength' => 4,
                'offset' => 4,
            ],
        ];
    }

    public function actionIndex()
    {
        // SELECT count(id) FROM `product` WHERE `supplierId`= '0188f85c-a3ff-72ed-bcc7-2e1d8cffc836';

        /*$p = new Parser_0188f85ca3ff72edbcc72e1d8cffc836;
        foreach ($p->run() as $dto) {            
            echo $dto->getSku() . PHP_EOL;
        }*/

        /*
        $currencyService = new CurrencyService;
        $m = $currencyService->convertToRub(
            new Money(
                fractionalCount: 1,
                currency: MoneyСurrency::USD,
            )
        );
        */
        /*
        $supplierImport = new SupplierImport(
            productRepository: new ProductRepository,
            supplierRepository: new SupplierRepository,
            brandRepository: new BrandRepository,
            productImgRepository: new ProductImgRepository,
            productImgService: new ProductImgService
        );

        $supplierImport->importProducts();
        $supplierImport->importImgs();
        */    
        // ProductMigration::migrate();

        // $brandRepository = new BrandRepository;
        // $brandRepository->generateNameCanonicals();         

        return $this->render('index', []);
    }
}
