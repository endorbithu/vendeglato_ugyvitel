<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.13.
 * Time: 20:04
 */

namespace Order\Model\ProductTransaction;

use Transaction\Model\StockTransaction\ItemCollectionModel;

class ProductCollectionModel extends ItemCollectionModel
{

    protected $productCollection = [];
    protected $repository;

    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function addProducts(array $products)
    {
        foreach ($products as $id => $productOrAmount) {
            if (array_key_exists($id, $this->productCollection)) {
                $this->productCollection[$id] += $productOrAmount;
            } else {
                $this->productCollection[$id] = $productOrAmount;
            }
            $this->addItems($this->repository->getIngredientsAmountOfProduct([$id => $productOrAmount]));
        }
    }


    //muszály megadni, hogy mennyit törlünk mertaz ingredientnek mindenképp tudnia kell mennyit kell törölni
    public function removeProducts(array $product)
    {
        foreach ($product as $id => $productOrAmount) {
            if (array_key_exists($id, $this->productCollection)) {
                if ($productOrAmount > $this->productCollection[$id]) throw new \Exception('Nincs ennyi termék a collectionben!');
                if (!(($this->productCollection[$id] -= $productOrAmount) > 0)) unset($this->productCollection[$id]);
            }
            $this->removeItems($this->repository->getIngredientsAmountOfProduct([$id => $productOrAmount]));
        }
        return;
    }


    /**
     * @param array $productCollection
     */
    public function setProductCollection($productCollection)
    {
        $this->productCollection = $productCollection;

        foreach ($this->productCollection as $productId => $amount) {
            $this->addItems($this->repository->getIngredientsAmountOfProduct([$productId => $amount]));
        }
    }


    /**
     * @return array
     */
    public function getProductCollection()
    {
        return $this->productCollection;
    }


}