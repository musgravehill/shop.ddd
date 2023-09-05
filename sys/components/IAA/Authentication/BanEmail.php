<?php

declare(strict_types=1);

namespace app\components\IAA\Authentication;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;

use app\components\IAA\Authentication\Model\Contract\IdentityRepositoryInterface;
use app\components\Shared\Domain\ValueObject\Email;
use DateTimeImmutable;

class BanEmail
{
    public function __construct(
        private readonly Email $email,
        private readonly IdentityRepositoryInterface $identityRepository
    ) {
    }

    public function onViolation(): void
    {
        $identity = $this->identityRepository->getByEmail($this->email);
        if (is_null($identity)) {
            return;
        }

        $identity = $identity->violationCountIncrement();
        $identity = $this->identityRepository->save($identity);
        $identity = $identity->controlBan();
        $identity = $this->identityRepository->save($identity);
    }

    public function getBannedUntil(): ?DateTimeImmutable
    {
        $identity = $this->identityRepository->getByEmail($this->email);
        if (is_null($identity)) {
            return null;
        }

        return $identity->getBannedUntil();
    }
}
