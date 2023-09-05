<?php

declare(strict_types=1);

namespace app\components\SupplierImport;

use app\components\Brand\Domain\Contract\BrandRepositoryInterface;
use app\components\Brand\Domain\ValueObject\BrandName;
use app\components\BrandCategory\Domain\ValueObject\BrandCategoryId;
use app\components\Product\Domain\Contract\ProductImgRepositoryInterface;
use app\components\Product\Domain\Contract\ProductRepositoryInterface;
use app\components\Product\Domain\Entity\Product;
use app\components\Product\Domain\Entity\ProductImg;
use app\components\Product\Domain\ValueObject\ImgExternalUrl;
use app\components\Product\Domain\ValueObject\ImgExternalUrlHash;
use app\components\Product\Domain\ValueObject\ProductDsc;
use app\components\Product\Domain\ValueObject\ProductExternalId;
use app\components\Product\Domain\ValueObject\ProductName;
use app\components\Product\Domain\ValueObject\ProductSku;
use app\components\Product\Infrastructure\ProductImgService;
use app\components\Product\Infrastructure\ProductRepository;
use app\components\Supplier\Domain\Contract\SupplierRepositoryInterface;
use app\components\Supplier\Domain\ValueObject\SupplierId;
use app\components\Supplier\Infrastructure\SupplierRepository;
use app\components\SupplierImport\Contract\SupplierImportInterface;
use app\components\SupplierImport\Parser\ParserFactory;
use app\components\SupplierImport\Parser\ParserItemDto;
use DateTimeImmutable;
use Exception;

class SupplierImport implements SupplierImportInterface
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private SupplierRepositoryInterface $supplierRepository,
        private BrandRepositoryInterface $brandRepository,
        private ProductImgRepositoryInterface $productImgRepository,
        private ProductImgService $productImgService
    ) {
    }

    public function importProducts(): void
    {
        $supplierRepository = new SupplierRepository;
        $supplierId = $supplierRepository->getQueueSupplierId(nextHoursInc: 24);
        if (is_null($supplierId)) {
            return;
        }
        $parserFactory = new ParserFactory;
        $parser = $parserFactory->getParser($supplierId);
        foreach ($parser->run() as $dto) {
            $this->importProduct(
                supplierId: $supplierId,
                dto: $dto
            );
        }
    }

    private function importProduct(SupplierId $supplierId, ParserItemDto $dto): void
    {
        try {
            $product = $this->productRepository->getByExternalData(
                productExternalId: new ProductExternalId($dto->getSku()),
                supplierId: $supplierId
            );

            $brandId = $this->brandRepository->getIdByName($dto->getBrandName());
            $pricePurchase = $dto->getPricePurchase();
            $priceSelling = $dto->getPriceSelling();
            $quantityAvailable = $dto->getQuantityAvailable();
            $sku = new ProductSku($dto->getSku());
            $name = new ProductName($dto->getName());
            $dsc = new ProductDsc($dto->getDsc());

            if (is_null($product)) { //create new product                
                $product = Product::new(
                    externalId: new ProductExternalId($dto->getSku()),
                    supplierId: $supplierId,
                    brandId: $brandId,
                    brandCategoryId: BrandCategoryId::fromString(null),
                    pricePurchase: $pricePurchase,
                    priceSelling: $priceSelling,
                    quantityAvailable: $quantityAvailable,
                    sku: $sku,
                    name: $name,
                    dsc: $dsc,
                    createdAt: new DateTimeImmutable(),
                    updatedAt: new DateTimeImmutable()
                );
            } else { //change exisiting product                    
                $product = $product->updateBySupplier(
                    $brandId,
                    $pricePurchase,
                    $priceSelling,
                    $quantityAvailable,
                    $sku,
                    $name,
                    $dsc
                );
            }
            $product = $this->productRepository->save($product);
            if (is_null($product)) {
                throw new Exception(' Product import error. ');
            }

            //IMG   
            if (filter_var($dto->getImgUrl(), FILTER_VALIDATE_URL) === FALSE) {
            } else {
                $externalUrl = new ImgExternalUrl($dto->getImgUrl());
                $externalUrlHash = ImgExternalUrlHash::generateExternalUrlHash($externalUrl);
                $productImg = $this->productImgRepository->getByExternalData(
                    productId: $product->getId(),
                    externalUrlHash: $externalUrlHash
                );
                if (is_null($productImg)) {
                    $productImg = ProductImg::new(
                        productId: $product->getId(),
                        externalUrl: $externalUrl,
                        externalUrlHash: $externalUrlHash
                    );
                    $productImg = $this->productImgRepository->save($productImg);
                }
            }
        } catch (\Throwable $th) {
            //echo $dto->getName() . '___';
            //echo mb_substr(print_r($th, true), 0, 512, "utf-8");
            //echo PHP_EOL . PHP_EOL;
        }         

        unset($product);
        unset($brandId);
        unset($pricePurchase);
        unset($priceSelling);
        unset($quantityAvailable);
        unset($sku);
        unset($name);
        unset($dsc);
        unset($externalUrl);
        unset($externalUrlHash);
        unset($productImg);
    }

    public function importImgs(): void
    {
        $productImgs = $this->productImgRepository->getQueueTasks(
            nextHoursInc: 24, //this wil be overwrite
            taskCount: 60
        );
        foreach ($productImgs as $productImg) {
            $this->importImg($productImg);
        }
    }

    private function importImg(ProductImg $productImg): void
    {
        try {
            $isDownload = $this->productImgService->download(
                externalUrl: $productImg->getExternalUrl(),
                productImgId: $productImg->getId()
            );
            if ($isDownload) {
                $productImg = $productImg->setDownloadOk();
                $productImg = $this->productImgRepository->save($productImg);
            } else {
                $productImg = $productImg->setDownloadFail();
                $productImg = $this->productImgRepository->save($productImg);
            }
        } catch (\Throwable $th) {
            // print_r($th);
        }              
    }
}
