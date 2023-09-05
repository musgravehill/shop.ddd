<?php

declare(strict_types=1);

namespace app\components\IAA\AccessRecovery;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;
use app\components\IAA\Authentication\Model\ValueObject\IdentityId;
use yii\db\Query;

class AccessRecoveryTokenRepository implements AccessRecoveryTokenRepositoryInterface
{
    public function add(IdentityId $identityId): AccessRecoveryToken
    {
        $this->removeByIdentity($identityId);

        $token = $this->token();

        Yii::$app->db->createCommand()->insert(
            'authentication_access_recovery_token',
            [
                'identityId' => $identityId->getId(),
                'token' => $token->getToken(),
                'createdAt' => time()
            ]
        )->execute();

        return $token;
    }


    public function find(IdentityId $identityId, AccessRecoveryToken $token): bool
    {
        $expiration = time() - 24 * 3600 * 1;
        $uart = Yii::$app->db->createCommand("
            SELECT
                uart.id             
            FROM  {{authentication_access_recovery_token}} uart                   
            WHERE
                uart.identityId='" . $identityId->getId() . "'
                AND uart.token='" . $token->getToken() . "' 
                AND uart.createdAt >= " . $expiration . "   
            LIMIT 1                    
        ")->queryOne();

        if (!$uart) {
            return false;
        }

        $this->removeByIdentity($identityId);

        return true;
    }

    private function token(): AccessRecoveryToken
    {
        return AccessRecoveryToken::new();
    }

    private function removeByIdentity(IdentityId $identityId): void
    {
        Yii::$app->db->createCommand("
            DELETE             
            FROM  {{authentication_access_recovery_token}}                   
            WHERE
                identityId='" . $identityId->getId() . "'
            LIMIT 1000                   
        ")->execute();
    }
}
