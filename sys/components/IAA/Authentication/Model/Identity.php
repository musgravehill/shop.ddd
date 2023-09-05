<?php

declare(strict_types=1);

namespace app\components\IAA\Authentication\Model;

use app\components\IAA\Authentication\Model\Contract\IdentityRepositoryInterface;
use app\components\IAA\Authentication\Model\ValueObject\ViolationCount;
use app\components\IAA\Authentication\Model\ValueObject\PasswordHash;
use app\components\IAA\Authentication\Model\ValueObject\RoleTypeId;
use app\components\IAA\Authentication\Model\ValueObject\IdentityId;
use app\components\Shared\Domain\ValueObject\Email;
use app\components\User\Domain\ValueObject\UserId;
use DateTimeImmutable;
use Exception;
use InvalidArgumentException;
use Yii;

class Identity implements \yii\web\IdentityInterface
{
    private function __construct(
        private readonly IdentityId $identityId,
        private readonly UserId $userId,
        private readonly Email $email,
        private readonly PasswordHash $passwordHash,
        private readonly RoleTypeId $role,
        private readonly ViolationCount $violationCount,
        private readonly DateTimeImmutable $bannedUntil
    ) {
    }

    public static function findIdentity($id)
    {
        $identityRepository = Yii::$container->get(IdentityRepositoryInterface::class);
        $identity = $identityRepository->getById(IdentityId::fromString($id));
        return $identity;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new Exception('Not supported: findIdentityByAccessToken');
    }

    public function getId()
    {
        return (string) $this->identityId->getId();
    }

    public function getAuthKey()
    {
        return (string) sha1('1%#*01G' . $this->getId() . '3F!-_@2');
    }

    public function validateAuthKey($authKey)
    {
        return (string) $this->getAuthKey() === (string) $authKey;
    }

    public static function new(
        UserId $userId,
        Email $email,
        PasswordHash $passwordHash
    ): self {
        return new self(
            identityId: IdentityId::fromString(null),
            userId: $userId,
            email: $email,
            passwordHash: $passwordHash,
            role: RoleTypeId::Customer,
            violationCount: ViolationCount::forUserWithoutViolations(),
            bannedUntil: (new DateTimeImmutable())->setTimestamp(1)
        );
    }

    public static function hydrateExisting(
        IdentityId $identityId,
        UserId $userId,
        Email $email,
        PasswordHash $passwordHash,
        RoleTypeId $role,
        ViolationCount $violationCount,
        DateTimeImmutable $bannedUntil
    ): self {
        if (is_null($identityId->getId())) {
            throw new InvalidArgumentException('Rule: id not null.');
        }
        return new self(
            identityId: $identityId,
            userId: $userId,
            email: $email,
            passwordHash: $passwordHash,
            role: $role,
            violationCount: $violationCount,
            bannedUntil: $bannedUntil
        );
    }

    public function violationCountIncrement(): self
    {
        return new self(
            identityId: $this->getIdentityId(),
            userId: $this->getUserId(),
            email: $this->getEmail(),
            passwordHash: $this->getPasswordHash(),
            role: $this->getRole(),
            violationCount: $this->getViolationCount()->incrementExisting(),
            bannedUntil: $this->getBannedUntil()
        );
    }

    public function controlBan(): self
    {
        $bannedUntil = $this->getBannedUntil();
        if ($this->getViolationCount()->getViolationCount() > 3) {
            $bannedUntil = (new DateTimeImmutable())->modify('+1 hour');
        }

        return new self(
            identityId: $this->getIdentityId(),
            userId: $this->getUserId(),
            email: $this->getEmail(),
            passwordHash: $this->getPasswordHash(),
            role: $this->getRole(),
            violationCount: $this->getViolationCount(),
            bannedUntil: $bannedUntil
        );
    }

    public function violationCountZero(): self
    {
        return new self(
            identityId: $this->getIdentityId(),
            userId: $this->getUserId(),
            email: $this->getEmail(),
            passwordHash: $this->getPasswordHash(),
            role: $this->getRole(),
            violationCount: $this->getViolationCount()->forUserWithoutViolations(),
            bannedUntil: $this->getBannedUntil()
        );
    }

    public function changePasswordHash(PasswordHash $passwordHash): self
    {
        return new self(
            identityId: $this->getIdentityId(),
            userId: $this->getUserId(),
            email: $this->getEmail(),
            passwordHash: $passwordHash,
            role: $this->getRole(),
            violationCount: $this->getViolationCount(),
            bannedUntil: $this->getBannedUntil()
        );
    }

    public function getIdentityId(): IdentityId
    {
        return $this->identityId;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPasswordHash(): PasswordHash
    {
        return $this->passwordHash;
    }

    public function getRole(): RoleTypeId
    {
        return $this->role;
    }

    public function getViolationCount(): ViolationCount
    {
        return $this->violationCount;
    }

    public function getBannedUntil(): DateTimeImmutable
    {
        return $this->bannedUntil;
    }
}
