<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.08.
 * Time: 20:08
 */

namespace Transaction\Service\StockTransaction;


use Catalog\Entity\Storage;
use Doctrine\ORM\EntityManager;
use Transaction\Entity\StockTransaction;
use Transaction\Exception\MoreItemThanExistException;

/**
 * Ez az alapanyag tranzakció db-be írásáért felelős listener, lehet még egyéb  listener is pl bizonylatkészítés stb.
 * @package Transaction\Service\StockTransaction
 * @property  $transactionEventModel \Transaction\Model\StockTransaction\ItemTransactionEventModel
 */
class ItemTransactionDbListener
{

    protected $transactionEventModel;
    protected $sm;
    protected $em;
    protected $entities;
    protected $stockTransactionId;

    public function __construct($sm, $entities)
    {
        $this->sm = $sm;
        $this->em = $sm->get(EntityManager::class);
        $this->entities = $entities;

    }

    public function transactionModelTodb($transactionModel)
    {
        $this->transactionEventModel = $transactionModel->getParams()['transactionEventModel'];

        //ha a tranzakció során nem volt item mozgás
        if (empty($this->transactionEventModel->getItemCollectionModel()->getItemCollection())) return;

        $this->validAmount();
        $this->refreshStockAmountStatus();
        $this->insertAllMovedItemAndAmount();
    }


    public function validAmount()
    {

        if (empty($this->transactionEventModel->getItemCollectionModel()->getItemCollection())) return true;

        //megnézzük, hogy nem-e lépte túl a készleten lévő mennyiséget a megadott mennyiség

        if (!empty($this->em->getRepository(Storage::class)->find((int)$this->transactionEventModel->getFromStorage())->getStorageType()->getIsRealStorageType())) {
            $fromStorageStock = $this->em->getRepository($this->entities['Stock'])->findBy(['storage' => $this->transactionEventModel->getFromStorage()]);
            $amountOnlyOwnStorage = [];
            foreach ($fromStorageStock as $itemAmount) {
                if (!(array_key_exists($itemAmount->getItem()->getId(), $this->transactionEventModel->getItemCollectionModel()->getItemCollection()))) continue;
                if ($itemAmount->getAmount() < $this->transactionEventModel->getItemCollectionModel()->getItemCollection()[$itemAmount->getItem()->getId()]
                ) {
                    //Ha nem ok akkor töröljük az előbbi listener által létrehozott  StockTransactiont
                    $stockTransaction = $this->em->getRepository(StockTransaction::class)->find($this->transactionEventModel->getStockTransactionId());
                    $this->em->remove($stockTransaction);
                    $this->em->flush();
                    throw new MoreItemThanExistException('Egy vagy több megadott mennyiség meghaladja a készleten lévő mennyiséget, vagy nincs készleten!');
                } else {
                    $amountOnlyOwnStorage[$itemAmount->getItem()->getId()] = $this->transactionEventModel->getItemCollectionModel()->getItemCollection()[$itemAmount->getItem()->getId()];
                }
            }

            $this->transactionEventModel->getItemCollectionModel()->setItemCollection($amountOnlyOwnStorage); //így kirostáljuk azokat az itemeket. amik nincsenek is neki

        }
        return true;
    }


    protected function refreshStockAmountStatus()
    {
        $this->refreshFromStorageStock();
        $this->refreshToStorageStock();
    }


    protected function refreshFromStorageStock()
    {
        if (empty($this->em->getRepository(Storage::class)->find((int)$this->transactionEventModel->getFromStorage())->getStorageType()->getIsRealStorageType())) return;

        //a fromStorage-ből levonjuk, e fázis előtt már csekkoltuk, hogy ne legyen több a levonás mint a meglevő
        foreach ($this->transactionEventModel->getItemCollectionModel()->getItemCollection() as $itemId => $amount) {
            $actualStockRow = $this->em->getRepository($this->entities['Stock'])->findBy(['storage' => $this->transactionEventModel->getFromStorage(), 'item' => $itemId])[0];
            $actualItemAmount = (float)$actualStockRow->getAmount();
            $correctedItemAmount = $actualItemAmount - $amount;
            $actualStockRow->setAmount($correctedItemAmount);

            $this->em->flush();
        }
    }

    protected function refreshToStorageStock()
    {
        if (empty($this->em->getRepository(Storage::class)->find((int)$this->transactionEventModel->getToStorage())->getStorageType()->getIsRealStorageType())) return;

        //A toStorage státuszát beállítjuk, ha nincs ilyen item, akkor bejegyezzük
        foreach ($this->transactionEventModel->getItemCollectionModel()->getItemCollection() as $ItemId => $amount) {
            if (!empty($this->em->getRepository($this->entities['Stock'])->findBy(['storage' => $this->transactionEventModel->getToStorage(), 'item' => $ItemId]))) {
                $actualStockRow = $this->em->getRepository($this->entities['Stock'])->findBy(['storage' => $this->transactionEventModel->getToStorage(), 'item' => $ItemId])[0];
                $actualItemAmount = (float)$actualStockRow->getAmount();
                $correctedItemAmount = $actualItemAmount + $amount;
                $actualStockRow->setAmount($correctedItemAmount);
                $this->em->flush();
            } else {
                $newStockRow = new $this->entities['Stock']();
                $newStockRow->setServiceManager($this->sm);

                $newStockRow->setAmount($amount);
                $newStockRow->setStorage($this->transactionEventModel->getToStorage());
                $newStockRow->setItem($ItemId);
                $this->em->persist($newStockRow);
                $this->em->flush();
            }

        }
    }


    protected function insertAllMovedItemAndAmount()
    {
        foreach ($this->transactionEventModel->getItemCollectionModel()->getItemCollection() as $itemId => $amount) {
            $newMovedItem = new $this->entities['ItemMoving']();
            $newMovedItem->setServiceManager($this->sm);

            $newMovedItem->setStockTransaction($this->transactionEventModel->getStockTransactionId());
            $newMovedItem->setItem($itemId);
            $newMovedItem->setAmount($amount);
            $this->em->persist($newMovedItem);
            $this->em->flush();
        }
    }

}