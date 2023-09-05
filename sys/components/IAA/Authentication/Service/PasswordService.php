<?php

declare(strict_types=1);

namespace app\components\IAA\Authentication\Service;

use app\components\IAA\Authentication\Model\ValueObject\PasswordHash;
use Ramsey\Uuid\Uuid;
use Yii;

class PasswordService
{
    public static function generatePasswordHash(string $password): PasswordHash
    {
        return new PasswordHash(Yii::$app->getSecurity()->generatePasswordHash($password));
    }

    public static function validatePassword(string $password, PasswordHash $hash): bool
    {
        return (bool) Yii::$app->getSecurity()->validatePassword($password, $hash->getPasswordHash());
    }

    public static function generatePassword(): string
    {
        // 1ee9aa1b-6510-4105-92b9-7171bb2f3089 
        $uuid = Uuid::uuid4()->toString();
        $password = substr($uuid, -12);
        return $password;
    }
}
