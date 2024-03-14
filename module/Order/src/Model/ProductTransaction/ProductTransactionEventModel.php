<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.19.
 * Time: 17:51
 */

namespace Order\Model\ProductTransaction;


use Transaction\Model\StockTransaction\IItemTransactionEvent;
use Transaction\Model\StockTransaction\IStockTransactionEvent;

class ProductTransactionEventModel implements IStockTransactionEvent, IItemTransactionEvent, IProductTransactionEvent
{
    protected $transactionType;
    protected $toStorage;
    protected $fromStorage;
    protected $dateTime;
    protected $user;
    protected $eventManager;
    protected $moreInfo;
    protected $productCollectionModel;
    protected $stockTransactionId;

    /**
     * @return mixed
     */
    public function getStockTransactionId()
    {
        return $this->stockTransactionId;
    }

    /**
     * @param mixed $stockTransactionId
     */
    public function setStockTransactionId($stockTransactionId)
    {
        $this->stockTransactionId = $stockTransactionId;
    }

    public function getItemCollectionModel()
    {
        return $this->productCollectionModel;
    }

    public function getProductCollectionModel()
    {
        return $this->productCollectionModel;
    }

    public function setProductCollectionModel(ProductCollectionModel $collectonModel)
    {
        $this->productCollectionModel = $collectonModel;
    }

    /**
     * @param mixed $ingredientCollectionModel
     */
    public function setItemCollectionModel($ingredientCollectionModel)
    {
        return;
    }


    /**
     * @return mixed
     */
    public function getMoreInfo()
    {
        return $this->moreInfo;
    }

    /**
     * @param mixed $moreInfo
     */
    public function setMoreInfo($moreInfo)
    {
        $this->moreInfo = $moreInfo;
    }


    /**
     * @return mixed
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }

    /**
     * @param mixed $transactionType
     */
    public function setTransactionType($transactionType)
    {
        $this->transactionType = $transactionType;
    }


    /**
     * @return mixed
     */
    public function getToStorage()
    {
        return $this->toStorage;
    }

    /**
     * @param mixed $toStorage
     */
    public function setToStorage($toStorage)
    {
        $this->toStorage = $toStorage;
    }

    /**
     * @return mixed
     */
    public function getFromStorage()
    {
        return $this->fromStorage;
    }

    /**
     * @param mixed $fromStorage
     */
    public function setFromStorage($fromStorage)
    {
        $this->fromStorage = $fromStorage;
    }

    /**
     * @return mixed
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * @param mixed $dateTime
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }


}