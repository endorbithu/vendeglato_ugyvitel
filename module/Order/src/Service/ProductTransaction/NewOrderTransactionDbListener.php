<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.20.
 * Time: 21:19
 */

namespace Order\Service\ProductTransaction;

use Catalog\Entity\Product;
use Catalog\Entity\Storage;
use Doctrine\ORM\EntityManager;

class NewOrderTransactionDbListener
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
        $this->moveProductsFromToStorage();
    }


    public function moveProductsFromToStorage()
    {
        if (empty($this->em->getRepository(Storage::class)->find($this->transactionEventModel->getFromStorage())->getStorageType()->getProductAwareStorageType())
            || empty($this->em->getRepository(Storage::class)->find($this->transactionEventModel->getToStorage())->getStorageType()->getProductAwareStorageType())
        ) {
            throw new \Exception('Vagy a forrás- vagy a cél tároló nem képes termékek tárolására!');
        }

        foreach ($this->transactionEventModel->getProductCollectionModel()->getProductCollection() as $productId => $amount) {
            $product = $this->em->getRepository(Product::class)->find($productId);
            $productStorageEntity = new $this->entities['OrderItemInStorage']();
            $productStorageEntity->setServiceManager($this->sm);
            $productStorageEntity->setStorage($this->transactionEventModel->getToStorage());
            $productStorageEntity->setProduct($productId);
            $productStorageEntity->setAmount($amount);
            $productStorageEntity->setPrice(($amount * $product->getPrice()));

            $productStorageEntity->setStockTransaction($this->transactionEventModel->getStockTransactionId());

            $this->em->persist($productStorageEntity);
            $this->em->flush();

            $this->addProductMoving($productId, $amount);
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