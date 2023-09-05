<?php

declare(strict_types=1);

namespace app\components\Imgsys\Infrastructure;

use app\components\Imgsys\Domain\Contract\ImgsysRepositoryInterface;
use app\components\Shared\Domain\ValueObject\CountOnPage;
use app\components\Shared\Domain\ValueObject\PageNumber;
use app\components\Imgsys\Domain\Entity\Imgsys;
use app\components\Imgsys\Domain\ValueObject\ImgsysId;
use app\components\Imgsys\Domain\ValueObject\ImgsysTags;
use Exception;
use LogicException;
use Ramsey\Uuid\Uuid;
use Yii;

class ImgsysRepository implements ImgsysRepositoryInterface
{
    public function nextId(): ImgsysId
    {
        $uuid = Uuid::uuid7()->toString();
        return ImgsysId::fromString($uuid);
    }

    public function delete(ImgsysId $id): void
    {
        $idString = $id->getId();
        Yii::$app->db->createCommand()->delete(
            table: 'img_sys',
            condition: " id='$idString' "
        )->execute();
    }

    public function list(PageNumber $page, CountOnPage $cop, ImgsysTags $tags): array
    {
        $res = [];

        $q_cond = ' ';
        if (isset($tags->getTags()[1])) {
            $qs = explode(' ', $tags->getTags());
            foreach ($qs as $q) {
                if (isset($q[1])) {
                    $q_cond .= " AND img_sys.tags LIKE '%$q%' ";
                }
            }
        }

        $offset = (int) ($page->getPageNumber() - 1) * $cop->getCop();
        $limit = " LIMIT $offset, " . $cop->getCop() . ' ';

        $ss = Yii::$app->db->createCommand("
                    SELECT
                        img_sys.*                
                    FROM  {{img_sys}} img_sys    
                    WHERE 1=1
                        $q_cond          
                    ORDER BY img_sys.id DESC  
                    $limit 
                   ")
            ->queryAll();

        if (!$ss) {
            return $res;
        }

        foreach ($ss as $s) {
            $res[] = Imgsys::hydrateExisting(
                id: ImgsysId::fromString($s['id']),
                tags: new ImgsysTags($s['tags'])
            );
        }
        return $res;
    }

    public function getById(ImgsysId $id): ?Imgsys
    {
        $s = Yii::$app->db->createCommand("
        SELECT
            s.*               
        FROM  {{img_sys}} s                   
        WHERE
            s.id='" . $id->getId() . "'
        LIMIT 1                    
        ")
            ->queryOne();
        if (!$s) {
            return null;
        }

        $imgsys = Imgsys::hydrateExisting(
            id: ImgsysId::fromString($s['id']),
            tags: new ImgsysTags($s['tags'])
        );

        return $imgsys;
    }

    public function save(Imgsys $imgsys): ?Imgsys
    {
        $res = null;
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            if (is_null($imgsys->getId()->getId())) {
                $res = $this->new($imgsys);
            } else {
                $res = $this->update($imgsys);
            }
            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw new Exception('Save: error!');
        }

        return $res;
    }

    private function new(Imgsys $imgsys): ?Imgsys
    {
        $imgsysId = $this->nextId();
        Yii::$app->db->createCommand()->insert(
            'img_sys',
            [
                'id' => $imgsysId->getId(),
                'tags' => $imgsys->getTags()->getTags(),
            ]
        )->execute();

        return $this->getById($imgsysId);
    }

    private function update(Imgsys $imgsys): ?Imgsys
    {
        $imgsysId = $imgsys->getId();
        Yii::$app->db->createCommand()->update(
            'img_sys',
            [
                'tags' => $imgsys->getTags()->getTags(),
            ],
            " id = '" . $imgsysId->getId() . "' "
        )->execute();

        return $this->getById($imgsysId);
    }
}
