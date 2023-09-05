<?php

declare(strict_types=1);

namespace app\components\IAA\Authorization;

use app\components\IAA\Authentication\Model\Identity;
use Yii;

class AccessRule extends \yii\filters\AccessRule
{
    /**
     * @inheritdoc
     */
    protected function matchRole($user)
    {
        if (empty($this->roles)) {
            return true; //allow to all, cause no roles_based
        }

        foreach ($this->roles as $role) {
            if (($role === '?') && $user->getIsGuest()) {
                return true;
            }

            if (($role === '@') && !$user->getIsGuest()) {
                return true;
            }

            /** @var Identity $identity */
            $identity = Yii::$app->user->identity;

            $userRole = $identity->getRole();
            if (!$user->getIsGuest() && ($role === $userRole)) {
                return true;
            }
        }

        return false;
    }
}
