<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.26.
 * Time: 7:21
 */

namespace Transaction\Model\StockTransaction;


class StockTransactionEventModel implements IStockTransactionEvent
{
    protected $transactionType;
    protected $toStorage;
    protected $fromStorage;
    protected $dateTime;
    protected $user;
    protected $eventManager;
    protected $moreInfo;
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