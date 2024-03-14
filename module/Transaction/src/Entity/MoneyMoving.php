<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.12.
 * Time: 22:57
 */


namespace Transaction\Entity;

use Application\Entity\InjectionOfEntity\ServiceMangerAwareEntity;
use Catalog\Entity\Money;
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
 * @Table(name="tra_money_moving")
 */
class MoneyMoving extends ServiceMangerAwareEntity
{

    /**
     * @Id
     * @GeneratedValue(strategy="AUTO")
     * @Column(type="integer")
     */
    private $id;



    /**
     * @ManyToOne(targetEntity="StockTransaction", inversedBy="moneyMoving", fetch="EAGER")
     * @JoinColumn(name="stock_transaction_id", referencedColumnName="id", onDelete="cascade")
     **/
    private $stockTransaction;


    /**
     * //azért kell mert a alap osztály ami a mozgásokat kezeli egységesen getItem() van
     * @ManyToOne(targetEntity="Catalog\Entity\Money", inversedBy="moneyMoving", fetch="EAGER")
     * @JoinColumn(name="item_id", referencedColumnName="id")
     **/
    private $item;


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
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param mixed $item
     */
    public function setItem($item)
    {
        if (is_object($item)) {
            $this->item = $item;
        } else {
            $itemFromEm = $this->sm->get('Doctrine\ORM\EntityManager')->getRepository(Money::class)->find($item);
            $this->item = $itemFromEm;
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
        $arrayCopy['MoneyMoving'] = get_object_vars($this);
        if (array_key_exists('sm', $arrayCopy['MoneyMoving'])) {
            unset($arrayCopy['MoneyMoving']['sm']);
        }
        return $arrayCopy;
    }




}