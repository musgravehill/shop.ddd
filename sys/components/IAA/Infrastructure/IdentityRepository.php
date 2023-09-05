<?php

declare(strict_types=1);

namespace app\components\IAA\Infrastructure;

use app\components\IAA\Authentication\Model\Contract\IdentityRepositoryInterface;
use app\components\IAA\Authentication\Model\Identity;
use app\components\IAA\Authentication\Model\ValueObject\ViolationCount;
use app\components\IAA\Authentication\Model\ValueObject\IdentityId;
use app\components\IAA\Authentication\Model\ValueObject\PasswordHash;
use app\components\IAA\Authentication\Model\ValueObject\RoleTypeId;
use app\components\User\Domain\ValueObject\UserId;
use app\components\Shared\Domain\ValueObject\Email;
use InvalidArgumentException;
use Yii;
use yii\db\Query;
use Ramsey\Uuid\Uuid;
use DateTimeImmutable;
use Exception;

class IdentityRepository implements IdentityRepositoryInterface
{
    public function nextId(): IdentityId
    {
        $uuid = Uuid::uuid7()->toString();
        return IdentityId::fromString($uuid);
    }

    public function save(Identity $identity): ?Identity
    {
        $res = null;
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {

            if (is_null($identity->getIdentityId()->getId())) {
                $res = $this->new($identity);
            } else {
                $res = $this->update($identity);
            }

            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
            print_r($e);
            throw new Exception('Save: error!');
        }

        return $res;
    }

    private function new(Identity $identity): ?Identity
    {
        $identityId = $this->nextId();
        $i = Yii::$app->db->createCommand()->insert(
            'authentication_identity',
            [
                'id' => $identityId->getId(),
                'userId' => $identity->getUserId()->getId(),
                'email' => $identity->getEmail()->getEmail(),
                'passwordHash' => $identity->getPasswordHash()->getPasswordHash(),
                'role' => $identity->getRole()->value,
                'violationCount' => $identity->getViolationCount()->getViolationCount(),
                'bannedUntil' => $identity->getBannedUntil()->getTimestamp(),
            ]
        )->execute();

        return $this->getById($identityId);
    }

    private function update(Identity $identity): ?Identity
    {
        $identityId = $identity->getIdentityId();
        Yii::$app->db->createCommand()->update(
            'authentication_identity',
            [
                'passwordHash' => $identity->getPasswordHash()->getPasswordHash(),
                'role' => $identity->getRole()->value,
                'violationCount' => $identity->getViolationCount()->getViolationCount(),
                'bannedUntil' => $identity->getBannedUntil()->getTimestamp(),
            ],
            " id = '" . $identityId->getId() . "' "
        )->execute();

        return $this->getById($identityId);
    }


    public function getByEmail(Email $email): ?Identity
    {
        $i  = Yii::$app->db->createCommand("
        SELECT
            ai.*               
        FROM  {{authentication_identity}} ai                   
        WHERE
            ai.email='" . $email->getEmail() . "' 
        LIMIT 1                    
        ")
            ->queryOne();
        if (!$i) {
            return null;
        }

        $identity = Identity::hydrateExisting(
            identityId: IdentityId::fromString($i['id']),
            userId: UserId::fromString($i['userId']),
            email: new Email($i['email']),
            passwordHash: new PasswordHash($i['passwordHash']),
            role: RoleTypeId::from($i['role']),
            violationCount: ViolationCount::hydrateExisting($i['violationCount']),
            bannedUntil: (new DateTimeImmutable())->setTimestamp($i['bannedUntil'])
        );

        return $identity;
    }

    public function getById(IdentityId $identityId): ?Identity
    {
        $i  = Yii::$app->db->createCommand("
        SELECT
            ai.*               
        FROM  {{authentication_identity}} ai                   
        WHERE
            ai.id='" . $identityId->getId() . "' 
        LIMIT 1                    
        ")
            ->queryOne();
        if (!$i) {
            return null;
        }

        $identity = Identity::hydrateExisting(
            identityId: IdentityId::fromString($i['id']),
            userId: UserId::fromString($i['userId']),
            email: new Email($i['email']),
            passwordHash: new PasswordHash($i['passwordHash']),
            role: RoleTypeId::from($i['role']),
            violationCount: ViolationCount::hydrateExisting($i['violationCount']),
            bannedUntil: (new DateTimeImmutable())->setTimestamp($i['bannedUntil'])
        );

        return $identity;
    }
}
