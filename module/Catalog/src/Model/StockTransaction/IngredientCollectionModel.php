<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.13.
 * Time: 20:04
 */

namespace Catalog\Model\StockTransaction;

//egy dimenziós tömb a kulcs az ingredient id a value pedig az amount
class IngredientCollectionModel
{

    protected $ingredientCollection = [];

    public function addIngredients(array $addToCollection)
    {

        foreach ($addToCollection as $ingrId => $amount) {
            if (array_key_exists($ingrId, $this->ingredientCollection)) {
                $this->ingredientCollection[$ingrId] += $amount;
            } else {
                $this->ingredientCollection[$ingrId] = $amount;
            }
        }

    }


    public function removeIngredients($ingredient)
    {
        if (is_array($ingredient)) {
            foreach ($ingredient as $id => $ingrOrAmount) {
                if (array_key_exists($id, $this->ingredientCollection)) {
                    if (!(($this->ingredientCollection[$id] -= $ingrOrAmount) > 0)) unset($this->ingredientCollection[$id]);
                }
            }
            return;
        }

        if (array_key_exists($ingredient, $this->ingredientCollection)) unset($this->ingredientCollection[$ingredient]);

    }

    /**
     * @return array
     */
    public function getIngredientCollection()
    {
        return $this->ingredientCollection;
    }

    /**
     * @param array $ingredientCollection
     */
    public function setIngredientCollection($ingredientCollection)
    {
        $this->ingredientCollection = $ingredientCollection;
    }


}