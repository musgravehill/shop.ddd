<?php

declare(strict_types=1);

namespace app\components\IAA\Authentication\Model\Contract;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;
use app\components\IAA\Authentication\Model\Identity;
use app\components\IAA\Authentication\Model\ValueObject\IdentityId;

use app\components\Shared\Domain\ValueObject\Email;

interface IdentityRepositoryInterface
{
    public function nextId(): IdentityId;
    public function save(Identity $identity): ?Identity;
    public function getByEmail(Email $email): ?Identity;
    public function getById(IdentityId $identityId): ?Identity;
}
