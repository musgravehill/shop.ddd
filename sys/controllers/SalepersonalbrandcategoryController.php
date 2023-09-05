<?php

namespace app\controllers;

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
use app\components\Brand\Domain\ValueObject\BrandId;
use app\components\BrandCategory\Domain\Contract\BrandCategoryRepositoryInterface;
use app\components\BrandCategory\Domain\ValueObject\BrandCategoryId;
use app\components\SalePersonalBrandCategory\Domain\Contract\SalePersonalBrandCategoryRepositoryInterface;
use app\components\SalePersonalBrandCategory\Domain\Entity\SalePersonalBrandCategory;
use app\components\SalePersonalBrandCategory\Domain\ValueObject\SalePercent;
use app\components\SalePersonalBrandCategory\Domain\ValueObject\SalePersonalBrandCategoryId;
use app\components\Shared\Domain\ValueObject\CountOnPage;
use app\components\Shared\Domain\ValueObject\PageNumber;
use app\components\User\Domain\ValueObject\UserId;

class SalepersonalbrandcategoryController extends Controller
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
                        'actions' => ['my'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['cru', 'list_adm'],
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

    public function actionList_adm()
    {
        $pageRaw = PageNumber::prepare(HelperY::getGet('page', 1));
        $brandIdRaw = BrandId::prepare(HelperY::getGet('brandId', null));
        $userIdRaw = UserId::prepare(HelperY::getGet('userId', null));

        $urlParams = [
            'page' => $pageRaw,
            'brandId' => $brandIdRaw,
            'userId' => $userIdRaw,
        ];
        $urlParams[0] = 'salepersonalbrandcategory/list_adm'; //for URL create

        $items = $this->salePersonalBrandCategoryRepository->list(
            page: new PageNumber($pageRaw),
            cop: new CountOnPage(50),
            userId: UserId::fromString($userIdRaw),
            brandId: BrandId::fromString($brandIdRaw),
        );

        $brandIdsNames = $this->brandRepository->idsNames();

        return $this->render('list_adm', [
            'items' => $items,
            'urlParams' => $urlParams,
            'brandIdsNames' => $brandIdsNames,
        ]);
    }

    public function actionCru($id)
    {
        $salePersonalBrandCategoryIdRaw = SalePersonalBrandCategoryId::prepare($id);

        $userIdRaw = '';
        $brandIdRaw = 0;
        $brandCategoryIdRaw = 0;
        $salePercentRaw = 0;

        $salePersonalBrandCategoryId = SalePersonalBrandCategoryId::fromString($salePersonalBrandCategoryIdRaw);
        $salePersonalBrandCategory = $this->salePersonalBrandCategoryRepository->getById($salePersonalBrandCategoryId);
        if (!is_null($salePersonalBrandCategory)) {
            $userIdRaw = $salePersonalBrandCategory->getUserId()->getId();
            $brandIdRaw = $salePersonalBrandCategory->getBrandId()->getId();
            $brandCategoryIdRaw = $salePersonalBrandCategory->getBrandCategoryId()->getId();
            $salePercentRaw = $salePersonalBrandCategory->getSalePercent()->getSalePercent();
        }

        if (Yii::$app->request->post()) {
            $userIdRaw =  UserId::prepare(HelperY::getPost('userId', ''));
            $brandIdRaw = BrandId::prepare(HelperY::getPost('brandId', ''));
            $brandCategoryIdRaw = BrandCategoryId::prepare(HelperY::getPost('brandCategoryId', ''));
            $salePercentRaw = SalePercent::prepare(HelperY::getPost('salePercent', ''));

            if (is_null($salePersonalBrandCategory)) {
                $salePersonalBrandCategory = SalePersonalBrandCategory::new(
                    userId: UserId::fromString($userIdRaw),
                    brandId: BrandId::fromString($brandIdRaw),
                    brandCategoryId: BrandCategoryId::fromString($brandCategoryIdRaw),
                    salePercent: new SalePercent($salePercentRaw),
                );
            } else {
                $salePersonalBrandCategory = $salePersonalBrandCategory->change(
                    userId: UserId::fromString($userIdRaw),
                    brandId: BrandId::fromString($brandIdRaw),
                    brandCategoryId: BrandCategoryId::fromString($brandCategoryIdRaw),
                    salePercent: new SalePercent($salePercentRaw),
                );
            }

            $salePersonalBrandCategory = $this->salePersonalBrandCategoryRepository->save($salePersonalBrandCategory);

            if (is_null($salePersonalBrandCategory)) {
                Yii::$app->session->addFlash('danger', 'Error.');
            } else {
                return $this->redirect(Url::to(['salepersonalbrandcategory/list_adm']));
            }
        }

        $brandIdsNames = $this->brandRepository->idsNames();
        $brandCategoryIdsNamesBrands = $this->brandCategoryRepository->idsNamesBrands();
        return $this->render('cru', [
            'userIdRawSelected' => $userIdRaw,
            'brandIdRawSelected' => $brandIdRaw,
            'brandCategoryIdRawSelected' => $brandCategoryIdRaw,
            'brandCategoryIdsNamesBrands' => $brandCategoryIdsNamesBrands,
            'brandIdsNames' => $brandIdsNames,
            'salePercentRawSelected' => $salePercentRaw,
        ]);
    }

    public function actionMy()
    {
        $brandIdRaw = BrandId::prepare(HelperY::getGet('brandId', null));

        $urlParams = [
            'brandId' => $brandIdRaw,
        ];
        $urlParams[0] = 'salepersonalbrandcategory/my'; //for URL create

        $brandIdsNames = $this->brandRepository->idsNames();

        $items = $this->salePersonalBrandCategoryRepository->list(
            page: new PageNumber(1),
            cop: new CountOnPage(99999),
            userId: IdentityService::userId(),
            brandId: BrandId::fromString($brandIdRaw),
        );

        return $this->render('my', [
            'items' => $items,
            'urlParams' => $urlParams,
            'brandIdsNames' => $brandIdsNames,
        ]);
    }
}
