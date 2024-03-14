<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.12.
 * Time: 22:57
 */


namespace Catalog\Entity;

use Application\Entity\InjectionOfEntity\ServiceMangerAwareEntity;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinTable;

/**
 * //Entity(repositoryClass="Catalog\Repository\MoneyRepository")
 * @Entity
 * @Table(name="cat_money")
 */
class Money extends ServiceMangerAwareEntity
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
     * @ManyToOne(targetEntity="MoneyGroup", inversedBy="money", fetch="EAGER")
     * @JoinColumn(name="money_group_id", referencedColumnName="id")
     **/
    private $moneyGroup;

    /**
     * @ManyToOne(targetEntity="MoneyUnit", inversedBy="money", fetch="EAGER")
     * @JoinColumn(name="ingredient_money_id", referencedColumnName="id")
     **/
    private $moneyUnit;


    /**
     * @Column(name="more_info", type="string", nullable=true)
     */
    private $moreInfo;


    /**
     * @OneToMany(targetEntity="Transaction\Entity\MoneyMoving", mappedBy="item")
     **/
    private $moneyMoving;

    /**
     * @OneToMany(targetEntity="Transaction\Entity\MoneyStock", mappedBy="item")
     **/
    private $moneyStock;


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
    public function getMoneyGroup()
    {
        return $this->moneyGroup;
    }

    /**
     * @param mixed $moneyGroup
     */
    public function setMoneyGroup($moneyGroup)
    {
        if (is_object($moneyGroup)) {
            $this->moneyGroup = $moneyGroup;
        } else {
            $moneyGroupFromEm = $this->sm->get('Doctrine\ORM\EntityManager')->getRepository(MoneyGroup::class)->find($moneyGroup);
            $this->moneyGroup = $moneyGroupFromEm;
        }
    }


    /**
     * @return mixed
     */
    public function getMoneyUnit()
    {
        return $this->moneyUnit;
    }

    /**
     * @param mixed $moneyUnit
     */
    public function setMoneyUnit($moneyUnit)
    {
        if (is_object($moneyUnit)) {
            $this->moneyUnit = $moneyUnit;
        } else {
            $moneyUnitFromEm = $this->sm->get('Doctrine\ORM\EntityManager')->getRepository(MoneyUnit::class)->find($moneyUnit);
            $this->moneyUnit = $moneyUnitFromEm;
        }
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
    public function getMoneyMoving()
    {
        return $this->moneyMoving;
    }

    /**
     * @param mixed $moneyMoving
     */
    public function setMoneyMoving($moneyMoving)
    {
        $this->moneyMoving = $moneyMoving;
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
        $this->moneyStock = $moneyStock;
    }


    public function getArrayCopy() //Doctrinehoz kell
    {
        $arrayCopy = [];
        $arrayCopy['Money'] = get_object_vars($this);
        if (array_key_exists('sm', $arrayCopy['Money'])) {
            unset($arrayCopy['Money']['sm']);
        }
        return $arrayCopy;
    }

}