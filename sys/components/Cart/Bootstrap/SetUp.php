<?php

namespace app\components\Cart\Bootstrap;

use Yii;
use yii\base\BootstrapInterface;
use app\components\Cart\Domain\Aggregate\Cart;
use app\components\Cart\Infrastructure\CartHybridRepository;
use app\components\IAA\Authentication\Service\IdentityService;
use app\components\User\Domain\ValueObject\UserId;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app): void
    {
        $container = Yii::$container;         

        $container->setSingleton(Cart::class, function () {
            $userId = IdentityService::userId();            
            $repository = new CartHybridRepository(
                cookieName: 'cart',
                cookieTimeout: 7 * 24 * 3600,
                userId: $userId
            );

            return Cart::create(repository: $repository);
        });
    }
}

//  ** @var \app\components\Cart\Domain\Aggregate\Cart $cart */
//  $cart = Yii::$container->get(\app\components\Cart\Domain\Aggregate\Cart::class);
