<?php

declare(strict_types=1);

namespace app\components\Product\Domain\Contract;

use app\components\Product\Domain\ValueObject\ProductImgId;
use app\components\Product\Domain\Entity\ProductImg;
use app\components\Product\Domain\ValueObject\ImgExternalUrlHash;
use app\components\Product\Domain\ValueObject\ProductId;

interface ProductImgRepositoryInterface
{
    public function nextId(): ProductImgId;
    public function save(ProductImg $productImg): ?ProductImg;
    public function getById(ProductImgId $id): ?ProductImg;
    /** @return ProductImg[] */
    public function getByProductId(ProductId $productId): array;
    public function getByExternalData(ProductId $productId, ImgExternalUrlHash $externalUrlHash): ?ProductImg;
    /** @return ProductImg[] */
    public function getQueueTasks(int $nextHoursInc, int $taskCount): array;
}
