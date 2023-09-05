<?php

namespace app\models;

use app\components\Shared\Domain\ValueObject\CountOnPage;
use app\components\Shared\Domain\ValueObject\PageNumber;
use Yii;

class PageHelper
{
    public static function getItems(PageNumber $page, CountOnPage $cop)
    {
        $offset = (int) ($page->getPageNumber() - 1) * $cop->getCop();
        $limit = " LIMIT $offset, " . $cop->getCop() . ' ';       

        $res = Yii::$app->db->createCommand("
                SELECT
                    p.*                
                FROM  {{page}} p                     
                ORDER BY p.changedAt DESC, p.id DESC 
                $limit 
               ")
            ->queryAll(); //queryOne 
        return $res;
    }
}
