<?php

namespace app\controllers;

use app\components\Brand\App\Form\FormBrandCr;
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
use app\components\Brand\Domain\Contract\BrandRepositoryInterface;
use app\components\Brand\Domain\ValueObject\BrandDsc;
use app\components\Brand\Domain\ValueObject\BrandExternalId;
use app\components\Brand\Domain\ValueObject\BrandId;
use app\components\Brand\Domain\ValueObject\BrandName;
use app\components\Brand\Infrastructure\BrandImgService;
use app\components\HelperCache;
use app\components\Shared\Domain\ValueObject\CountOnPage;
use app\components\Shared\Domain\ValueObject\PageNumber;
use app\components\Shared\Domain\ValueObject\Ufu;
use Exception;
use yii\web\UploadedFile;
use app\components\Brand\App\Form\FormBrandU;
use app\components\Brand\Domain\Entity\Brand;
use app\components\Brand\Domain\ValueObject\BrandLogoFn;
use app\components\Search\Domain\SortId;

class BrandController extends Controller
{
    private BrandRepositoryInterface $brandRepository;

    public function __construct(
        $id,
        $module,
        BrandRepositoryInterface $brandRepository,
        $config = []
    ) {
        $this->brandRepository = $brandRepository;
        parent::__construct($id, $module, $config);
    }

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
                        'actions' => ['list', 'view', 'showcase'],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                    [
                        'actions' => ['list_adm', 'u', 'cr'],
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

    public function actionShowcase()
    {
        $cacheId = HelperCache::getCacheKey(HelperCache::BRAND_SHOWCASE, []);
        $data = Yii::$app->cache->get($cacheId);

        if ($data === false) {
            $cop = new CountOnPage(12);
            $brands = $this->brandRepository->popular(
                cop: $cop
            );

            $data = '';
            foreach ($brands as $brand) {
                /** @var Brand $brand */
                $data .= $this->renderPartial('/brand/_item', [
                    'brand' => $brand,
                ]);
            }

            Yii::$app->cache->set($cacheId, $data, 3600);
        }

        return $data;
    }

    public function actionList()
    {
        $pageRaw = PageNumber::prepare(HelperY::getGet('page', 1));

        $page = new PageNumber($pageRaw);
        $cop = new CountOnPage(999);
        $brands = $this->brandRepository->list(page: $page, cop: $cop);

        $urlParams = [];
        $urlParams[0] = 'brand/list'; //for URL create
        $urlParams['page'] = $pageRaw;

        return $this->render('list', [
            'brands' => $brands,
            'urlParams' => $urlParams,
        ]);
    }

    public function actionView($id)
    {
        $brandIdRaw = BrandId::prepare($id);
        $ufuFromClientRaw = HelperY::getGet('ufu', '-');

        $brandId = BrandId::fromString($brandIdRaw);
        $ufuFromClient = Ufu::hydrateExisting($ufuFromClientRaw);

        $brand = $this->brandRepository->getById($brandId);
        if (is_null($brand) || !$ufuFromClient->isEqualsTo($brand->getUfu())) { // ufu==ufu
            throw new HttpException(404, 'Not found');
        }

        $this->brandRepository->incrementViewIdx($brandId);

        return $this->render('view', [
            'brand' => $brand,
        ]);
    }

    public function actionList_adm()
    {
        $pageRaw = PageNumber::prepare(HelperY::getGet('page', 1));

        $page = new PageNumber($pageRaw);
        $cop = new CountOnPage(100);
        $brands = $this->brandRepository->list(page: $page, cop: $cop);

        $urlParams = [];
        $urlParams[0] = 'brand/list_adm'; //for URL create
        $urlParams['page'] = $pageRaw;

        return $this->render('list_adm', [
            'brands' => $brands,
            'urlParams' => $urlParams,
        ]);
    }

    public function actionU($id)
    {
        $brandIdRaw = BrandId::prepare($id);

        $brandId = BrandId::fromString($brandIdRaw);
        $brand = $this->brandRepository->getById($brandId);
        if (is_null($brand)) {
            throw new HttpException(404, 'Not found');
        }

        $formBrandU = new FormBrandU();
        $formBrandU->externalId = $brand->getExternalId()->getId();
        $formBrandU->name = $brand->getName()->getName();
        $formBrandU->dsc = $brand->getDsc()->getDsc();
        if ($formBrandU->load(Yii::$app->request->post())) {
            $formBrandU->imageFile = UploadedFile::getInstance($formBrandU, 'imageFile');
            if ($formBrandU->validate()) {
                $brand = $brand->change(
                    externalId: BrandExternalId::fromString(BrandExternalId::prepare($formBrandU->externalId)),
                    name: new BrandName(BrandName::prepare($formBrandU->name)),
                    dsc: new BrandDsc(BrandDsc::prepare($formBrandU->dsc))
                );
                $brand = $this->brandRepository->save($brand);
                if (is_null($brand)) {
                    throw new Exception(' Save:error. ');
                }

                //img if uploaded
                if (!is_null($formBrandU->imageFile)) {
                    BrandImgService::delete($brand->getLogoFn());

                    $logoFnNew = BrandLogoFn::new();
                    $brand = $brand->changeLogoFn($logoFnNew);
                    $brand = $this->brandRepository->save($brand);
                    if (is_null($brand)) {
                        throw new Exception(' Save:error. ');
                    }

                    $pathFull = BrandImgService::getPathFull($brand->getLogoFn());
                    $formBrandU->imageFile->saveAs($pathFull);
                }

                Yii::$app->session->addFlash('success', 'Сохранено!');
                return $this->redirect(Url::to(['brand/u', 'id' => $id,]));
            }
        }

        return $this->render('u', [
            'brand' => $brand,
            'formBrandU' => $formBrandU,
        ]);
    }

    public function actionCr()
    {
        $formBrandCr = new FormBrandCr();
        if ($formBrandCr->load(Yii::$app->request->post())) {
            $formBrandCr->imageFile = UploadedFile::getInstance($formBrandCr, 'imageFile');
            if ($formBrandCr->validate()) {
                $brand = Brand::new(
                    externalId: BrandExternalId::fromString(BrandExternalId::prepare($formBrandCr->externalId)),
                    name: new BrandName(BrandName::prepare($formBrandCr->name)),
                    dsc: new BrandDsc(BrandDsc::prepare($formBrandCr->dsc)),
                    logoFn: BrandLogoFn::fromString(null)
                );
                $brand = $this->brandRepository->save($brand);
                if (is_null($brand)) {
                    throw new Exception(' Save:error. ');
                }

                //img if uploaded
                if (!is_null($formBrandCr->imageFile)) {
                    BrandImgService::delete($brand->getLogoFn());

                    $logoFnNew = BrandLogoFn::new();
                    $brand = $brand->changeLogoFn($logoFnNew);
                    $brand = $this->brandRepository->save($brand);
                    if (is_null($brand)) {
                        throw new Exception(' Save:error. ');
                    }

                    $pathFull = BrandImgService::getPathFull($brand->getLogoFn());
                    $formBrandCr->imageFile->saveAs($pathFull);
                }

                Yii::$app->session->addFlash('success', 'Сохранено!');
                return $this->redirect(Url::to(['brand/u', 'id' => $brand->getId()->getId(),]));
            }
        }

        return $this->render('cr', [
            'formBrandCr' => $formBrandCr,
        ]);
    }
}

/*
  $page = new PageNumber(1);
        $cop = new CountOnPage(10000);
        $brands = $this->brandRepository->list(page: $page, cop: $cop);
        foreach ($brands as $brand) {
            $logoFnOld = Yii::getAlias('@webroot' . '/imgbrand/' . $brand->getId()->getId() . '.jpg');
            $logoFnNew = BrandLogoFn::new();
            $brand = $brand->changeLogoFn($logoFnNew);
            $brand = $this->brandRepository->save($brand);
            $pathFull = HelperBrandImg::getPathFull($brand->getLogoFn());
            copy($logoFnOld, $pathFull);
        }
        die;
*/
