<?php

declare(strict_types=1);

namespace app\components\IAA\Authentication;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;

use app\components\IAA\Authentication\Model\Contract\IdentityRepositoryInterface;
use app\components\IAA\Authentication\Model\Identity;
use app\components\IAA\Authentication\Service\PasswordService;
use app\components\Saga\UserRegister\Dto\UserRegisterSagaData;
use app\components\Saga\UserRegister\UserRegisterSaga;
use app\components\Shared\Domain\ValueObject\Email;

class AuthEmailPassword
{
    public function __construct(
        private readonly Email $email,
        private readonly string $password,
        private readonly IdentityRepositoryInterface $identityRepository
    ) {
    }

    public function enter(): bool
    {
        $identity = $this->identityRepository->getByEmail($this->email);

        //new customer
        if (is_null($identity)) {
            if (!$this->registerUser($this->email, $this->password)) {
                return false;
            }
        }

        $identity = $this->identityRepository->getByEmail($this->email);
        if (is_null($identity)) {
            return false;
        }

        if (!PasswordService::validatePassword($this->password, $identity->getPasswordHash())) {
            return false;
        }

        $identity = $identity->violationCountZero();
        $identity = $this->identityRepository->save($identity);

        $this->login($identity);

        return true;
    }

    private function login(Identity $identity): void
    {
        Yii::$app->user->login($identity, HelperY::params('lifetimeLogin'));
    }

    private function registerUser(Email $email, string $password): bool
    {
        $userRegisterSagaData = new UserRegisterSagaData(
            email: $email,
            password: $password
        );
        $userRegisterSaga = Yii::$container->get(UserRegisterSaga::class, ['sagaData' => $userRegisterSagaData]);
        $userRegisterSaga->process();
        return $userRegisterSaga->isSuccess();
    }
}
