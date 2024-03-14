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
 * @Table(name="cat_money_unit")
 */
class MoneyUnit
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
     * @Column(name="short_name", type="string")
     */
    private $shortName;


    /**
     * @Column(name="is_decimal", type="boolean", options={"default":0})
     */
    private $isDecimal;

    /**
     * @OneToMany(targetEntity="Money", mappedBy="moneyUnit")
     **/
    private $money;


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
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * @param mixed $shortName
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
    }

    /**
     * @return mixed
     */
    public function getMoney()
    {
        return $this->money;
    }

    /**
     * @param mixed $money
     */
    public function setMoney($money)
    {
        $this->money = $money;
    }

    /**
     * @return mixed
     */
    public function getIsDecimal()
    {
        return $this->isDecimal;
    }

    /**
     * @param mixed $isDecimal
     */
    public function setIsDecimal($isDecimal)
    {
        $this->isDecimal = $isDecimal;
    }


    public function getArrayCopy() //Doctrinehoz kell
    {
        $arrayCopy = [];
        $arrayCopy['MoneyUnit'] = get_object_vars($this);
        if (array_key_exists('sm', $arrayCopy['MoneyUnit'])) {
            unset($arrayCopy['MoneyUnit']['sm']);
        }
        return $arrayCopy;
    }

}