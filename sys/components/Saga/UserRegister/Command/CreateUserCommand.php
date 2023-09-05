<?php

declare(strict_types=1);

namespace app\components\Saga\UserRegister\Command;

use app\components\Saga\Contract\SagaCommandAbstract;
use app\components\Saga\Contract\SagaCommandInterface;
use app\components\Saga\Contract\SagaDataInterface;
use app\components\Saga\UserRegister\Dto\UserRegisterSagaData;
use app\components\User\Domain\Contract\UserRepositoryInterface;
use app\components\User\Domain\Entity\User;
use Exception;

class CreateUserCommand extends SagaCommandAbstract implements SagaCommandInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    public function execute(SagaDataInterface $sagaData): bool
    {
        /** @var UserRegisterSagaData $sagaData */
        try {
            $user = User::registerUser(
                email: $sagaData->getEmail()
            );
            $user = $this->userRepository->save($user);
            if (is_null($user)) {
                throw new Exception('Save: error.');
            }

            //save data for next step
            $sagaData->setUser($user);
        } catch (\Throwable $th) {
            return false;
        }

        return true;
    }

    //idempotence?
    public function compensate(SagaDataInterface $sagaData): void
    {
        /** @var UserRegisterSagaData $sagaData */
    }
}
