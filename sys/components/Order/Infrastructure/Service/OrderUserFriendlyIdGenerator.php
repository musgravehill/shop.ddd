<?php

declare(strict_types=1);

namespace app\components\Order\Infrastructure\Service;

use app\components\Order\Domain\Contract\OrderUserFriendlyIdGeneratorInterface;
use app\components\Order\Domain\ValueObject\OrderUserFriendlyId;
use InvalidArgumentException;
use Yii;

class OrderUserFriendlyIdGenerator implements OrderUserFriendlyIdGeneratorInterface
{
    public function nextId(): OrderUserFriendlyId
    {
        $seq = null;
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            Yii::$app->db->createCommand()->insert('order_ufid', ['createdAt' => time()])->execute();

            $data = Yii::$app->db->createCommand('SELECT MAX(ufid) as max_ufid FROM order_ufid ')->queryOne();

            if ($data) {
                $seq = (int)$data['max_ufid'];
            }
            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
        }

        if (is_null($seq)) {
            throw new InvalidArgumentException(' Rule: seq not null. ');
        }

        return OrderUserFriendlyId::fromString('БН-' . $seq);
    }
}
