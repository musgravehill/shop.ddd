<?php

declare(strict_types=1);

namespace app\components\IAA\AccessRecovery;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;
use app\components\IAA\Authentication\Model\ValueObject\IdentityId;

interface AccessRecoveryTokenRepositoryInterface
{
    public function add(IdentityId $identityId): AccessRecoveryToken;
    public function find(IdentityId $identityId, AccessRecoveryToken $token): bool;
}
