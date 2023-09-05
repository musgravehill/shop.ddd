<?php

declare(strict_types=1);

namespace app\components\UserCompany\Infrastructure;

use app\components\UserCompany\Domain\Contract\UserCompanyRepositoryInterface;
use app\components\UserCompany\Domain\Entity\UserCompany;
use app\components\UserCompany\Domain\ValueObject\Bik;
use app\components\User\Domain\ValueObject\UserId;
use app\components\UserCompany\Domain\ValueObject\Inn;
use app\components\UserCompany\Domain\ValueObject\Kpp;
use app\components\UserCompany\Domain\ValueObject\Name;
use app\components\UserCompany\Domain\ValueObject\Rs;
use app\components\UserCompany\Domain\ValueObject\UserCompanyId;
use InvalidArgumentException;
use Yii;
use yii\db\Query;
use Ramsey\Uuid\Uuid;


use DateTimeImmutable;

class UserCompanyRepository implements UserCompanyRepositoryInterface
{

    public function nextId(): UserCompanyId
    {
        $uuid = Uuid::uuid7()->toString();
        return UserCompanyId::fromString($uuid);
    }

    public function getById(UserCompanyId $id): ?UserCompany
    {
        $uc = Yii::$app->db->createCommand("
        SELECT
            uc.*               
        FROM  {{user_company}} uc                   
        WHERE
            uc.id='" . $id->getId() . "'
        LIMIT 1                    
        ")
            ->queryOne();
        if (!$uc) {
            return null;
        }

        $userCompany = UserCompany::hydrateExisting(
            id: UserCompanyId::fromString($uc['id']),
            userId: UserId::fromString($uc['userId']),
            name: new Name($uc['name']),
            inn: new Inn($uc['inn']),
            kpp: new Kpp($uc['kpp']),
            rs: new Rs($uc['rs']),
            bik: new Bik($uc['bik'])
        );

        return $userCompany;
    }

    public function getByUserId(UserId $userId): ?UserCompany
    {
        $uc = Yii::$app->db->createCommand("
        SELECT
            uc.*               
        FROM  {{user_company}} uc                   
        WHERE
            uc.userId='" . $userId->getId() . "'
        LIMIT 1                    
        ")
            ->queryOne();
        if (!$uc) {
            return null;
        }

        $userCompany = UserCompany::hydrateExisting(
            id: UserCompanyId::fromString($uc['id']),
            userId: UserId::fromString($uc['userId']),
            name: new Name($uc['name']),
            inn: new Inn($uc['inn']),
            kpp: new Kpp($uc['kpp']),
            rs: new Rs($uc['rs']),
            bik: new Bik($uc['bik'])
        );

        return $userCompany;
    }

    public function save(UserCompany $userCompany): UserCompany
    {
        if (is_null($userCompany->getId()->getId())) {
            return $this->new($userCompany);
        } else {
            return $this->update($userCompany);
        }
    }

    private function new(UserCompany $userCompany): UserCompany
    {
        $userCompanyId = $this->nextId();

        Yii::$app->db->createCommand()->insert(
            'user_company',
            [
                'id' => $userCompanyId->getId(),
                'userId' => $userCompany->getUserId()->getId(),
                'name' => $userCompany->getName()->getName(),
                'inn' => $userCompany->getInn()->getInn(),
                'kpp' => $userCompany->getKpp()->getKpp(),
                'rs' => $userCompany->getRs()->getRs(),
                'bik' => $userCompany->getBik()->getBik(),
            ]
        )->execute();

        return $this->getById($userCompanyId);
    }

    private function update(UserCompany $userCompany): UserCompany
    {
        $userCompanyId = $userCompany->getId();

        Yii::$app->db->createCommand()->update(
            'user_company',
            [
                'name' => $userCompany->getName()->getName(),
                'inn' => $userCompany->getInn()->getInn(),
                'kpp' => $userCompany->getKpp()->getKpp(),
                'rs' => $userCompany->getRs()->getRs(),
                'bik' => $userCompany->getBik()->getBik(),
            ],
            " id = '" . $userCompanyId->getId() . "' "
        )->execute();

        return $this->getById($userCompanyId);
    }
}
