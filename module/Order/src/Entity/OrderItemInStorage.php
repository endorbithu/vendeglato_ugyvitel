<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.12.
 * Time: 22:57
 */


namespace Order\Entity;

use Application\Entity\InjectionOfEntity\ServiceMangerAwareEntity;
use Transaction\Entity\StockTransaction;
use Catalog\Entity\Product;
use Catalog\Entity\Storage;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\JoinTable;


/**
 * @Entity
 * @Table(name="ord_order_item_in_storage")
 */
class OrderItemInStorage extends ServiceMangerAwareEntity
{

    /**
     * @Id
     * @GeneratedValue(strategy="AUTO")
     * @Column(type="integer")
     */
    private $id;


    /**
     * @ManyToOne(targetEntity="Catalog\Entity\Storage", inversedBy="productStorage", fetch="EAGER")
     * @JoinColumn(name="storage_id", referencedColumnName="id")
     **/
    private $storage;


    /**
     * @ManyToOne(targetEntity="Catalog\Entity\Product", inversedBy="productStorage", fetch="EAGER")
     * @JoinColumn(name="product_id", referencedColumnName="id")
     **/
    private $product;


    /**
     * @Column(name="amount", type="integer")
     */
    private $amount;


    /**
     * @Column(name="price", type="decimal", precision=8, scale=2)
     */
    private $price;


    /**
     * @ManyToOne(targetEntity="Transaction\Entity\StockTransaction", inversedBy="orderItemInStorage", fetch="EAGER")
     * @JoinColumn(name="stock_transaction_id", referencedColumnName="id", onDelete="cascade")
     **/
    private $stockTransaction;


    /**
     * @Column(name="more_info", type="string", nullable=true)
     */
    //private $moreInfo;


    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $product
     */
    public function setProduct($product)
    {
        if (is_object($product)) {
            $this->product = $product;
        } else {
            $productFromEm = $this->sm->get('Doctrine\ORM\EntityManager')->getRepository(Product::class)->find($product);
            $this->product = $productFromEm;
        }
    }

    /**
     * @return mixed
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * @param mixed $storage
     */
    public function setStorage($storage)
    {
        if (is_object($storage)) {
            $this->storage = $storage;
        } else {
            $storageFromEm = $this->sm->get('Doctrine\ORM\EntityManager')->getRepository(Storage::class)->find($storage);
            $this->storage = $storageFromEm;
        }
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->sm->get('currencyConverter')->getConvertedPrice($this->price);
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $this->sm->get('currencyConverter')->setSystemPriceFromForeign($price);
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
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getMoreInfo()
    {
        return $this->moreInfo;
    }

    /**
     * @param mixed $moreInfo
     */
    public function setMoreInfo($moreInfo)
    {
        $this->moreInfo = $moreInfo;
    }


    /**
     * @return mixed
     */
    public function getStockTransaction()
    {
        return $this->stockTransaction;
    }

    /**
     * @param mixed $stockTransaction
     */
    public function setStockTransaction($stockTransaction)
    {
        if (is_object($stockTransaction)) {
            $this->stockTransaction = $stockTransaction;
        } else {
            $ingrTransactionFromEm = $this->sm->get('Doctrine\ORM\EntityManager')->getRepository(StockTransaction::class)->find($stockTransaction);
            $this->stockTransaction = $ingrTransactionFromEm;
        }
    }


    public function getArrayCopy() //Doctrinehoz kell
    {
        $arrayCopy = [];
        $arrayCopy['OrderItemInStorage'] = get_object_vars($this);
        if (array_key_exists('sm', $arrayCopy['OrderItemInStorage'])) {
            unset($arrayCopy['OrderItemInStorage']['sm']);
        }
        return $arrayCopy;
    }

}