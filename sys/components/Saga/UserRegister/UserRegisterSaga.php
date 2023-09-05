<?php

declare(strict_types=1);

namespace app\components\Saga\UserRegister;

use app\components\IAA\Authentication\Model\Contract\IdentityRepositoryInterface;
use app\components\IAA\Authentication\Service\PasswordService;
use app\components\Saga\Contract\SagaAbstract;
use app\components\Saga\Contract\SagaInterface;
use app\components\Saga\UserRegister\Command\CreateIdentityCommand;
use app\components\Saga\UserRegister\Command\CreateUserCommand;

use app\components\Saga\UserRegister\Dto\UserRegisterSagaData;
use app\components\User\Domain\Contract\UserRepositoryInterface;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

class UserRegisterSaga extends SagaAbstract implements SagaInterface
{
    public function __construct(
        UserRegisterSagaData $sagaData,
        private readonly UserRepositoryInterface $userRepository,
        private readonly IdentityRepositoryInterface $identityRepository,
        private readonly PasswordService $passwordService
    ) {
        parent::__construct(
            sagaData: $sagaData,
            sagaId: Uuid::uuid7(),
            createdAt: new DateTimeImmutable()
        );

        $createUserCommand = new CreateUserCommand(
            userRepository: $this->userRepository
        );
        $createIdentityCommand = new CreateIdentityCommand(
            identityRepository: $this->identityRepository,
            passwordService: $this->passwordService
        );

        $this->addStep($createUserCommand)
            ->addStep($createIdentityCommand);
    }
}
