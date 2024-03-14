<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.13.
 * Time: 19:32
 */

namespace Catalog\Model\StockTransaction;


class IngredientTransactionEventModel extends StockTransactionEventModel implements IIngredientTransactionEvent
{
    protected $ingredientCollectionModel;


    /**
     * @return mixed
     */
    public function getIngredientCollectionModel()
    {
        return $this->ingredientCollectionModel;
    }

    /**
     * @param mixed $ingredientCollectionModel
     */
    public function setIngredientCollectionModel($ingredientCollectionModel)
    {
        $this->ingredientCollectionModel = $ingredientCollectionModel;
    }


}