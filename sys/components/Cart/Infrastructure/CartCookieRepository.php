<?php

declare(strict_types=1);

namespace app\components\Cart\Infrastructure;

use app\components\Cart\Domain\Contract\CartRepositoryInterface;
use app\components\Cart\Domain\Contract\CartItemCollection;
use app\components\Cart\Domain\ValueObject\CartItem;
use app\components\Product\Domain\ValueObject\ProductId;
use app\components\Shared\Domain\ValueObject\QuantityPositive;
use LogicException;
use InvalidArgumentException;
use Yii;
use yii\helpers\Json;
use yii\web\Cookie;

class CartCookieRepository implements CartRepositoryInterface
{
    public function getAll(): CartItemCollection
    {
        $cookie = Yii::$app->request->cookies->get($this->name);
        if (!$cookie) {
            return new CartItemCollection();
        }
        $arr = Json::decode($cookie->value);
        return new CartItemCollection(array_map(
            function ($item) {
                return new CartItem(
                    productId: ProductId::fromString((string) $item['product_id']),
                    productQuantity: new QuantityPositive($item['quantity'])
                );
            },
            $arr
        ));
    }

    public function saveAll(CartItemCollection $items): void
    {
        $data = array_map(
            function (CartItem $item) {
                return [
                    'product_id' => $item->getProductId()->getId(),
                    'quantity' => $item->getProductQuantity()->getQuantity(),
                ];
            },
            $items->toArray()
        );

        $value = Json::encode($data);
        $cookie = new Cookie([
            'name' => $this->name,
            'value' => $value,
            'expire' => time() + $this->timeout,
        ]);

        Yii::$app->response->cookies->add($cookie);
    }

    public function __construct(
        private readonly string $name,
        private readonly int $timeout
    ) {
        if (empty($name)) {
            throw new InvalidArgumentException('Specify session key.');
        }
    }
}
