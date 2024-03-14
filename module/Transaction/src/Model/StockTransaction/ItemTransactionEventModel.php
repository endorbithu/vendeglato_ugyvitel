<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.13.
 * Time: 19:32
 */

namespace Transaction\Model\StockTransaction;


class ItemTransactionEventModel extends StockTransactionEventModel implements IItemTransactionEvent
{
    protected $ItemCollectionModel;


    /**
     * @return mixed
     */
    public function getItemCollectionModel()
    {
        return $this->ItemCollectionModel;
    }

    /**
     * @param mixed $itemCollectionModel
     */
    public function setItemCollectionModel($itemCollectionModel)
    {
        $this->ItemCollectionModel = $itemCollectionModel;
    }


}