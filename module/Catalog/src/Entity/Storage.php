<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.12.
 * Time: 22:57
 */


namespace Catalog\Entity;

use Application\Entity\InjectionOfEntity\ServiceMangerAwareEntity;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Transaction\Entity\MoneyStock;
use Transaction\Entity\Stock;
use Transaction\Entity\ToolStock;


/**
 * @Entity(repositoryClass="Catalog\Repository\StorageRepository")
 * @Table(name="cat_storage")
 */
class Storage extends ServiceMangerAwareEntity
{

    /**
     * @Id
     * @GeneratedValue(strategy="AUTO")
     * @Column(type="integer")
     */
    private $id;


    /**
     * @Column(type="string")
     */
    private $name;


    /**
     * @ManyToOne(targetEntity="StorageType", inversedBy="storage", fetch="EAGER")
     * @JoinColumn(name="storage_type",  referencedColumnName="id")
     **/
    private $storageType;


    /**
     * @ManyToOne(targetEntity="Supplier", inversedBy="storage", fetch="EAGER")
     * @JoinColumn(name="supplier",  referencedColumnName="id")
     **/
    private $supplier;


    /**
     * Alapértelmezett beszállítót hozzárendelhetünk az alapanyagokhoz
     * @OneToMany(targetEntity="Ingredient", mappedBy="storage")
     **/
    // private $ingredient;


    /**
     * @OneToMany(targetEntity="Transaction\Entity\Stock", mappedBy="storage")
     **/
    private $stock;

    /**
     * @OneToMany(targetEntity="Transaction\Entity\ToolStock", mappedBy="storage")
     **/
    private $toolStock;

    /**
     * @OneToMany(targetEntity="Transaction\Entity\MoneyStock", mappedBy="storage")
     **/
    private $moneyStock;


    /**
     * @ManyToOne(targetEntity="Storage", inversedBy="childStorage")
     * @JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parentStorage;

    /**
     * @OneToMany(targetEntity="Storage", mappedBy="parentStorage", cascade={"all"}, fetch="EAGER")
     */
    private $childStorage;


    /**
     * @OneToMany(targetEntity="Order\Entity\OrderItemInStorage", mappedBy="storage")
     **/
    private $productStorage;


    /**
     * @OneToMany(targetEntity="Transaction\Entity\StockTransaction", mappedBy="fromStorage")
     **/
    private $stockTransactionFrom;

    /**
     * @OneToMany(targetEntity="Transaction\Entity\StockTransaction", mappedBy="fromStorage")
     **/
    private $stockTransactionTo;

    /**
     * @return mixed
     */
    public function getStockTransactionFrom()
    {
        return $this->stockTransactionFrom;
    }

    /**
     * @param mixed $stockTransactionFrom
     */
    public function setStockTransactionFrom($stockTransactionFrom)
    {
        $this->stockTransactionFrom = $stockTransactionFrom;
    }

    /**
     * @return mixed
     */
    public function getStockTransactionTo()
    {
        return $this->stockTransactionTo;
    }

    /**
     * @param mixed $stockTransactionTo
     */
    public function setStockTransactionTo($stockTransactionTo)
    {
        $this->stockTransactionTo = $stockTransactionTo;
    }


    /**
     * @return mixed
     */
    public function getStorageType()
    {
        return $this->storageType;
    }

    /**
     * @param mixed $storageType
     */
    public function setStorageType($storageType)
    {
        if (is_object($storageType)) {
            $this->storageType = $storageType;
        } else {
            $storageTypeFromEm = $this->sm->get('Doctrine\ORM\EntityManager')->getRepository(StorageType::class)->find($storageType);
            $this->storageType = $storageTypeFromEm;
        }
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSupplier()
    {
        return $this->supplier;
    }

    /**
     * @param mixed $supplier
     */
    public function setSupplier($supplier)
    {
        if (is_object($supplier)) {
            $this->supplier = $supplier;
        } else {
            $supplierFromEm = $this->sm->get('Doctrine\ORM\EntityManager')->getRepository(Supplier::class)->find($supplier);
            $this->supplier = $supplierFromEm;
        }
    }

    /**
     * @return mixed
     */
    public function getParentStorage()
    {
        return $this->parentStorage;
    }

    /**
     * @param mixed $parentStorage
     */
    public function setParentStorage($parentStorage)
    {
        if (is_object($parentStorage)) {
            $this->parentStorage = $parentStorage;
        } else {
            $parentStorageFromEm = $this->sm->get(EntityManager::class)->getRepository(Storage::class)->find($parentStorage);
            $this->parentStorage = $parentStorageFromEm;
        }
    }

    /**
     * @return mixed
     */
    public function getChildStorage()
    {
        return $this->childStorage;
    }

    /**
     * @param mixed $childStorage
     */
    public function setChildStorage($childStorage)
    {
        $this->childStorage = $childStorage;
    }


    /**
     * @return mixed
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * @param mixed $stock
     */
    public function setStock($stock)
    {
        $this->stock = $stock;
        if (is_object($stock)) {
            $this->stock = $stock;
        } else {
            $stockFromEm = $this->sm->get(EntityManager::class)->getRepository(Stock::class)->find($stock);
            $this->stock = $stockFromEm;
        }
    }

    /**
     * @return mixed
     */
    public function getToolStock()
    {
        return $this->toolStock;
    }

    /**
     * @param mixed $toolStock
     */
    public function setToolStock($toolStock)
    {
        $this->stock = $toolStock;
        if (is_object($toolStock)) {
            $this->toolStock = $toolStock;
        } else {
            $toolStockFromEm = $this->sm->get(EntityManager::class)->getRepository(ToolStock::class)->find($toolStock);
            $this->toolStock = $toolStockFromEm;
        }
    }

    /**
     * @return mixed
     */
    public function getMoneyStock()
    {
        return $this->moneyStock;
    }

    /**
     * @param mixed $moneyStock
     */
    public function setMoneyStock($moneyStock)
    {
        $this->stock = $moneyStock;
        if (is_object($moneyStock)) {
            $this->moneyStock = $moneyStock;
        } else {
            $moneyStockFromEm = $this->sm->get(EntityManager::class)->getRepository(MoneyStock::class)->find($moneyStock);
            $this->moneyStock = $moneyStockFromEm;
        }
    }




    /**
     * @return mixed
     */
    public function getProductStorage()
    {
        return $this->productStorage;
    }

    /**
     * @param mixed $productStorage
     */
    public function setProductStorage($productStorage)
    {
        $this->productStorage = $productStorage;
    }


    public function getArrayCopy() //Doctrinehoz kell
    {
        $arrayCopy = [];
        $arrayCopy['Storage'] = get_object_vars($this);
        if (array_key_exists('sm', $arrayCopy['Storage'])) {
            unset($arrayCopy['Storage']['sm']);
        }
        return $arrayCopy;
    }


}