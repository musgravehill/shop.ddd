<?php

declare(strict_types=1);

namespace app\components\Imgsys\Domain\Contract;

use app\components\Imgsys\Domain\Entity\Imgsys;
use app\components\Imgsys\Domain\ValueObject\ImgsysId;
use app\components\Imgsys\Domain\ValueObject\ImgsysTags;
use app\components\Shared\Domain\ValueObject\CountOnPage;
use app\components\Shared\Domain\ValueObject\PageNumber;

interface ImgsysRepositoryInterface
{
    public function nextId(): ImgsysId;
    public function save(Imgsys $imgsys): ?Imgsys;
    public function getById(ImgsysId $id): ?Imgsys;
    public function list(PageNumber $page, CountOnPage $cop, ImgsysTags $tags): array;
    public function delete(ImgsysId $id): void;
}
