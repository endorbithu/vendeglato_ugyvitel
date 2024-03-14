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
 * //Entity(repositoryClass="Catalog\Repository\ToolRepository")
 * @Entity
 * @Table(name="cat_tool")
 */
class Tool extends ServiceMangerAwareEntity
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
     * @ManyToOne(targetEntity="ToolGroup", inversedBy="tool", fetch="EAGER")
     * @JoinColumn(name="tool_group_id", referencedColumnName="id")
     **/
    private $toolGroup;

    /**
     * @ManyToOne(targetEntity="ToolUnit", inversedBy="tool", fetch="EAGER")
     * @JoinColumn(name="ingredient_tool_id", referencedColumnName="id")
     **/
    private $toolUnit;


    /**
     * @Column(name="more_info", type="string", nullable=true)
     */
    private $moreInfo;


    /**
     * @OneToMany(targetEntity="Transaction\Entity\ToolMoving", mappedBy="item")
     **/
    private $toolMoving;

    /**
     * @OneToMany(targetEntity="Transaction\Entity\ToolStock", mappedBy="item")
     **/
    private $toolStock;


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
    public function getToolGroup()
    {
        return $this->toolGroup;
    }

    /**
     * @param mixed $toolGroup
     */
    public function setToolGroup($toolGroup)
    {
        if (is_object($toolGroup)) {
            $this->toolGroup = $toolGroup;
        } else {
            $toolGroupFromEm = $this->sm->get('Doctrine\ORM\EntityManager')->getRepository(ToolGroup::class)->find($toolGroup);
            $this->toolGroup = $toolGroupFromEm;
        }
    }


    /**
     * @return mixed
     */
    public function getToolUnit()
    {
        return $this->toolUnit;
    }

    /**
     * @param mixed $toolUnit
     */
    public function setToolUnit($toolUnit)
    {
        if (is_object($toolUnit)) {
            $this->toolUnit = $toolUnit;
        } else {
            $toolUnitFromEm = $this->sm->get('Doctrine\ORM\EntityManager')->getRepository(ToolUnit::class)->find($toolUnit);
            $this->toolUnit = $toolUnitFromEm;
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
    public function getToolMoving()
    {
        return $this->toolMoving;
    }

    /**
     * @param mixed $toolMoving
     */
    public function setToolMoving($toolMoving)
    {
        $this->toolMoving = $toolMoving;
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
        $this->toolStock = $toolStock;
    }


    public function getArrayCopy() //Doctrinehoz kell
    {
        $arrayCopy = [];
        $arrayCopy['Tool'] = get_object_vars($this);
        if (array_key_exists('sm', $arrayCopy['Tool'])) {
            unset($arrayCopy['Tool']['sm']);
        }
        return $arrayCopy;
    }

}