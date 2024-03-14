<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.19.
 * Time: 18:12
 */

namespace Order\Model\ProductTransaction;


use Transaction\Model\StockTransaction\IStockTransactionEvent;

interface IProductTransactionEvent extends IStockTransactionEvent
{
    public function getProductCollectionModel();

    public function setProductCollectionModel(ProductCollectionModel $ingredientCollectionModel);
}