<?php

declare(strict_types=1);

namespace app\components\Saga\UserRegister\Dto;

use app\components\IAA\Authentication\Model\Identity;
use app\components\Saga\Contract\SagaDataInterface;
use app\components\Shared\Domain\ValueObject\Email;
use app\components\User\Domain\Entity\User;

class UserRegisterSagaData implements SagaDataInterface
{
    private ?User $user = null;
    private ?Identity $identity = null;

    public function __construct(
        private readonly Email $email,
        private readonly string $password
    ) {
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setIdentity(?Identity $identity): void
    {
        $this->identity = $identity;
    }
    public function getIdentity(): ?Identity
    {
        return $this->identity;
    }
}
