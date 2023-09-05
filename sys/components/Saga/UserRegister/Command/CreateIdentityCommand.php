<?php

declare(strict_types=1);

namespace app\components\Saga\UserRegister\Command;

use app\components\IAA\Authentication\Model\Contract\IdentityRepositoryInterface;
use app\components\IAA\Authentication\Model\Identity;
use app\components\IAA\Authentication\Service\PasswordService;
use app\components\Saga\Contract\SagaCommandAbstract;
use app\components\Saga\Contract\SagaCommandInterface;
use app\components\Saga\Contract\SagaDataInterface;
use app\components\Saga\UserRegister\Dto\UserRegisterSagaData;
use Exception;

class CreateIdentityCommand extends SagaCommandAbstract implements SagaCommandInterface
{
    public function __construct(
        private readonly IdentityRepositoryInterface $identityRepository,
        private readonly PasswordService $passwordService
    ) {
    }

    public function execute(SagaDataInterface $sagaData): bool
    {
        /** @var UserRegisterSagaData $sagaData */
        try {
            $identity = Identity::new(
                userId: $sagaData->getUser()->getId(),
                email: $sagaData->getEmail(),
                passwordHash: $this->passwordService::generatePasswordHash($sagaData->getPassword())
            );
            $identity = $this->identityRepository->save($identity);
            if (is_null($identity)) {
                throw new Exception('Save: error.');
            }

            //save data for next step
            $sagaData->setIdentity($identity);
        } catch (\Throwable $th) {
            return false;
        }

        return true;
    }

    //idempotence?
    public function compensate(SagaDataInterface $sagaData): void
    {
        /** @var UserRegisterSagaData $sagaData */
        // delete User ?
    }
}
