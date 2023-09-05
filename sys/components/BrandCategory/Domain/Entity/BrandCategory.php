<?php

declare(strict_types=1);

namespace app\components\BrandCategory\Domain\Entity;

use app\components\Brand\Domain\ValueObject\BrandId;
use app\components\BrandCategory\Domain\ValueObject\BrandCategoryId;
use app\components\BrandCategory\Domain\ValueObject\BrandCategoryDsc;
use app\components\BrandCategory\Domain\ValueObject\BrandCategoryLogoFn;
use app\components\BrandCategory\Domain\ValueObject\BrandCategoryName;
use app\components\BrandCategory\Domain\ValueObject\SearchOffers;
use app\components\BrandCategory\Domain\ValueObject\SearchPriceFractionalMax;
use app\components\BrandCategory\Domain\ValueObject\SearchPriceFractionalMin;
use app\components\Search\Domain\SearchQuery;
use app\components\Shared\Domain\ValueObject\Ufu;
use app\components\Shared\Domain\ValueObject\ViewIdx;
use InvalidArgumentException;

class BrandCategory
{
    private function __construct(
        private BrandCategoryId $id,
        private BrandId $brandId,
        private BrandCategoryName $name,
        private BrandCategoryDsc $dsc,
        private SearchQuery $searchQuery,
        private SearchPriceFractionalMin $searchPriceFractionalMin,
        private SearchPriceFractionalMax $searchPriceFractionalMax,
        private SearchOffers $searchOffers,
        private Ufu $ufu,
        private BrandCategoryLogoFn $logoFn,
        private ViewIdx $viewIdx,
    ) {
    }

    public static function new(
        BrandId $brandId,
        BrandCategoryName $name,
        BrandCategoryDsc $dsc,
        SearchQuery $searchQuery,
        SearchPriceFractionalMin $searchPriceFractionalMin,
        SearchPriceFractionalMax $searchPriceFractionalMax,
        SearchOffers $searchOffers,
        BrandCategoryLogoFn $logoFn,        
    ): self {
        return new self(
            id: BrandCategoryId::fromString(null),
            brandId: $brandId,
            name: $name,
            dsc: $dsc,
            searchQuery: $searchQuery,
            searchPriceFractionalMin: $searchPriceFractionalMin,
            searchPriceFractionalMax: $searchPriceFractionalMax,
            searchOffers: $searchOffers,
            ufu: Ufu::fromRu($name->getName()),
            logoFn: $logoFn,
            viewIdx: new ViewIdx(0),
        );
    }

    public static function hydrateExisting(
        BrandCategoryId $id,
        BrandId $brandId,
        BrandCategoryName $name,
        BrandCategoryDsc $dsc,
        SearchQuery $searchQuery,
        SearchPriceFractionalMin $searchPriceFractionalMin,
        SearchPriceFractionalMax $searchPriceFractionalMax,
        SearchOffers $searchOffers,
        Ufu $ufu,
        BrandCategoryLogoFn $logoFn,
        ViewIdx $viewIdx,
    ): self {
        if (is_null($id->getId())) {
            throw new InvalidArgumentException(' Rule: id not null. ');
        }
        return new self(
            id: $id,
            brandId: $brandId,
            name: $name,
            dsc: $dsc,
            searchQuery: $searchQuery,
            searchPriceFractionalMin: $searchPriceFractionalMin,
            searchPriceFractionalMax: $searchPriceFractionalMax,
            searchOffers: $searchOffers,
            ufu: $ufu,
            logoFn: $logoFn,
            viewIdx: $viewIdx,
        );
    }

    public function change(
        BrandId $brandId,
        BrandCategoryName $name,
        BrandCategoryDsc $dsc,
        SearchQuery $searchQuery,
        SearchPriceFractionalMin $searchPriceFractionalMin,
        SearchPriceFractionalMax $searchPriceFractionalMax,
        SearchOffers $searchOffers
    ): self {
        return new self(
            id: $this->getId(),
            brandId: $brandId,
            name: $name,
            dsc: $dsc,
            searchQuery: $searchQuery,
            searchPriceFractionalMin: $searchPriceFractionalMin,
            searchPriceFractionalMax: $searchPriceFractionalMax,
            searchOffers: $searchOffers,
            ufu: Ufu::fromRu($name->getName()),
            logoFn: $this->getLogoFn(),
            viewIdx: $this->getViewIdx(),
        );
    }

    public function changeLogoFn(
        BrandCategoryLogoFn $logoFn
    ): self {
        return new self(
            id: $this->getId(),
            brandId: $this->getBrandId(),
            name: $this->getName(),
            dsc: $this->getDsc(),
            searchQuery: $this->getSearchQuery(),
            searchPriceFractionalMin: $this->getSearchPriceFractionalMin(),
            searchPriceFractionalMax: $this->getSearchPriceFractionalMax(),
            searchOffers: $this->getSearchOffers(),
            ufu: $this->getUfu(),
            logoFn: $logoFn,
            viewIdx: $this->getViewIdx(),
        );
    }

    public function getId()
    {
        return $this->id;
    }

    public function getBrandId()
    {
        return $this->brandId;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDsc()
    {
        return $this->dsc;
    }

    public function getSearchQuery()
    {
        return $this->searchQuery;
    }

    public function getSearchPriceFractionalMin()
    {
        return $this->searchPriceFractionalMin;
    }

    public function getSearchPriceFractionalMax()
    {
        return $this->searchPriceFractionalMax;
    }

    public function getSearchOffers()
    {
        return $this->searchOffers;
    }

    public function getUfu(): Ufu
    {
        return $this->ufu;
    }

    public function setName($name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setUfu($ufu): self
    {
        $this->ufu = $ufu;
        return $this;
    }

    public function getLogoFn()
    {
        return $this->logoFn;
    }

    public function getViewIdx(): ViewIdx
    {
        return $this->viewIdx;
    }
}
