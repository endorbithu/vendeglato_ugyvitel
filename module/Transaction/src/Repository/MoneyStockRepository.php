<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.07.
 * Time: 12:46
 */

namespace Transaction\Repository;


use Doctrine\ORM\EntityRepository;
use Transaction\Entity\MoneyStock;

class MoneyStockRepository extends EntityRepository
{
    public function getMoneysAmount($storageId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('s')
            ->distinct(true)
            ->from(MoneyStock::class, 's')
            ->where('s.storage = :storageId')->setParameter("storageId", $storageId);

        return $qb->getQuery()->getResult();


    }
}