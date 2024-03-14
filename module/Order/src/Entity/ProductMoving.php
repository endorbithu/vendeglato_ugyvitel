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
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;


/**
 * @Entity
 * @Table(name="ord_product_moving")
 */
class ProductMoving extends ServiceMangerAwareEntity
{

    /**
     * @Id
     * @GeneratedValue(strategy="AUTO")
     * @Column(type="integer")
     */
    private $id;


    /**
     * @ManyToOne(targetEntity="Transaction\Entity\StockTransaction", inversedBy="productMoving", fetch="EAGER")
     * @JoinColumn(name="stock_transaction_id", referencedColumnName="id", onDelete="cascade")
     **/
    private $stockTransaction;


    /**
     * @ManyToOne(targetEntity="Catalog\Entity\Product", inversedBy="productMoving", fetch="EAGER")
     * @JoinColumn(name="product_id", referencedColumnName="id")
     **/
    private $product;


    /**
     * @Column(name="amount", type="integer")
     */
    private $amount;


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


    public function getArrayCopy() //Doctrinehoz kell
    {
        $arrayCopy = [];
        $arrayCopy['IngredientMoving'] = get_object_vars($this);
        if (array_key_exists('sm', $arrayCopy['IngredientMoving'])) {
            unset($arrayCopy['IngredientMoving']['sm']);
        }
        return $arrayCopy;
    }


}