<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.26.
 * Time: 6:09
 */

namespace Transaction\Service\StockTransaction;


use Transaction\Entity\StockTransaction;
use Doctrine\ORM\EntityManager;

class StockTransactionDbListener
{

    protected $sm;
    protected $em;
    protected $entities;
    protected $transactionEventModel;

    public function __construct($sm, $entities)
    {
        $this->sm = $sm;
        $this->em = $sm->get(EntityManager::class);
        $this->entities = $entities;

    }

    public function __invoke($transactionEventModel)
    {
        $this->transactionEventModel = $transactionEventModel->getParams()['transactionEventModel'];

        /** @var StockTransaction $stockTransactonEntity */
        $stockTransactonEntity = new $this->entities['StockTransaction']();

        $stockTransactonEntity->setServiceManager($this->sm);
        $stockTransactonEntity->setFromStorage($this->transactionEventModel->getFromStorage());
        $stockTransactonEntity->setToStorage($this->transactionEventModel->getToStorage());
        $stockTransactonEntity->setUser($this->transactionEventModel->getUser());
        $stockTransactonEntity->setMoreInfo($this->transactionEventModel->getMoreInfo());
        $stockTransactonEntity->setTransactionType($this->transactionEventModel->getTransactionType());
        $stockTransactonEntity->setDateTime($this->transactionEventModel->getDateTime());
        $this->em->persist($stockTransactonEntity);
        $this->em->flush();

        //Továbbadjuk a transaction Id-t
        $this->transactionEventModel->setStockTransactionId($stockTransactonEntity->getId());
    }


}