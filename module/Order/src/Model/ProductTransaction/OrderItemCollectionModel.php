<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.23.
 * Time: 8:20
 */

namespace Order\Model\ProductTransaction;


class OrderItemCollectionModel extends ProductCollectionModel
{

    protected $orderItemCollection = [];
    protected $repository;


    public function __construct($repository)
    {
        parent::__construct($repository);
    }

    public function setOrderItems(array $orderItems)
    {
        //TODO: #161 ??? Itt beállítjuk a orderItemCollection, és foreachel a productCollection-t, ami ugye az ingredeintCollectiont
    }

    public function getOrderItems()
    {
        return $this->orderItemCollection;
    }


    public function addOrderItems(array $orderItems)
    {
        foreach ($orderItems as $orderItemId => $productAmount) {
            $this->orderItemCollection[(int)$orderItemId] = $orderItems[$orderItemId];
            $this->addProducts($this->orderItemCollection[$orderItemId]);
        }

    }


    public function removeOrderItems(array $orderItems)
    {
        foreach ($orderItems as $orderItemId => $productAmount) {
            if (array_key_exists($orderItemId, $this->orderItemCollection)) {
                unset($this->orderItemCollection[(int)$orderItemId]);
                $this->removeProducts($this->orderItemCollection[(int)$orderItemId]);
            }


        }
        return;
    }

}