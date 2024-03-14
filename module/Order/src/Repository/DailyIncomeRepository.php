<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.07.
 * Time: 12:46
 */

namespace Order\Repository;


use Doctrine\ORM\EntityRepository;
use Order\Entity\DailyIncome;

class DailyIncomeRepository extends EntityRepository
{


    public function getTodayIncome()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('di')
            ->from(DailyIncome::class, 'di')
            ->where('di.dateTime > :now')->setParameter("now", new \DateTime('today'));
        return $qb->getQuery()->getResult();
    }

}