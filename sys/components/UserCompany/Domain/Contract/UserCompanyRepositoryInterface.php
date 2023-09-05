<?php

declare(strict_types=1);

namespace app\components\UserCompany\Domain\Contract;

use app\components\UserCompany\Domain\Entity\UserCompany;
use app\components\User\Domain\ValueObject\UserId;
use app\components\UserCompany\Domain\ValueObject\UserCompanyId;

interface UserCompanyRepositoryInterface
{
    public function nextId(): UserCompanyId;
    public function getById(UserCompanyId $id): ?UserCompany;
    public function getByUserId(UserId $userId): ?UserCompany;
    public function save(UserCompany $userCompany): UserCompany;
}
