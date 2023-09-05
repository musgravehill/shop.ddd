<?php

namespace app\controllers;

use app\components\Brand\Domain\Contract\BrandRepositoryInterface;
use app\components\Brand\Domain\ValueObject\BrandId;
use app\components\BrandCategory\App\Form\FormBrandCategoryCr;
use app\components\BrandCategory\App\Form\FormBrandCategoryU;
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
use app\components\BrandCategory\Domain\Contract\BrandCategoryRepositoryInterface;
use app\components\BrandCategory\Domain\Entity\BrandCategory;
use app\components\BrandCategory\Domain\ValueObject\BrandCategoryDsc;
use app\components\BrandCategory\Domain\ValueObject\BrandCategoryId;
use app\components\BrandCategory\Domain\ValueObject\BrandCategoryLogoFn;
use app\components\BrandCategory\Domain\ValueObject\BrandCategoryName;
use app\components\BrandCategory\Domain\ValueObject\SearchOffers;
use app\components\BrandCategory\Domain\ValueObject\SearchPriceFractionalMax;
use app\components\BrandCategory\Domain\ValueObject\SearchPriceFractionalMin;
use app\components\Search\Domain\SearchQuery;
use app\components\BrandCategory\Infrastructure\BrandCategoryImgService;
use app\components\HelperCache;
use app\components\SalePersonalBrandCategory\Domain\Contract\SalePersonalBrandCategoryRepositoryInterface;
use app\components\Shared\Domain\ValueObject\CountOnPage;
use app\components\Shared\Domain\ValueObject\PageNumber;
use app\components\Shared\Domain\ValueObject\Ufu;
use app\components\Search\Domain\SortId;
use app\components\Search\Domain\TemplateTypeId;
use Exception;
use yii\web\UploadedFile;

class BrandcategoryController extends Controller
{
    private BrandRepositoryInterface $brandRepository;
    private BrandCategoryRepositoryInterface $brandCategoryRepository;
    private SalePersonalBrandCategoryRepositoryInterface $salePersonalBrandCategoryRepository;

    public function __construct(
        $id,
        $module,
        BrandRepositoryInterface $brandRepository,
        BrandCategoryRepositoryInterface $brandCategoryRepository,
        SalePersonalBrandCategoryRepositoryInterface $salePersonalBrandCategoryRepository,
        $config = []
    ) {
        $this->brandRepository = $brandRepository;
        $this->brandCategoryRepository = $brandCategoryRepository;
        $this->salePersonalBrandCategoryRepository = $salePersonalBrandCategoryRepository;
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
                        'actions' => ['brand', 'view', 'showcase'],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                    [
                        'actions' => ['list_adm', 'cr', 'u'],
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

    public function actionBrand($id)
    {
        $brandIdRaw = BrandId::prepare($id);
        $brandId = BrandId::fromString($brandIdRaw);
        $brandCategorys = $this->brandCategoryRepository->getByBrandId(
            brandId: $brandId
        );
        $data = '';
        foreach ($brandCategorys as $brandCategory) {
            /** @var BrandCategory $brandCategory */

            $salePersonalBrandCategoryPercent = $this->salePersonalBrandCategoryRepository->getPercent(
                userId: IdentityService::userId(),
                brandId: $brandId,
                brandCategoryId: $brandCategory->getId(),
            );

            $data .= $this->renderPartial('/brandcategory/_item', [
                'brandCategory' => $brandCategory,
                'salePersonalBrandCategoryPercent' => $salePersonalBrandCategoryPercent,
            ]);
        }
        return $data;
    }

    public function actionShowcase()
    {
        $cacheId = HelperCache::getCacheKey(HelperCache::BRANDCATEGORY_SHOWCASE, []);
        $data = Yii::$app->cache->get($cacheId);

        if ($data === false) {
            $cop = new CountOnPage(12);
            $brandCategorys = $this->brandCategoryRepository->popular(
                cop: $cop
            );

            $data = '';
            foreach ($brandCategorys as $brandCategory) {
                /** @var BrandCategory $brandCategory */
                $data .= $this->renderPartial('/brandcategory/_item', [
                    'brandCategory' => $brandCategory,
                    'salePersonalBrandCategoryPercent' => null,
                ]);
            }

            Yii::$app->cache->set($cacheId, $data, 3600);
        }

        return $data;
    }

    public function actionView($id)
    {
        $brandCategoryIdRaw = BrandCategoryId::prepare($id);
        $ufuFromClientRaw = HelperY::getGet('ufu', '-');
        $filterQueryClientRaw = HelperY::getGet('filterQueryClient', null);

        $ufuFromClient = Ufu::hydrateExisting($ufuFromClientRaw);
        $brandCategoryId = BrandCategoryId::fromString($brandCategoryIdRaw);
        $brandCategory = $this->brandCategoryRepository->getById($brandCategoryId);
        if (is_null($brandCategory) || !$ufuFromClient->isEqualsTo($brandCategory->getUfu())) { // ufu==ufu
            throw new HttpException(404, 'Not found');
        }

        $brandId = $brandCategory->getBrandId();
        $brand = $this->brandRepository->getById($brandId);
        if (is_null($brand)) {
            throw new HttpException(404, 'Not found');
        }

        $this->brandCategoryRepository->incrementViewIdx($brandCategoryId);

        $searchQuerySeo = $brand->getName()->getName();
        if ($filterQueryClientRaw) {
            $searchQuerySeo .= ' ' . $filterQueryClientRaw;
        } else {
            $searchQuerySeo .= ' ' .  $brandCategory->getSearchQuery()->getSearchQuery();
        }
        $searchQuerySeo = SearchQuery::prepare($searchQuerySeo);

        $paramsSeo = [
            'page' => 1,
            'countOnPage' => 10,
            'searchQuery' => $searchQuerySeo,
            'priceMin' => $brandCategory->getSearchPriceFractionalMin()->getSearchPriceFractionalMin(),
            'priceMax' => $brandCategory->getSearchPriceFractionalMax()->getSearchPriceFractionalMax(),
            'supplierId' => HelperY::params('seoSupplierId'),
            'brandId' => $brand->getId()->getId(),
            'brandCategoryId' => $brandCategory->getId()->getId(),
            'quantityAvailableMin' => 0,
            'sortId' => (SortId::ProductRelevantDesc)->value,
            'templateTypeId' => (TemplateTypeId::Seo)->value,
        ];
        $searchProductsSeo__url = $paramsSeo;
        $searchProductsSeo__url[0] = 'product/find'; //for URL create                         

        return $this->render('view', [
            'brandCategory' => $brandCategory,
            'brand' => $brand,
            'searchProductsSeo__url' => $searchProductsSeo__url,
            'filterQueryClient' => SearchQuery::prepare($filterQueryClientRaw),
        ]);
    }

    public function actionList_adm()
    {
        $searchQueryRaw = SearchQuery::prepare(HelperY::getGet('searchQuery', ''));
        $pageRaw = PageNumber::prepare(HelperY::getGet('page', 1));
        $brandIdRaw = BrandId::prepare(HelperY::getGet('brandId', null));

        $page = new PageNumber($pageRaw);
        $cop = new CountOnPage(100);
        $brandCategorys = $this->brandCategoryRepository->list(
            page: $page,
            cop: $cop,
            q: new SearchQuery($searchQueryRaw),
            brandId: BrandId::fromString($brandIdRaw)
        );

        $urlParams = [];
        $urlParams[0] = 'brandcategory/list_adm'; //for URL create
        $urlParams['page'] = $pageRaw;
        $urlParams['searchQuery'] = $searchQueryRaw;
        $urlParams['brandId'] = $brandIdRaw;

        $brandIdsNames = $this->brandRepository->idsNames();

        return $this->render('list_adm', [
            'brandCategorys' => $brandCategorys,
            'urlParams' => $urlParams,
            'brandIdsNames' => $brandIdsNames,
        ]);
    }


    public function actionU($id)
    {
        $brandCategoryIdRaw = BrandCategoryId::prepare($id);

        $brandCategoryId = BrandCategoryId::fromString($brandCategoryIdRaw);
        $brandCategory = $this->brandCategoryRepository->getById($brandCategoryId);
        if (is_null($brandCategory)) {
            throw new HttpException(404, 'Not found');
        }

        $brandId = $brandCategory->getBrandId();
        $brand = $this->brandRepository->getById($brandId);
        if (is_null($brand)) {
            throw new HttpException(404, 'Not found');
        }

        $formBrandCategoryU = new FormBrandCategoryU();
        $formBrandCategoryU->brandId = $brandCategory->getBrandId()->getId();
        $formBrandCategoryU->name = $brandCategory->getName()->getName();
        $formBrandCategoryU->dsc = $brandCategory->getDsc()->getDsc();
        $formBrandCategoryU->searchQuery = $brandCategory->getSearchQuery()->getSearchQuery();
        $formBrandCategoryU->searchPriceMin = $brandCategory->getSearchPriceFractionalMin()->getSearchPriceFractionalMin() / 100.0;
        $formBrandCategoryU->searchPriceMax = $brandCategory->getSearchPriceFractionalMax()->getSearchPriceFractionalMax() / 100.0;
        $formBrandCategoryU->searchOffers = $brandCategory->getSearchOffers()->getSearchOffers();

        if ($formBrandCategoryU->load(Yii::$app->request->post())) {
            $formBrandCategoryU->imageFile = UploadedFile::getInstance($formBrandCategoryU, 'imageFile');
            if ($formBrandCategoryU->validate()) {

                $brandCategory = $brandCategory->change(
                    brandId: BrandId::fromString($formBrandCategoryU->brandId),
                    name: new BrandCategoryName(BrandCategoryName::prepare($formBrandCategoryU->name)),
                    dsc: new BrandCategoryDsc(BrandCategoryDsc::prepare($formBrandCategoryU->dsc)),
                    searchQuery: new SearchQuery(SearchQuery::prepare($formBrandCategoryU->searchQuery)),
                    searchPriceFractionalMin: new SearchPriceFractionalMin(intval(100.0 * $formBrandCategoryU->searchPriceMin)),
                    searchPriceFractionalMax: new SearchPriceFractionalMax(intval(100.0 * $formBrandCategoryU->searchPriceMax)),
                    searchOffers: new SearchOffers(SearchOffers::prepare($formBrandCategoryU->searchOffers))
                );
                $brandCategory = $this->brandCategoryRepository->save($brandCategory);
                if (is_null($brandCategory)) {
                    throw new Exception(' Save:error. ');
                }

                //img if uploaded
                if (!is_null($formBrandCategoryU->imageFile)) {
                    BrandCategoryImgService::delete($brandCategory->getLogoFn());

                    $logoFnNew = BrandCategoryLogoFn::new();
                    $brandCategory = $brandCategory->changeLogoFn($logoFnNew);
                    $brandCategory = $this->brandCategoryRepository->save($brandCategory);
                    if (is_null($brandCategory)) {
                        throw new Exception(' Save:error. ');
                    }

                    $pathFull = BrandCategoryImgService::getPathFull($brandCategory->getLogoFn());
                    $formBrandCategoryU->imageFile->saveAs($pathFull);
                }

                Yii::$app->session->addFlash('success', 'Сохранено!');
                return $this->redirect(Url::to(['brandcategory/u', 'id' => $brandCategory->getId()->getId(),]));
            }
        }

        $brandIdsNames = $this->brandRepository->idsNames();

        return $this->render('u', [
            'brandCategory' => $brandCategory,
            'formBrandCategoryU' => $formBrandCategoryU,
            'brandIdsNames' => $brandIdsNames,
            'brand' => $brand,
        ]);
    }

    public function actionCr()
    {
        $formBrandCategoryCr = new FormBrandCategoryCr();
        if ($formBrandCategoryCr->load(Yii::$app->request->post())) {
            $formBrandCategoryCr->imageFile = UploadedFile::getInstance($formBrandCategoryCr, 'imageFile');
            if ($formBrandCategoryCr->validate()) {

                $brandCategory = BrandCategory::new(
                    brandId: BrandId::fromString($formBrandCategoryCr->brandId),
                    name: new BrandCategoryName(BrandCategoryName::prepare($formBrandCategoryCr->name)),
                    dsc: new BrandCategoryDsc(BrandCategoryDsc::prepare($formBrandCategoryCr->dsc)),
                    searchQuery: new SearchQuery(SearchQuery::prepare($formBrandCategoryCr->searchQuery)),
                    searchPriceFractionalMin: new SearchPriceFractionalMin(intval(100.0 * $formBrandCategoryCr->searchPriceMin)),
                    searchPriceFractionalMax: new SearchPriceFractionalMax(intval(100.0 * $formBrandCategoryCr->searchPriceMax)),
                    searchOffers: new SearchOffers(SearchOffers::prepare($formBrandCategoryCr->searchOffers)),
                    logoFn: BrandCategoryLogoFn::fromString(null)
                );
                $brandCategory = $this->brandCategoryRepository->save($brandCategory);
                if (is_null($brandCategory)) {
                    throw new Exception(' Save:error. ');
                }

                //img if uploaded
                if (!is_null($formBrandCategoryCr->imageFile)) {
                    BrandCategoryImgService::delete($brandCategory->getLogoFn());

                    $logoFnNew = BrandCategoryLogoFn::new();
                    $brandCategory = $brandCategory->changeLogoFn($logoFnNew);
                    $brandCategory = $this->brandCategoryRepository->save($brandCategory);
                    if (is_null($brandCategory)) {
                        throw new Exception(' Save:error. ');
                    }

                    $pathFull = BrandCategoryImgService::getPathFull($brandCategory->getLogoFn());
                    $formBrandCategoryCr->imageFile->saveAs($pathFull);
                }

                Yii::$app->session->addFlash('success', 'Сохранено!');
                return $this->redirect(Url::to(['brandcategory/u', 'id' => $brandCategory->getId()->getId(),]));
            }
        }

        $brandIdsNames = $this->brandRepository->idsNames();

        return $this->render(
            'cr',
            [
                'formBrandCategoryCr' => $formBrandCategoryCr,
                'brandIdsNames' => $brandIdsNames,
            ]
        );
    }
}

/*

$page = new PageNumber(1);
        $cop = new CountOnPage(10000);
        $brandCategorys = $this->brandCategoryRepository->list(
            page: $page,
            cop: $cop,
            q: new SearchQuery(''),
            brandId: BrandId::fromString(null)
        );
        foreach ($brandCategorys as $brandCategory) {
            $nameString = HelperY::sanitizeText($brandCategory->getName()->getName());

            if ($nameString <> $brandCategory->getName()->getName()) {
                echo 'name: ' . $brandCategory->getName()->getName() . '===> ' . $nameString . '<br>' . PHP_EOL;
            }

            $name = new BrandCategoryName($nameString);
            $ufu = Ufu::fromRu($nameString);


            if ($ufu->getUfu() <> $brandCategory->getUfu()->getUfu()) {
                echo 'ufu: ' . $brandCategory->getUfu()->getUfu() . '===> ' . $ufu->getUfu()  . '<br>' . PHP_EOL;
            }

            $brandCategory = $brandCategory->setName($name)->setUfu($ufu);
            $this->brandCategoryRepository->save($brandCategory);
        }

        */

        /*
$page = new PageNumber(1);
        $cop = new CountOnPage(100000);
        $brandCategorys = $this->brandCategoryRepository->list(
            page: $page,
            cop: $cop,
            q: new SearchQuery(''),
            brandId: BrandId::fromString(null)
        );
        foreach ($brandCategorys as $brandCategory) {
            $logoFnOld = Yii::getAlias('@webroot' . '/imgbrandcategory/' . $brandCategory->getId()->getId() . '.jpg');
            $logoFnNew = BrandCategoryLogoFn::new();
            $brandCategory = $brandCategory->changeLogoFn($logoFnNew);
            $brandCategory = $this->brandCategoryRepository->save($brandCategory);
            $pathFull = HelperBrandCategoryImg::getPathFull($brandCategory->getLogoFn());
            copy($logoFnOld, $pathFull);
        }
        die;
*/
