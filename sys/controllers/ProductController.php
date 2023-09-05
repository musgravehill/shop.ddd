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
use app\components\HelperCache;
use app\components\Product\Domain\Contract\ProductRepositoryInterface;
use app\components\Brand\Domain\Contract\BrandRepositoryInterface;
use app\components\Brand\Domain\ValueObject\BrandId;
use app\components\BrandCategory\Domain\Contract\BrandCategoryRepositoryInterface;
use app\components\BrandCategory\Domain\ValueObject\BrandCategoryId;
use app\components\Offer\Domain\Service\OfferFactory;
use app\components\Product\Domain\ValueObject\ProductId;
use app\components\Product\Domain\Entity\Product;
use app\components\Product\Infrastructure\ProductImgService;
use app\components\SalePersonalBrandCategory\Domain\Contract\SalePersonalBrandCategoryRepositoryInterface;
use app\components\Search\Domain\Contract\SearchProductInterface;
use app\components\Search\Domain\SearchProductDto;
use app\components\Search\Domain\SearchQuery;
use app\components\Search\Domain\SortId;
use app\components\Search\Domain\TemplateTypeId;
use app\components\Shared\Domain\ValueObject\CountOnPage;
use app\components\Shared\Domain\ValueObject\Money;
use app\components\Shared\Domain\ValueObject\MoneyСurrency;
use app\components\Shared\Domain\ValueObject\PageNumber;
use app\components\Shared\Domain\ValueObject\QuantityPositive;
use app\components\Shared\Domain\ValueObject\QuantityZeroPositive;
use app\components\Shared\Domain\ValueObject\Ufu;
use app\components\Supplier\Domain\Contract\SupplierRepositoryInterface;
use app\components\Supplier\Domain\ValueObject\SupplierId;
use DateTimeImmutable;

class ProductController extends Controller
{
    private ProductRepositoryInterface $productRepository;
    private ProductImgService $productImgService;
    private BrandRepositoryInterface $brandRepository;
    private BrandCategoryRepositoryInterface $brandCategoryRepository;
    private SupplierRepositoryInterface $supplierRepository;
    private SalePersonalBrandCategoryRepositoryInterface $salePersonalBrandCategoryRepository;

    public function __construct(
        $id,
        $module,
        ProductRepositoryInterface $productRepository,
        ProductImgService $productImgService,
        BrandRepositoryInterface $brandRepository,
        BrandCategoryRepositoryInterface $brandCategoryRepository,
        SupplierRepositoryInterface $supplierRepository,
        SalePersonalBrandCategoryRepositoryInterface $salePersonalBrandCategoryRepository,
        $config = []
    ) {
        $this->productRepository = $productRepository;
        $this->productImgService = $productImgService;
        $this->brandRepository = $brandRepository;
        $this->brandCategoryRepository = $brandCategoryRepository;
        $this->supplierRepository = $supplierRepository;
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
                        'actions' => ['view', 'showcase', 'search', 'find', 'totalcount'],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                    [
                        'actions' => ['list_adm',],
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

    public function actionTotalcount()
    {
        $cacheId = HelperCache::getCacheKey(HelperCache::PRODUCT_TOTALCOUNT, []);
        $data = Yii::$app->cache->get($cacheId);

        if ($data === false) {
            $data = $this->productRepository->totalCount();
            Yii::$app->cache->set($cacheId, $data, 3600);
        }

        return $data;
    }

    public function actionShowcase()
    {
        $brandIdRaw = BrandId::prepare(HelperY::getGet('brandId', null));
        $countOnPageRaw = CountOnPage::prepare(HelperY::getGet('countOnPage', 1));

        $cacheId = HelperCache::getCacheKey(HelperCache::PRODUCT_SHOWCASE, [$brandIdRaw, $countOnPageRaw]);
        $data = Yii::$app->cache->get($cacheId);

        if ($data === false) {
            $brandId = BrandId::fromString($brandIdRaw);
            $cop = new CountOnPage($countOnPageRaw);

            $products = $this->productRepository->popular(
                supplierId: SupplierId::fromString(HelperY::params('seoSupplierId')),
                cop: $cop,
                brandId: $brandId,
                obsoleteСonstraintAt: (new DateTimeImmutable)->modify('-3 days')
            );

            $data = '';
            foreach ($products as $product) {
                /** @var Product $product */

                $imgUrls = $this->productImgService->getImgUrls($product->getId());
                $data .= $this->renderPartial('/product/_item', [
                    'product' => $product,
                    'imgUrls' => $imgUrls,
                ]);
            }

            Yii::$app->cache->set($cacheId, $data, 3600);
        }

        return $data;
    }

    public function actionView($id)
    {
        $productIdRaw = ProductId::prepare($id);

        $productId = ProductId::fromString($productIdRaw);
        $ufuFromClient = Ufu::hydrateExisting(HelperY::getGet('ufu', '-'));
        $product = $this->productRepository->getById($productId);
        if (is_null($product) || !$ufuFromClient->isEqualsTo($product->getUfu())) { // ufu==ufu
            throw new HttpException(404, 'Not found');
        }
        $brand = $this->brandRepository->getById($product->getBrandId());
        $brandCategory = $this->brandCategoryRepository->getById($product->getBrandCategoryId());
        $imgUrls = $this->productImgService->getImgUrls($product->getId());

        $this->productRepository->incrementViewIdx($productId);

        /** @var OfferFactory $offerFactory */
        $userId = IdentityService::userId();
        $offerFactory = Yii::$container->get(OfferFactory::class, ['userId' => $userId]);
        $offerFactory->addItem(
            productId: $product->getId(),
            productQuantity: new QuantityPositive(1)
        );
        $offer = $offerFactory->getOffer();

        $paramsSeo = [
            'page' => 1,
            'countOnPage' => 3,
            'searchQuery' => $product->getName()->getName(),
            'priceMin' => 0,
            'priceMax' => 0,
            'supplierId' => HelperY::params('seoSupplierId'),
            'brandId' => '',
            'brandCategoryId' => '',
            'quantityAvailableMin' => 0,
            'sortId' => (SortId::ProductRelevantDesc)->value,
            'templateTypeId' => (TemplateTypeId::Seo)->value,
        ];
        $searchProductsSeo__url = $paramsSeo;
        $searchProductsSeo__url[0] = 'product/find'; //for URL create

        $paramsCommon = [
            'page' => 1,
            'countOnPage' => 10,
            'searchQuery' => $product->getName()->getName(),
            'priceMin' => intval(0.5 * $product->getPriceSelling()->getFractionalCount()),
            'priceMax' => intval(2 * $product->getPriceSelling()->getFractionalCount()),
            'supplierId' => '',
            'brandId' => '',
            'brandCategoryId' => '',
            'quantityAvailableMin' => 1,
            'sortId' => (SortId::ProductRelevantDesc)->value,
            'templateTypeId' => (TemplateTypeId::Supplier)->value,
        ];

        $searchProductsCommon__url = $paramsCommon;
        $searchProductsCommon__url[0] = 'product/find'; //for URL create    

        $salePersonalBrandCategoryPercent = $this->salePersonalBrandCategoryRepository->getPercent(
            userId: IdentityService::userId(),
            brandId: $product->getBrandId(),
            brandCategoryId: $product->getBrandCategoryId(),
        );

        $supplier = $this->supplierRepository->getById($product->getSupplierId());

        return $this->render('view', [
            'product' => $product,
            'brand' => $brand,
            'brandCategory' => $brandCategory,
            'imgUrls' => $imgUrls,
            'offer' => $offer,
            'searchProductsSeo__url' => $searchProductsSeo__url,
            'searchProductsCommon__url' => $searchProductsCommon__url,
            'salePersonalBrandCategoryPercent' => $salePersonalBrandCategoryPercent,
            'supplier' => $supplier,
        ]);
    }

    public function actionSearch()
    {
        $pageRaw = PageNumber::prepare(HelperY::getGet('page', 1));
        $countOnPageRaw = CountOnPage::prepare(HelperY::getGet('countOnPage', 30));
        $searchQueryRaw = SearchQuery::prepare(HelperY::getGet('searchQuery', ''));
        $priceMinRaw = 100 * intval(HelperY::getGet('priceMin', 0));
        $priceMaxRaw = 100 * intval(HelperY::getGet('priceMax', 0));
        $supplierIdRaw = SupplierId::prepare(HelperY::getGet('supplierId', null));
        $brandIdRaw = BrandId::prepare(HelperY::getGet('brandId', null));
        $brandCategoryIdRaw = BrandCategoryId::prepare(HelperY::getGet('brandCategoryId', null));
        //$quantityAvailableMinRaw = QuantityZeroPositive::prepare(HelperY::getGet('quantityAvailableMin', 0));
        $sortIdRaw = intval(HelperY::getGet('sortId', (SortId::ProductRelevantDesc)->value));

        $priceMinRaw = ($priceMinRaw < 1) ? 1 : $priceMinRaw;

        $paramsSeo = [
            'page' => 1,
            'countOnPage' => 3,
            'searchQuery' => $searchQueryRaw,
            'priceMin' => 0,
            'priceMax' => 0,
            'supplierId' => HelperY::params('seoSupplierId'),
            'brandId' => $brandIdRaw,
            'brandCategoryId' => $brandCategoryIdRaw,
            'quantityAvailableMin' => 0,
            'sortId' => (SortId::ProductRelevantDesc)->value,
            'templateTypeId' => (TemplateTypeId::Seo)->value,
        ];
        $searchProductsSeo__url = $paramsSeo;
        $searchProductsSeo__url[0] = 'product/find'; //for URL create

        $paramsCommon = [
            'page' => $pageRaw,
            'countOnPage' => $countOnPageRaw,
            'searchQuery' => $searchQueryRaw,
            'priceMin' => $priceMinRaw,
            'priceMax' => $priceMaxRaw,
            'supplierId' => $supplierIdRaw,
            'brandId' => $brandIdRaw,
            'brandCategoryId' => $brandCategoryIdRaw,
            'quantityAvailableMin' => 1,
            'sortId' => $sortIdRaw,
            'templateTypeId' => (TemplateTypeId::Supplier)->value,
        ];

        $searchProductsCommon__url = $paramsCommon;
        $searchProductsCommon__url[0] = 'product/find'; //for URL create        

        return $this->render('search', [
            'searchProductsSeo__url' => $searchProductsSeo__url,
            'searchProductsCommon__url' => $searchProductsCommon__url,
        ]);
    }

    // API 
    public function actionFind()
    {
        $userId = IdentityService::userId();

        $pageRaw = PageNumber::prepare(HelperY::getGet('page', 1));
        $countOnPageRaw = CountOnPage::prepare(HelperY::getGet('countOnPage', 30));
        $searchQueryRaw = SearchQuery::prepare(HelperY::getGet('searchQuery', ''));
        $priceMinRaw = intval(HelperY::getGet('priceMin', 0));
        $priceMaxRaw = intval(HelperY::getGet('priceMax', 0));
        $supplierIdRaw = SupplierId::prepare(HelperY::getGet('supplierId', null));
        $brandIdRaw = BrandId::prepare(HelperY::getGet('brandId', null));
        $brandCategoryIdRaw = BrandCategoryId::prepare(HelperY::getGet('brandCategoryId', null));
        $quantityAvailableMinRaw = QuantityZeroPositive::prepare(HelperY::getGet('quantityAvailableMin', 0));
        $sortIdRaw = intval(HelperY::getGet('sortId', (SortId::ProductRelevantDesc)->value));
        $templateTypeIdRaw =  intval(HelperY::getGet('templateTypeId', (TemplateTypeId::Seo)->value));

        $page = new PageNumber($pageRaw);
        $countOnPage = new CountOnPage($countOnPageRaw);
        $searchQuery = new SearchQuery(SearchQuery::prepare($searchQueryRaw));
        $priceMin = new Money($priceMinRaw, MoneyСurrency::RUB);
        $priceMax = new Money($priceMaxRaw, MoneyСurrency::RUB);
        $supplierId = SupplierId::fromString($supplierIdRaw);
        $brandId = BrandId::fromString($brandIdRaw);
        $brandCategoryId = BrandCategoryId::fromString($brandCategoryIdRaw);
        $quantityAvailableMin = new QuantityZeroPositive($quantityAvailableMinRaw);
        $sortId = SortId::tryFrom($sortIdRaw);
        $templateTypeId = TemplateTypeId::tryFrom($templateTypeIdRaw);

        /** @var SearchProductInterface $searchProduct */
        $searchProduct = Yii::$container->get(SearchProductInterface::class);
        /** @var SearchProductDto[] $searchProductDtos */
        $searchProductDtos = $searchProduct->getProducts(
            page: $page,
            countOnPage: $countOnPage,
            searchQuery: $searchQuery,
            priceMin: $priceMin,
            priceMax: $priceMax,
            supplierId: $supplierId,
            brandId: $brandId,
            brandCategoryId: $brandCategoryId,
            quantityAvailableMin: $quantityAvailableMin,
            sortId: $sortId,
            obsoleteСonstraintAt: (new DateTimeImmutable)->modify('-3 days')
        );
        $html = '';
        if (!$searchProductDtos) {
            return $html;
        }

        foreach ($searchProductDtos as $searchProductDto) {
            $productId = ProductId::fromString($searchProductDto->getId());
            $product = $this->productRepository->getById($productId);
            if (is_null($product)) {
                continue;
            }

            $brand = $this->brandRepository->getById($product->getBrandId());
            $brandCategory = $this->brandCategoryRepository->getById($product->getBrandCategoryId());
            $imgUrls = $this->productImgService->getImgUrls($product->getId());

            switch ($templateTypeId) {
                case TemplateTypeId::Seo:
                    /** @var OfferFactory $offerFactory */
                    $offerFactory = Yii::$container->get(OfferFactory::class, ['userId' => $userId]);
                    $offerFactory->addItem(
                        productId: $product->getId(),
                        productQuantity: new QuantityPositive(1)
                    );
                    $offer = $offerFactory->getOffer();

                    $html .= $this->renderPartial('/product/_search_product_seo', [
                        'product' => $product,
                        'brand' => $brand,
                        'brandCategory' => $brandCategory,
                        'imgUrls' => $imgUrls,
                        'searchProductDto' => $searchProductDto,
                        'offer' => $offer,
                    ]);
                    break;
                case TemplateTypeId::Supplier:
                    $supplier = $this->supplierRepository->getById($product->getSupplierId());
                    $html .= $this->renderPartial('/product/_search_product_supplier', [
                        'product' => $product,
                        'brand' => $brand,
                        'brandCategory' => $brandCategory,
                        'imgUrls' => $imgUrls,
                        'searchProductDto' => $searchProductDto,
                        'supplier' => $supplier,
                    ]);
                    break; 
            }
        }

        return $html;
    }

    public function actionList_adm()
    {
        $searchQueryRaw = SearchQuery::prepare(HelperY::getGet('searchQuery', ''));
        $pageRaw = PageNumber::prepare(HelperY::getGet('page', 1));
        $brandIdRaw = BrandId::prepare(HelperY::getGet('brandId', null));
        $brandCategoryIdRaw = BrandCategoryId::prepare(HelperY::getGet('brandCategoryId', null));
        $BBcEmptyOnly = intval(HelperY::getGet('BBcEmptyOnly', 0));
        $supplierIdRaw = SupplierId::prepare(HelperY::getGet('supplierId', null));

        if (Yii::$app->request->post()) {
            $brandIdSet = BrandId::fromString(BrandId::prepare(HelperY::getPost('brandId', null)));
            $brandCategoryIdSet = BrandCategoryId::fromString(BrandCategoryId::prepare(HelperY::getPost('brandCategoryId', null)));
            $productIds = HelperY::getPost('productIds', []);
            foreach ($productIds as $productIdRaw) {
                $productId = ProductId::fromString(ProductId::prepare($productIdRaw));
                $product = $this->productRepository->getById($productId);
                if (is_null($product)) {
                    continue;
                }
                $product = $product->setBrandBrandCategory(
                    brandId: $brandIdSet,
                    brandCategoryId: $brandCategoryIdSet,
                );
                $this->productRepository->save($product);
            }
        }

        $page = new PageNumber($pageRaw);
        $cop = new CountOnPage(100);
        $products = $this->productRepository->list(
            page: $page,
            cop: $cop,
            q: new SearchQuery($searchQueryRaw),
            brandId: BrandId::fromString($brandIdRaw),
            brandCategoryId: BrandCategoryId::fromString($brandCategoryIdRaw),
            BBcEmptyOnly: (1 === $BBcEmptyOnly) ? true : false,
            supplierId: SupplierId::fromString($supplierIdRaw),
        );

        $urlParams = [];
        $urlParams[0] = 'product/list_adm'; //for URL create
        $urlParams['page'] = $pageRaw;
        $urlParams['searchQuery'] = $searchQueryRaw;
        $urlParams['brandId'] = $brandIdRaw;
        $urlParams['brandCategoryId'] = $brandCategoryIdRaw;
        $urlParams['BBcEmptyOnly'] = $BBcEmptyOnly;
        $urlParams['supplierId'] = $supplierIdRaw;

        $brandIdsNames = $this->brandRepository->idsNames();
        $brandCategoryIdsNamesBrands = $this->brandCategoryRepository->idsNamesBrands();
        $supplierIdsNames = $this->supplierRepository->idsNames();

        $productsImgs = [];
        foreach ($products as $product) {
            $imgUrls = $this->productImgService->getImgUrls($product->getId());
            $productsImgs[$product->getId()->getId()] = $imgUrls;
        }

        $page = new PageNumber(1);
        $cop = new CountOnPage(999);
        $suppliers = $this->supplierRepository->list(
            page: $page,
            cop: $cop,
            withSeo: true,
        );

        return $this->render('list_adm', [
            'products' => $products,
            'productsImgs' => $productsImgs,
            'brandIdsNames' => $brandIdsNames,
            'supplierIdsNames' => $supplierIdsNames,
            'brandCategoryIdsNamesBrands' => $brandCategoryIdsNamesBrands,
            'suppliers' => $suppliers,
            'urlParams' => $urlParams,
        ]);
    }
}
