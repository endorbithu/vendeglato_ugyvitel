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
use Catalog\Entity\Storage;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;





/**
 * @Entity(repositoryClass="Transaction\Repository\MoneyStockRepository")
 * @Table(name="tra_money_stock")
 */
class MoneyStock extends ServiceMangerAwareEntity
{

    /**
     * @Id
     * @GeneratedValue(strategy="AUTO")
     * @Column(type="integer")
     */
    private $id;

    

    /**
     * @ManyToOne(targetEntity="Catalog\Entity\Storage", inversedBy="moneyStock", fetch="EAGER")
     * @JoinColumn( name="storage_id", referencedColumnName="id")
     **/
    private $storage;



    /**
     * Ezt azért nem toolnak neveztem el, mert a toolnak pénznek stb is kell egy stockk és ugyanúfy itemnek kell lennie
     * @ManyToOne(targetEntity="Catalog\Entity\Money", inversedBy="moneyStock", fetch="EAGER")
     * @JoinColumn(name="item_id", referencedColumnName="id")
     **/
    private $item;



    /**
     * @Column(name="amount", type="decimal", precision=8, scale=2)
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
        $arrayCopy['MoneyStock'] = get_object_vars($this);
        if (array_key_exists('sm', $arrayCopy['MoneyStock'])) {
            unset($arrayCopy['MoneyStock']['sm']);
        }
        return $arrayCopy;
    }





}