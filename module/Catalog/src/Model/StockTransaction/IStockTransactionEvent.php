<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.19.
 * Time: 18:16
 */

namespace Catalog\Model\StockTransaction;


interface IStockTransactionEvent
{

    public function getMoreInfo();

    public function setMoreInfo($moreInfo);

    public function getTransactionType();

    public function setTransactionType($transactionType);

    public function getToStorage();

    public function setToStorage($toStorage);

    public function getFromStorage();

    public function setFromStorage($fromStorage);

    public function getDateTime();

    public function setDateTime($dateTime);

    public function getUser();

    public function setUser($user);

    public function getStockTransactionId();

    public function setStockTransactionId($stockTransactionId);


}