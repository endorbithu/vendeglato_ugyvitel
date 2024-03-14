<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.13.
 * Time: 20:04
 */

namespace Transaction\Model\StockTransaction;

//egy dimenziós tömb a kulcs az item id a value pedig az amount
class ItemCollectionModel
{

    protected $itemCollection = [];

    public function addItems(array $addToCollection)
    {

        foreach ($addToCollection as $itemId => $amount) {
            if (array_key_exists($itemId, $this->itemCollection)) {
                $this->itemCollection[$itemId] += $amount;
            } else {
                $this->itemCollection[$itemId] = $amount;
            }
        }

    }


    public function removeItems($item)
    {
        if (is_array($item)) {
            foreach ($item as $id => $itemOrAmount) {
                if (array_key_exists($id, $this->itemCollection)) {
                    if (!(($this->itemCollection[$id] -= $itemOrAmount) > 0)) unset($this->itemCollection[$id]);
                }
            }
            return;
        }

        if (array_key_exists($item, $this->itemCollection)) unset($this->itemCollection[$item]);

    }

    /**
     * @return array
     */
    public function getItemCollection()
    {
        return $this->itemCollection;
    }

    /**
     * @param array $itemCollection
     */
    public function setItemCollection($itemCollection)
    {
        $this->itemCollection = $itemCollection;
    }


}