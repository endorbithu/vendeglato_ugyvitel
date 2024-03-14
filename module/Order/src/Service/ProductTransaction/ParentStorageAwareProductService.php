<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.23.
 * Time: 9:15
 */

namespace Order\Service\ProductTransaction;


use Catalog\Entity\Product;
use Catalog\Entity\Storage;

class ParentStorageAwareProductService
{

    protected $em;

    public function __construct($em)
    {
        $this->em = $em;
    }

    public function differentProductStorage($postData)
    {
        $newPostDatas = $postData;
        if (!empty($storagesFrom = $this->em->getRepository(Storage::class)->getChildStorage($postData['StockTransaction']['fromStorage']))
            xor (!empty($storagesTo = $this->em->getRepository(Storage::class)->getChildStorage($postData['StockTransaction']['toStorage'])))
        ) {
            $storages = (!empty($storagesFrom)) ? $storagesFrom : $storagesTo;
            $newPostDatas = [];
            foreach ($storages as $i => $aStorage) {
                $newPostDatas[$i] = $postData;
                if (!empty($this->em->getRepository(Storage::class)->getChildStorage($postData['StockTransaction']['fromStorage']))) {
                    $newPostDatas[$i]['StockTransaction']['fromStorage'] = $aStorage->getId();
                } else {
                    $newPostDatas[$i]['StockTransaction']['toStorage'] = $aStorage->getId();
                }

                $newPostDatas[$i]['amount'] = $this->getOnlyOwnProduct($aStorage->getId(), $postData['amount']);
            }
        }
        return $newPostDatas;
    }


    public function getOnlyOwnProduct($storageId, $productAmount)
    {
        $products = $this->em->getRepository(Product::class)->getAvailableProducts($storageId);
        $poductIds = [];
        foreach ($products as $product) {
            $poductIds[] = $product->getId();
        }

        foreach ($productAmount as $postProductId => $postAmount) {
            if (!in_array($postProductId, $poductIds)) {
                unset($productAmount[$postProductId]);
            }
        }

        return $productAmount;
    }


    public function  differentoOrderItemStorage($postData)
    {
        $newPostDatas = $postData;
        if (!empty($storagesFrom = $this->em->getRepository(Storage::class)->getChildStorage($postData['StockTransaction']['fromStorage']))
            xor (!empty($storagesTo = $this->em->getRepository(Storage::class)->getChildStorage($postData['StockTransaction']['toStorage'])))
        ) {
            $storages = (!empty($storagesFrom)) ? $storagesFrom : $storagesTo;
            $newPostDatas = [];
            foreach ($storages as $i => $aStorage) {
                $newPostDatas[$i] = $postData;
                if (!empty($this->em->getRepository(Storage::class)->getChildStorage($postData['StockTransaction']['fromStorage']))) {
                    $newPostDatas[$i]['StockTransaction']['fromStorage'] = $aStorage->getId();
                } else {
                    $newPostDatas[$i]['StockTransaction']['toStorage'] = $aStorage->getId();
                }

                $newPostDatas[$i]['orderItem'] = $this->getOnlyOwnOrderItem($aStorage->getId(), $postData['orderItem']);
            }
        }

        return $newPostDatas;
    }


    public function getOnlyOwnOrderItem($storageId, $orderItem)
    {
        $products = $this->em->getRepository(Product::class)->getProductsOfStorage($storageId);
        $productIds = [];
        foreach ($products as $product) {
            $productIds[] = $product->getId();
        }

        foreach ($orderItem as $orderItemId => $postProductAmount) {
            foreach ($postProductAmount as $postProductId => $postAmount) {
                if (!in_array($postProductId, $productIds)) {
                    unset($orderItem[$orderItemId]);
                    continue 2;
                }
            }
        }
        return $orderItem;
    }

}