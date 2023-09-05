<?php

declare(strict_types=1);

namespace app\components\Product\Domain\Entity;

use app\components\Product\Domain\ValueObject\ImgExternalUrl;
use app\components\Product\Domain\ValueObject\ImgExternalUrlHash;
use app\components\Product\Domain\ValueObject\ProductId;
use app\components\Product\Domain\ValueObject\ProductImgId;
use app\components\Product\Domain\ValueObject\TaskDownloadFailCount;
use DateInterval;
use DateTimeImmutable;
use InvalidArgumentException;

class ProductImg
{
    private function __construct(
        private ProductImgId $id,
        private ProductId $productId,        
        private ImgExternalUrl $externalUrl,
        private ImgExternalUrlHash $externalUrlHash,
        private DateTimeImmutable $taskDownloadAt,
        private TaskDownloadFailCount $taskDownloadFailCount
    ) {
    }

    public static function new(
        ProductId $productId,
        ImgExternalUrl $externalUrl,
        ImgExternalUrlHash $externalUrlHash,
    ): self {
        return new self(
            id: ProductImgId::fromString(null),
            productId: $productId,            
            externalUrl: $externalUrl,
            externalUrlHash: $externalUrlHash,
            taskDownloadAt: new DateTimeImmutable(),
            taskDownloadFailCount: new TaskDownloadFailCount(0),
        );
    }

    public static function hydrateExisting(
        ProductImgId $id,
        ProductId $productId,        
        ImgExternalUrl $externalUrl,
        ImgExternalUrlHash $externalUrlHash,
        DateTimeImmutable $taskDownloadAt,
        TaskDownloadFailCount $taskDownloadFailCount
    ): self {
        if (is_null($id->getId())) {
            throw new InvalidArgumentException(' Rule: id not null. ');
        }
        return new self(
            id: $id,
            productId: $productId,            
            externalUrl: $externalUrl,
            externalUrlHash: $externalUrlHash,
            taskDownloadAt: $taskDownloadAt,
            taskDownloadFailCount: $taskDownloadFailCount,
        );
    }

    public function setDownloadOk(): self
    {
        $taskDownloadAt =  (new DateTimeImmutable())->modify('+10 year');
        return new self(
            id: $this->getId(),
            productId: $this->getProductId(),            
            externalUrl: $this->getExternalUrl(),
            externalUrlHash: $this->getExternalUrlHash(),
            taskDownloadAt: $taskDownloadAt,
            taskDownloadFailCount: new TaskDownloadFailCount(0),
        );
    }

    public function setDownloadFail(): self
    {
        $taskDownloadFailCount = $this->getTaskDownloadFailCount()->incrementFails();
        $taskDownloadAt =  (new DateTimeImmutable())->modify('+10 year');
        switch ($taskDownloadFailCount->getFailCount()) {
            case 1:
                $taskDownloadAt =  (new DateTimeImmutable())->modify('+1 hour');
                break;
            case 2:
                $taskDownloadAt =  (new DateTimeImmutable())->modify('+1 hour');
                break;
            case 3:
                $taskDownloadAt =  (new DateTimeImmutable())->modify('+24 hour');
                break;
            case 4:
                $taskDownloadAt =  (new DateTimeImmutable())->modify('+5 days');
                break;
        }
        
        return new self(
            id: $this->getId(),
            productId: $this->getProductId(),            
            externalUrl: $this->getExternalUrl(),
            externalUrlHash: $this->getExternalUrlHash(),
            taskDownloadAt: $taskDownloadAt,
            taskDownloadFailCount: $taskDownloadFailCount,
        );
    }

    public function getId()
    {
        return $this->id;
    }

    public function getProductId()
    {
        return $this->productId;
    }   

    public function getExternalUrl()
    {
        return $this->externalUrl;
    }

    public function getExternalUrlHash()
    {
        return $this->externalUrlHash;
    }

    public function getTaskDownloadAt()
    {
        return $this->taskDownloadAt;
    }

    public function getTaskDownloadFailCount()
    {
        return $this->taskDownloadFailCount;
    }
}
