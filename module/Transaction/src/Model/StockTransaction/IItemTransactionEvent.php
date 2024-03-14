<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.19.
 * Time: 18:10
 */

namespace Transaction\Model\StockTransaction;


interface IItemTransactionEvent extends IStockTransactionEvent
{

    public function getItemCollectionModel();

    public function setItemCollectionModel($itemCollectionModel);




}