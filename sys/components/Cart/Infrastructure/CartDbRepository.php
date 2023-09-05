<?php

declare(strict_types=1);

namespace app\components\Cart\Infrastructure;

use app\components\Cart\Domain\Contract\CartRepositoryInterface;
use app\components\Cart\Domain\Contract\CartItemCollection;
use app\components\Cart\Domain\ValueObject\CartItem;
use app\components\Product\Domain\ValueObject\ProductId;
use app\components\Shared\Domain\ValueObject\QuantityPositive;
use app\components\User\Domain\ValueObject\UserId;
use LogicException;
use InvalidArgumentException;
use Yii;
use yii\db\Query;

class CartDbRepository implements CartRepositoryInterface
{
    public function getAll(): CartItemCollection
    {
        $rows = (new Query())
            ->select('*')
            ->from('{{%cart}}')
            ->where(['userId' => $this->userId->getId()])
            ->all();

        return new CartItemCollection(array_map(
            function (array $row) {
                return new CartItem(
                    productId: ProductId::fromString((string) $row['productId']), 
                    productQuantity: new QuantityPositive($row['quantity'])
                );
            },
            $rows
        ));
    }

    public function saveAll(CartItemCollection $items): void
    {
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            Yii::$app->db->createCommand()->delete('{{%cart}}', [
                'userId' => $this->userId->getId(),
            ])->execute();

            Yii::$app->db->createCommand()->batchInsert(
                '{{%cart}}',
                [
                    'userId',
                    'productId',
                    'quantity'
                ],
                array_map(function (CartItem $item) {
                    return [
                        'userId' => $this->userId->getId(),
                        'productId' => $item->getProductId()->getId(),
                        'quantity' => $item->getProductQuantity()->getQuantity(),
                    ];
                }, $items->toArray())
            )->execute();

            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
        }
    }

    public function __construct(private readonly UserId $userId)
    {
        if (is_null($userId->getId())) {
            throw new InvalidArgumentException('Client only, not Guest.');
        }
    }
}
