<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.20.
 * Time: 21:19
 */

namespace Order\Service\ProductTransaction;


use Catalog\Entity\Storage;
use Doctrine\ORM\EntityManager;
use Order\Entity\OrderItemInStorage;

class OrderItemTransactionDbListener
{
    protected $transactionEventModel;
    protected $sm;
    protected $em;
    protected $entities;
    protected $eventLogPost;
    protected $stockTransactionId;

    public function __construct($sm, $entities)
    {
        $this->sm = $sm;
        $this->em = $sm->get(EntityManager::class);
        $this->entities = $entities;
    }

    public function transactionModelTodb($transactionModel)
    {
        $this->transactionEventModel = $transactionModel->getParams()['transactionEventModel'];
        $this->moveOrderItemsFromToStorage();
    }


    public function moveOrderItemsFromToStorage()
    {
        if (empty($this->em->getRepository(Storage::class)->find($this->transactionEventModel->getFromStorage())->getStorageType()->getProductAwareStorageType())
            || empty($this->em->getRepository(Storage::class)->find($this->transactionEventModel->getToStorage())->getStorageType()->getProductAwareStorageType())
        ) {
            throw new \Exception('Vagy a forrás- vagy a cél tároló nem képes termékek tárolására!');
        }

        foreach ($this->transactionEventModel->getOrderItemCollectionModel()->getOrderItems() as $orderItemId => $productAmount) {
            $orderItem = $this->em->getRepository(OrderItemInStorage::class)->find($orderItemId);
            $orderItem->setStorage($this->transactionEventModel->getToStorage());
            $orderItem->setStockTransaction($this->transactionEventModel->getStockTransactionId());

            //TODO: #164 itt kell beállítani, hogy a price-ot is frissítse a beküldött modellben lévő price-szal,= a kedvezménylistener által ezelőtt módosított modellt
            $this->em->flush();

            foreach ($productAmount as $productId => $amount) {
                $this->addProductMoving($productId, $amount);
            }
        }
    }


    protected function addProductMoving($productId, $amount)
    {
        $productMoving = new $this->entities['ProductMoving']();
        $productMoving->setServiceManager($this->sm);
        $productMoving->setStockTransaction($this->transactionEventModel->getStockTransactionId());
        $productMoving->setProduct($productId);
        $productMoving->setAmount($amount);
        $this->em->persist($productMoving);
        $this->em->flush();

    }


}