<?php

declare(strict_types=1);

namespace app\components\Cart\Infrastructure;

use app\components\Cart\Domain\Contract\CartRepositoryInterface;
use app\components\Cart\Domain\Contract\CartItemCollection;
use app\components\User\Domain\ValueObject\UserId;
use app\components\Cart\Domain\ValueObject\CartItem;
use LogicException;
use Yii;

class CartHybridRepository implements CartRepositoryInterface
{
    private ?CartRepositoryInterface $repository = null;

    public function __construct(
        private readonly string $cookieName,
        private readonly int $cookieTimeout,
        private readonly UserId $userId
    ) {
    }
    
    public function getAll(): CartItemCollection
    {
        return $this->getRepository()->getAll();
    }
   
    public function saveAll(CartItemCollection $items): void
    {
        $this->getRepository()->saveAll($items);
    }   

    private function getRepository(): CartRepositoryInterface
    {
        if (!is_null($this->repository)) {
            return $this->repository;
        }

        $cookieRepository = new CartCookieRepository($this->cookieName, $this->cookieTimeout);
        if (is_null($this->userId->getId())) {
            //set
            $this->repository = $cookieRepository;
        } else {
            $dbRepository = new CartDbRepository($this->userId);
            //Move items from Cookie to Db
            $cookieItems = $cookieRepository->getAll();
            if (!empty($cookieItems)) {
                $dbItems = $dbRepository->getAll();                   
                $summaryItemsArray = array_merge($dbItems->toArray(), array_udiff($cookieItems->toArray(), $dbItems->toArray(), function (CartItem $first, CartItem $second) {
                    return (int) $first->isEqualsTo($second) ? 0 : 1;
                }));
                $dbRepository->saveAll(new CartItemCollection($summaryItemsArray));
                $cookieRepository->saveAll(new CartItemCollection());
            }
            //set
            $this->repository = $dbRepository;
        }

        return $this->repository;
    }
}
