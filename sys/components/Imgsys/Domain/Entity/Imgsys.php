<?php

declare(strict_types=1);

namespace app\components\Imgsys\Domain\Entity;

use app\components\Imgsys\Domain\ValueObject\ImgsysId;
use app\components\Imgsys\Domain\ValueObject\ImgsysTags;
use InvalidArgumentException;

/** @psalm-immutable */
final class Imgsys
{
    //self-validation
    private function __construct(
        private readonly ImgsysId $id,
        private readonly ImgsysTags $tags
    ) {
    }

    public static function newImgsys(
        ImgsysTags $tags
    ): self {
        return new self(
            id: ImgsysId::fromString(null),
            tags: $tags
        );
    }

    public static function hydrateExisting(
        ImgsysId $id,
        ImgsysTags $tags
    ): self {
        if (is_null($id->getId())) {
            throw new InvalidArgumentException('Rule: id not null.');
        }
        return new self(
            id: $id,
            tags: $tags
        );
    }

    public function changeImgsys(
        ImgsysTags $tags
    ): self {
        return new self(
            id: $this->getId(),
            tags: $tags
        );
    }


    public function getId(): ImgsysId
    {
        return $this->id;
    }

    public function getTags(): ImgsysTags
    {
        return $this->tags;
    }
}
