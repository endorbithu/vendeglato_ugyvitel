<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.19.
 * Time: 18:10
 */

namespace Catalog\Model\StockTransaction;


interface IIngredientTransactionEvent extends IStockTransactionEvent
{

    public function getIngredientCollectionModel();

    public function setIngredientCollectionModel($ingredientCollectionModel);




}