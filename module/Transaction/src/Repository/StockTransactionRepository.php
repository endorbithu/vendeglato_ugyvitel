<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.07.
 * Time: 12:46
 */

namespace Transaction\Repository;


use Transaction\Entity\StockTransaction;
use Doctrine\ORM\EntityRepository;
use Transaction\Entity\StockTransactionType;

class StockTransactionRepository extends EntityRepository
{

    public function getStockTransaction($type, \DateTime $formDate, \DateTime $toDate)
    {
        $toDateCorrect = clone $toDate;
        $toDateCorrect->modify('+1 day');

        $type = $this->getEntityManager()->getRepository(StockTransactionType::class)->findBy(['stringId' => strip_tags($type)]);
        $typeId = (empty($type)) ? 0 : $type[0]->getId();

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('st')
            ->from(StockTransaction::class, 'st')
            ->leftJoin("st.transactionType", "stt")
            ->where('st.dateTime > :fromDate')->setParameter("fromDate", $formDate)
            ->andWhere('st.dateTime < :toDate')->setParameter("toDate", $toDateCorrect);
        if ($typeId != 0) {
            $qb->andWhere('st.transactionType = :typeId')->setParameter("typeId", $typeId);
        }

        return $qb->getQuery()->getResult();
    }


}