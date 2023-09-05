<?php

declare(strict_types=1);

namespace app\components\User\Infrastructure;

use app\components\Shared\Domain\ValueObject\CountOnPage;
use InvalidArgumentException;
use Yii;
use yii\db\Query;
use Ramsey\Uuid\Uuid;
use app\components\User\Domain\Contract\UserRepositoryInterface;
use app\components\User\Domain\Entity\User;

use app\components\User\Domain\ValueObject\Phone;
use app\components\Shared\Domain\ValueObject\Email;
use app\components\Shared\Domain\ValueObject\PageNumber;
use app\components\User\Domain\ValueObject\Address;
use app\components\User\Domain\ValueObject\CityName;
use app\components\User\Domain\ValueObject\UserId;
use app\components\User\Domain\ValueObject\Username;
use DateTimeImmutable;
use Exception;

class UserRepository implements UserRepositoryInterface
{

    public function nextId(): UserId
    {
        $uuid = Uuid::uuid7()->toString();
        return UserId::fromString($uuid);
    }

    public function getById(UserId $userId): ?User
    {
        $u = Yii::$app->db->createCommand("
        SELECT
            u.*               
        FROM  {{user}} u                   
        WHERE
            u.id='" . $userId->getId() . "'
        LIMIT 1                    
        ")
            ->queryOne();
        if (!$u) {
            return null;
        }

        $user = User::hydrateExisting(
            id: UserId::fromString($u['id']),
            username: new Username($u['username']),
            email: new Email($u['email']),
            phone: new Phone($u['phone']),
            createdAt: (new DateTimeImmutable())->setTimestamp($u['createdAt']),
            cityName: new CityName($u['cityName']),
            address: new Address($u['address']),
        );

        return $user;
    }

    public function getByEmail(Email $email): ?User
    {
        $u = Yii::$app->db->createCommand("
        SELECT
            u.*               
        FROM  {{user}} u                   
        WHERE
            u.email='" . $email->getEmail() . "'
        LIMIT 1                    
        ")
            ->queryOne();
        if (!$u) {
            return null;
        }

        $user = User::hydrateExisting(
            id: UserId::fromString($u['id']),
            username: new Username($u['username']),
            email: new Email($u['email']),
            phone: new Phone($u['phone']),
            createdAt: (new DateTimeImmutable())->setTimestamp($u['createdAt']),
            cityName: new CityName($u['cityName']),
            address: new Address($u['address']),
        );

        return $user;
    }

    public function save(User $user): ?User
    {
        $res = null;
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {

            if (is_null($user->getId()->getId())) {
                $res = $this->new($user);
            } else {
                $res = $this->update($user);
            }

            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw new Exception('Save: error!');
        }

        return $res;
    }

    private function new(User $user): ?User
    {
        $userId = $this->nextId();
        Yii::$app->db->createCommand()->insert(
            'user',
            [
                'id' => $userId->getId(),
                'username' => $user->getUsername()->getUsername(),
                'email' => $user->getEmail()->getEmail(),
                'phone' => $user->getPhone()->getPhone(),
                'createdAt' => $user->getCreatedAt()->getTimestamp(),
                'cityName' => $user->getCityName()->getCityName(),
                'address' => $user->getAddress()->getAddress(),
            ]
        )->execute();

        return $this->getById($userId);
    }

    private function update(User $user): ?User
    {
        $userId = $user->getId();
        Yii::$app->db->createCommand()->update(
            'user',
            [
                'username' => $user->getUsername()->getUsername(),
                'email' => $user->getEmail()->getEmail(),
                'phone' => $user->getPhone()->getPhone(),
                'cityName' => $user->getCityName()->getCityName(),
                'address' => $user->getAddress()->getAddress(),
            ],
            " id = '" . $userId->getId() . "' "
        )->execute();

        return $this->getById($userId);
    }

    public function list(PageNumber $page, CountOnPage $cop): array
    {
        $res = [];

        $copInt = $cop->getCop();
        $offset = (int) ($page->getPageNumber() - 1) * $copInt;
        $limit = " LIMIT $offset, $copInt ";

        $us = Yii::$app->db->createCommand("
            SELECT
                u.*               
            FROM  {{user}} u                   
            ORDER BY u.id DESC      
            $limit                 
        ")
            ->queryAll();
        if (!$us) {
            return $res;
        }

        foreach ($us as $u) {
            $user = User::hydrateExisting(
                id: UserId::fromString($u['id']),
                username: new Username($u['username']),
                email: new Email($u['email']),
                phone: new Phone($u['phone']),
                createdAt: (new DateTimeImmutable())->setTimestamp($u['createdAt']),
                cityName: new CityName($u['cityName']),
                address: new Address($u['address']),
            );
            $res[] = $user;
        }

        return $res;
    }
}
