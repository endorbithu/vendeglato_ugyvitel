<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.12.
 * Time: 22:57
 */


namespace Catalog\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;


/**
 * @Entity
 * @Table(name="cat_stock_transaction_type")
 */
class StockTransactionType
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
     * @Column(name="string_id", type="string", unique=true)
     */
    private $stringId;


    /**
     * @OneToMany(targetEntity="StockTransaction", mappedBy="stockTransactionType")
     **/
    private $stockTransaction;

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
    public function getStockTransaction()
    {
        return $this->stockTransaction;
    }

    /**
     * @param mixed $stockTransaction
     */
    public function setStockTransaction($stockTransaction)
    {
        $this->stockTransaction = $stockTransaction;
    }

    /**
     * @return mixed
     */
    public function getStringId()
    {
        return $this->stringId;
    }

    /**
     * @param mixed $stringId
     */
    public function setStringId($stringId)
    {
        $this->stringId = $stringId;
    }


    public function getArrayCopy() //Doctrinehoz kell
    {
        $arrayCopy = [];
        $arrayCopy['StockTransactionType'] = get_object_vars($this);
        if (array_key_exists('sm', $arrayCopy['StockTransactionType'])) {
            unset($arrayCopy['StockTransactionType']['sm']);
        }
        return $arrayCopy;
    }


}