<?php

namespace app\controllers;

use app\components\HelperCache;
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
use app\components\Shared\Domain\ValueObject\CountOnPage;
use app\components\Shared\Domain\ValueObject\PageNumber;
//
use app\components\Supplier\Domain\Contract\SupplierRepositoryInterface;
use app\components\Supplier\Domain\Entity\Supplier;
use app\components\Supplier\Domain\ValueObject\CityName;
use app\components\Supplier\Domain\ValueObject\ImgUrl;
use app\components\Supplier\Domain\ValueObject\SupplierDsc;
use app\components\Supplier\Domain\ValueObject\SupplierId;
use app\components\Supplier\Domain\ValueObject\SupplierName;
use DateTimeImmutable;

class SupplierController extends \yii\web\Controller
{

    private SupplierRepositoryInterface $supplierRepository;

    public function __construct(
        $id,
        $module,
        SupplierRepositoryInterface $supplierRepository,
        $config = []
    ) {
        $this->supplierRepository = $supplierRepository;
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        //https://thecodeninja.net/2014/12/simpler-role-based-authorization-in-yii-2-0/
        return [
            'access' => [
                'class' => AccessControl::class,
                // We will override the default rule config with the new AccessRule class
                'ruleConfig' => [
                    'class' => AccessRule::class,
                ],
                'rules' => [
                    [
                        'actions' => ['view', 'list', 'showcase'],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                    [
                        'actions' => ['cr', 'u', 'list_adm'],
                        'allow' => true,
                        'roles' => [RoleTypeId::Admin],
                    ],
                ],
            ],
        ];
    }

    public function actionShowcase()
    {
        $cacheId = HelperCache::getCacheKey(HelperCache::SUPPLIER_SHOWCASE, []);
        $data = Yii::$app->cache->get($cacheId);

        if ($data === false) {
            $cop = new CountOnPage(12);
            $suppliers = $this->supplierRepository->rand(
                cop: $cop
            );

            $data = '';
            foreach ($suppliers as $supplier) {
                /** @var Supplier $supplier */
                $data .= $this->renderPartial('/supplier/_item', [
                    'supplier' => $supplier,
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
        $suppliers = $this->supplierRepository->list(
            page: $page,
            cop: $cop,
            withSeo: false,
        );

        $urlParams = [];
        $urlParams[0] = 'supplier/list'; //for URL create
        $urlParams['page'] = $pageRaw;

        return $this->render('list', [
            'suppliers' => $suppliers,
            'urlParams' => $urlParams,
        ]);
    }

    public function actionView($id)
    {
        $supplierIdRaw = SupplierId::prepare($id);

        $supplierId = SupplierId::fromString($supplierIdRaw);
        $supplier = $this->supplierRepository->getById($supplierId);
        if (is_null($supplier)) {
            throw new HttpException(404, 'Not found');
        }

        return $this->render('view', [
            'supplier' => $supplier,
        ]);
    }

    public function actionU($id)
    {
        $supplierIdRaw = SupplierId::prepare($id);

        $supplierId = SupplierId::fromString($supplierIdRaw);
        $supplier = $this->supplierRepository->getById($supplierId);

        if (is_null($supplier)) {
            throw new HttpException(404, 'Not found');
        }

        if (Yii::$app->request->post()) {
            $name = SupplierName::prepare(HelperY::getPost('name', '-'));
            $dsc = SupplierDsc::prepare(HelperY::getPost('dsc', '-'));
            $imgUrl = ImgUrl::prepare(HelperY::getPost('imgUrl', ''));
            $cityName = CityName::prepare(HelperY::getPost('cityName', ''));

            $supplier = Supplier::hydrateExisting(
                id: $supplier->getId(),
                name: new SupplierName($name),
                dsc: new SupplierDsc($dsc),
                imgUrl: new ImgUrl($imgUrl),
                taskDownloadAt: new DateTimeImmutable(),
                cityName: new CityName($cityName),
            );
            $supplier = $this->supplierRepository->save($supplier);

            if (!is_null($supplier)) {
                Yii::$app->session->addFlash('success', 'Готово!');
                return $this->redirect(Url::to(['supplier/u', 'id' => $supplier->getId()->getId(),]));
            } else {
                Yii::$app->session->addFlash('danger', 'Сохранить не получается!');
            }
        }

        return $this->render('u', [
            'supplier' => $supplier,
        ]);
    }

    public function actionCr()
    {
        if (Yii::$app->request->post()) {
            $name = SupplierName::prepare(HelperY::getPost('name', '-'));
            $dsc = SupplierDsc::prepare(HelperY::getPost('dsc', '-'));
            $imgUrl = ImgUrl::prepare(HelperY::getPost('imgUrl', ''));
            $cityName = CityName::prepare(HelperY::getPost('cityName', ''));

            $supplier = Supplier::new(
                name: new SupplierName($name),
                dsc: new SupplierDsc($dsc),
                imgUrl: new ImgUrl($imgUrl),
                taskDownloadAt: new DateTimeImmutable(),
                cityName: new CityName($cityName),
            );
            $supplier = $this->supplierRepository->save($supplier);

            if (!is_null($supplier)) {
                Yii::$app->session->addFlash('success', 'Готово!');
                return $this->redirect(Url::to(['supplier/u', 'id' => $supplier->getId()->getId(),]));
            } else {
                Yii::$app->session->addFlash('danger', 'Сохранить не получается!');
            }
        }

        return $this->render('cr', []);
    }

    public function actionList_adm()
    {
        $pageRaw = PageNumber::prepare(HelperY::getGet('page', 1));

        $page = new PageNumber($pageRaw);
        $cop = new CountOnPage(999);
        $suppliers = $this->supplierRepository->list(
            page: $page,
            cop: $cop,
            withSeo: false,
        );

        $urlParams = [];
        $urlParams[0] = 'supplier/list'; //for URL create
        $urlParams['page'] = $pageRaw;

        return $this->render('list_adm', [
            'suppliers' => $suppliers,
            'urlParams' => $urlParams,
        ]);
    }
}
