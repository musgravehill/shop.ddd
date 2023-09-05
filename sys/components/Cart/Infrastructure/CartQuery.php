<?php

declare(strict_types=1);

namespace app\components\Cart\Infrastructure;

use app\components\Shared\Domain\ValueObject\CountOnPage;
use app\components\Shared\Domain\ValueObject\PageNumber;
use LogicException;
use InvalidArgumentException;
use Yii;
use yii\db\Query;

class CartQuery
{
    public function list(
        PageNumber $page,
        CountOnPage $cop,
    ): array {
        $offset = (int) ($page->getPageNumber() - 1) * $cop->getCop();
        $limit = " LIMIT $offset, " . $cop->getCop() . ' ';

        $items = Yii::$app->db->createCommand("
                    SELECT
                        cart.userId as cart_userId,
                        cart.productId as cart_productId,
                        cart.quantity as cart_quantity,
                        
                        user.username as user_username,
                        user.email as user_email,
                        user.phone as user_phone,

                        product.ufu as product_ufu,
                        product.supplierId as product_supplierId,
                        product.priceSelling as product_priceSelling,
                        product.name as product_name,
                        product.quantityAvailable as product_quantityAvailable,
                        
                        supplier.name as supplier_name 
                        
                    FROM  
                        {{cart}} cart 
                    LEFT JOIN {{user}} user ON cart.userId = user.id    
                    LEFT JOIN {{product}} product ON cart.productId = product.id    
                    LEFT JOIN {{supplier}} supplier ON supplier.id = product.supplierId  
                    
                    ORDER BY cart.id DESC 
                    $limit 
                   ")
            ->queryAll();

        return $items;
    }
}
