<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.07.
 * Time: 12:46
 */

namespace Catalog\Repository;


use Catalog\Entity\Stock;
use Doctrine\ORM\EntityRepository;

class StockRepository extends EntityRepository
{
    public function getIngredientsAmount($storageId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('s')
            ->distinct(true)
            ->from(Stock::class, 's')
            ->where('s.storage = :storageId')->setParameter("storageId", $storageId);

        return $qb->getQuery()->getResult();


    }
}