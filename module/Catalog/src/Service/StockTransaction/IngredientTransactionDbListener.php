<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.08.
 * Time: 20:08
 */

namespace Catalog\Service\StockTransaction;


use Catalog\Entity\Stock;
use Catalog\Entity\StockTransaction;
use Catalog\Entity\Storage;
use Catalog\Exception\MoreIngredientThanExistException;
use Doctrine\ORM\EntityManager;

/**
 * Ez az alapanyag tranzakció db-be írásáért felelős listener, lehet még egyéb  listener is pl bizonylatkészítés stb.
 * @package Catalog\Service\StockTransaction
 * @property  $transactionEventModel \Catalog\Model\StockTransaction\IngredientTransactionEventModel
 */
class IngredientTransactionDbListener
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

        //ha a tranzakció során nem volt ingredientmozgás
        if (empty($this->transactionEventModel->getIngredientCollectionModel()->getIngredientCollection())) return;

        $this->validAmount();
        $this->refreshStockAmountStatus();
        $this->insertAllMovedIngredientAndAmount();
    }


    public function validAmount()
    {

        if (empty($this->transactionEventModel->getIngredientCollectionModel()->getIngredientCollection())) return true;

        //megnézzük, hogy nem-e lépte túl a készleten lévő mennyiséget a megadott mennyiség

        if (!empty($this->em->getRepository(Storage::class)->find((int)$this->transactionEventModel->getFromStorage())->getStorageType()->getRealStorageType())) {
            $fromStorageStock = $this->em->getRepository(Stock::class)->findBy(['storage' => $this->transactionEventModel->getFromStorage()]);
            $amountOnlyOwnStorage = [];
            foreach ($fromStorageStock as $ingrAmount) {
                if (!(array_key_exists($ingrAmount->getIngredient()->getId(), $this->transactionEventModel->getIngredientCollectionModel()->getIngredientCollection()))) continue;
                if ($ingrAmount->getAmount() < $this->transactionEventModel->getIngredientCollectionModel()->getIngredientCollection()[$ingrAmount->getIngredient()->getId()]
                ) {
                    //Ha nem ok akkor töröljük az előbbi listener által létrehozott  StockTransactiont
                    $stockTransaction = $this->em->getRepository(StockTransaction::class)->find($this->transactionEventModel->getStockTransactionId());
                    $this->em->remove($stockTransaction);
                    $this->em->flush();
                    throw new MoreIngredientThanExistException('Egy vagy több megadott mennyiség meghaladja a készleten lévő mennyiséget, vagy nincs készleten!');
                } else {
                    $amountOnlyOwnStorage[$ingrAmount->getIngredient()->getId()] = $this->transactionEventModel->getIngredientCollectionModel()->getIngredientCollection()[$ingrAmount->getIngredient()->getId()];
                }
            }

            $this->transactionEventModel->getIngredientCollectionModel()->setIngredientCollection($amountOnlyOwnStorage); //így kirostáljuk azokat az ingr. amik nincsenek is neki

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
        if (empty($this->em->getRepository(Storage::class)->find((int)$this->transactionEventModel->getFromStorage())->getStorageType()->getRealStorageType())) return;

        //a fromStorage-ből levonjuk, e fázis előtt már csekkoltuk, hogy ne legyen több a levonás mint a meglevő
        foreach ($this->transactionEventModel->getIngredientCollectionModel()->getIngredientCollection() as $ingrId => $amount) {
            $actualStockRow = $this->em->getRepository(Stock::class)->findBy(['storage' => $this->transactionEventModel->getFromStorage(), 'ingredient' => $ingrId])[0];
            $actualIngredientAmount = (float)$actualStockRow->getAmount();
            $correctedIngredientAmount = $actualIngredientAmount - $amount;
            $actualStockRow->setAmount($correctedIngredientAmount);

            $this->em->flush();
        }
    }

    protected function refreshToStorageStock()
    {
        if (empty($this->em->getRepository(Storage::class)->find((int)$this->transactionEventModel->getToStorage())->getStorageType()->getRealStorageType())) return;

        //A toStorage státuszát beállítjuk, ha nincs ilyen ingredient, akkor bejegyezzük
        foreach ($this->transactionEventModel->getIngredientCollectionModel()->getIngredientCollection() as $ingrId => $amount) {
            if (!empty($this->em->getRepository(Stock::class)->findBy(['storage' => $this->transactionEventModel->getToStorage(), 'ingredient' => $ingrId]))) {
                $actualStockRow = $this->em->getRepository(Stock::class)->findBy(['storage' => $this->transactionEventModel->getToStorage(), 'ingredient' => $ingrId])[0];
                $actualIngredientAmount = (float)$actualStockRow->getAmount();
                $correctedIngredientAmount = $actualIngredientAmount + $amount;
                $actualStockRow->setAmount($correctedIngredientAmount);
                $this->em->flush();
            } else {
                $newStockRow = new $this->entities['Stock']();
                $newStockRow->setServiceManager($this->sm);

                $newStockRow->setAmount($amount);
                $newStockRow->setStorage($this->transactionEventModel->getToStorage());
                $newStockRow->setIngredient($ingrId);
                $this->em->persist($newStockRow);
                $this->em->flush();
            }

        }
    }


    protected function insertAllMovedIngredientAndAmount()
    {
        foreach ($this->transactionEventModel->getIngredientCollectionModel()->getIngredientCollection() as $ingrId => $amount) {
            $newMovedIngredient = new $this->entities['IngredientMoving']();
            $newMovedIngredient->setServiceManager($this->sm);

            $newMovedIngredient->setStockTransaction($this->transactionEventModel->getStockTransactionId());
            $newMovedIngredient->setIngredient($ingrId);
            $newMovedIngredient->setAmount($amount);
            $this->em->persist($newMovedIngredient);
            $this->em->flush();
        }
    }

}