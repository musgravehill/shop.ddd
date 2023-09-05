<?php

declare(strict_types=1);

namespace app\components\UserCompany\Domain\Entity;

use app\components\UserCompany\Domain\ValueObject\Bik;
use app\components\User\Domain\ValueObject\UserId;
use app\components\UserCompany\Domain\ValueObject\Inn;
use app\components\UserCompany\Domain\ValueObject\Kpp;
use app\components\UserCompany\Domain\ValueObject\Name;
use app\components\UserCompany\Domain\ValueObject\Rs;
use app\components\UserCompany\Domain\ValueObject\UserCompanyId;
use InvalidArgumentException;

/** @psalm-immutable */
final class UserCompany
{
    //self-validation
    private function __construct(
        private readonly UserCompanyId $id,
        private readonly UserId $userId,
        private readonly Name $name,
        private readonly Inn $inn,
        private readonly Kpp $kpp,
        private readonly Rs $rs,
        private readonly Bik $bik
    ) {
    }

    public static function new(
        UserId $userId,
        Name $name,
        Inn $inn,
        Kpp $kpp,
        Rs $rs,
        Bik $bik
    ): self {
        return new self(
            id: UserCompanyId::fromString(null),
            userId: $userId,
            name: $name,
            inn: $inn,
            kpp: $kpp,
            rs: $rs,
            bik: $bik
        );
    }

    public static function hydrateExisting(
        UserCompanyId $id,
        UserId $userId,
        Name $name,
        Inn $inn,
        Kpp $kpp,
        Rs $rs,
        Bik $bik
    ): self {
        if (is_null($id->getId())) {
            throw new InvalidArgumentException('Rule: id not null.');
        }
        return new self(
            id: $id,
            userId: $userId,
            name: $name,
            inn: $inn,
            kpp: $kpp,
            rs: $rs,
            bik: $bik
        );
    }

    public function changeData(
        Name $name,
        Inn $inn,
        Kpp $kpp,
        Rs $rs,
        Bik $bik
    ): self {
        return new self(
            id: $this->getId(),
            userId: $this->getUserId(),
            name: $name,
            inn: $inn,
            kpp: $kpp,
            rs: $rs,
            bik: $bik
        );
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getInn()
    {
        return $this->inn;
    }

    public function getKpp()
    {
        return $this->kpp;
    }

    public function getRs()
    {
        return $this->rs;
    }

    public function getBik()
    {
        return $this->bik;
    }
}
