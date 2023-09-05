<?php

declare(strict_types=1);

namespace app\components\IAA\AccessRecovery;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;
use app\components\IAA\Authentication\Model\Contract\IdentityRepositoryInterface;
use app\components\IAA\Authentication\Model\Identity;
use app\components\IAA\Authentication\Model\ValueObject\IdentityId;
use app\components\IAA\Authentication\Service\IdentityService;
use app\components\IAA\Authentication\Service\PasswordService;
use app\components\Shared\Domain\ValueObject\Email;
use app\components\User\Domain\ValueObject\UserId;
use Ramsey\Uuid\Uuid;

class AccessRecoveryEmail
{
    public function __construct(
        private readonly IdentityRepositoryInterface $identityRepository,
        private readonly AccessRecoveryTokenRepositoryInterface $accessRecoveryTokenRepository,
        private readonly \yii\mail\MailerInterface $mailer
    ) {
    }

    public function initAccessRecovery(Email $email): void
    {
        $identity = $this->identityRepository->getByEmail($email);
        if (is_null($identity)) {
            return;
        }

        $token = $this->accessRecoveryTokenRepository->add($identity->getIdentityId());

        $subject = '' . HelperY::params('domain') . ' смена пароля.';
        $body = 'Если Вам нужно сменить пароль на ' . HelperY::params('domain') . ' '
            . ' перейдите по ссылке: '
            . Url::to([
                'auth/accessrecovery',
                'identityId' => $identity->getIdentityId()->getId(),
                'token' => $token->getToken(),
            ], true);

        $this->mailer->compose()
            ->setFrom(HelperY::params('email_robot'))
            ->setTo($email->getEmail())
            ->setSubject($subject)
            ->setTextBody($body)
            ->send();
    }

    public function restoreAccess(
        IdentityId $identityId,
        AccessRecoveryToken $token,
        string $newPassword
    ): bool {
        $isFind = $this->accessRecoveryTokenRepository->find(
            identityId: $identityId,
            token: $token
        );
        if (!$isFind) {
            return false;
        }

        $identity = $this->identityRepository->getById($identityId);
        if (is_null($identity)) {
            return false;
        }

        $newPasswordHash = PasswordService::generatePasswordHash($newPassword);

        $identity = $identity->changePasswordHash($newPasswordHash);
        $identity = $this->identityRepository->save($identity);

        return true;
    }
}
