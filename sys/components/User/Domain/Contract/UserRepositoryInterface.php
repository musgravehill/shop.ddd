<?php

declare(strict_types=1);

namespace app\components\User\Domain\Contract;

use app\components\Shared\Domain\ValueObject\CountOnPage;
use app\components\Shared\Domain\ValueObject\Email;
use app\components\Shared\Domain\ValueObject\PageNumber;
use app\components\User\Domain\Entity\User;

use app\components\User\Domain\ValueObject\UserId;

interface UserRepositoryInterface
{
    public function nextId(): UserId;
    public function getById(UserId $userId): ?User;
    public function getByEmail(Email $email): ?User;
    public function save(User $user): ?User;
    public function list(PageNumber $page, CountOnPage $cop): array;
}
