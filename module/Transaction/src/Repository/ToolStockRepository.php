<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.07.
 * Time: 12:46
 */

namespace Transaction\Repository;


use Transaction\Entity\ToolStock;
use Doctrine\ORM\EntityRepository;

class ToolStockRepository extends EntityRepository
{
    public function getToolsAmount($storageId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('s')
            ->distinct(true)
            ->from(ToolStock::class, 's')
            ->where('s.storage = :storageId')->setParameter("storageId", $storageId);

        return $qb->getQuery()->getResult();


    }
}