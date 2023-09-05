<?php

declare(strict_types=1);

namespace app\components\IAA\Authentication\Service;

use app\components\IAA\Authentication\Model\Identity;
use app\components\IAA\Authentication\Model\ValueObject\RoleTypeId;
use app\components\User\Domain\ValueObject\UserId;
use Ramsey\Uuid\Uuid;
use Yii;

class IdentityService
{
    public static function userId(): UserId
    {
        if (!isset(Yii::$app->user)) {
            return UserId::fromString(null);
        }
        if (Yii::$app->user->isGuest) {
            return UserId::fromString(null);
        }

        /** @var Identity $identity */
        $identity = Yii::$app->user->identity;

        return  $identity->getUserId();
    }

    public static function userIsGuest(): bool
    {
        if (!isset(Yii::$app->user)) {
            return true;
        }
        return Yii::$app->user->isGuest;
    }

    public static function userIsAdmin(): bool
    {
        if (!isset(Yii::$app->user)) {
            return false;
        }
        if (Yii::$app->user->isGuest) {
            return false;
        }

        /** @var Identity $identity */
        $identity = Yii::$app->user->identity;

        return $identity->getRole() === RoleTypeId::Admin;
    }
}
