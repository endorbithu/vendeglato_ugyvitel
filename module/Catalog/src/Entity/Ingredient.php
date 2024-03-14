<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.12.
 * Time: 22:57
 */


namespace Catalog\Entity;

use Application\Entity\InjectionOfEntity\ServiceMangerAwareEntity;
use Catalog\Entity\IngredientGroup;
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
 * @Entity(repositoryClass="Catalog\Repository\IngredientRepository")
 * @Table(name="cat_ingredient")
 */
class Ingredient extends ServiceMangerAwareEntity
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
     * @ManyToOne(targetEntity="IngredientGroup", inversedBy="ingredient", fetch="EAGER")
     * @JoinColumn(name="ingredient_group_id", referencedColumnName="id")
     **/
    private $ingredientGroup;


    /**
     * @ManyToOne(targetEntity="IngredientUnit", inversedBy="ingredient", fetch="EAGER")
     * @JoinColumn(name="ingredient_unit_id", referencedColumnName="id")
     **/
    private $ingredientUnit;
    /**
     * @Column(name="minimum_amount", type="decimal", precision=8, scale=2)
     */
    private $minimumAmount;


    /**
     * @Column(name="more_info", type="string", nullable=true)
     */
    private $moreInfo;


    //TODO: #145 berakni az alapértelmezett storage-et
    /**
     * Megjelölhetjük  az alapértelmezett beszállítót
     * @ManyToOne(targetEntity="Storage", inversedBy="ingredient", fetch="EAGER")
     * @JoinColumn(name="storage_id", referencedColumnName="id")
     **/
    //private $storage;


    /**
     * @OneToMany(targetEntity="IngredientInProduct", mappedBy="ingredient")
     **/
    private $ingredientInProduct;



    /**
     * @OneToMany(targetEntity="Transaction\Entity\IngredientMoving", mappedBy="item")
     **/
    private $ingredientMoving;

    /**
     * @OneToMany(targetEntity="Transaction\Entity\Stock", mappedBy="ingredient")
     **/
    private $stock;

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
    public function getIngredientGroup()
    {
        return $this->ingredientGroup;
    }

    /**
     * @param mixed $ingredientGroup
     */
    public function setIngredientGroup($ingredientGroup)
    {
        if (is_object($ingredientGroup)) {
            $this->ingredientGroup = $ingredientGroup;
        } else {
            $ingredientGroupFromEm = $this->sm->get('Doctrine\ORM\EntityManager')->getRepository(IngredientGroup::class)->find($ingredientGroup);
            $this->ingredientGroup = $ingredientGroupFromEm;
        }
    }

    /**
     * @return mixed
     */
    public function getIngredientUnit()
    {
        return $this->ingredientUnit;
    }

    /**
     * @param mixed $ingredientUnit
     */
    public function setIngredientUnit($ingredientUnit)
    {
        if (is_object($ingredientUnit)) {
            $this->ingredientUnit = $ingredientUnit;
        } else {
            $ingredientUnitFromEm = $this->sm->get('Doctrine\ORM\EntityManager')->getRepository(IngredientUnit::class)->find($ingredientUnit);
            $this->ingredientUnit = $ingredientUnitFromEm;
        }
    }

    /**
     * @return mixed
     */
    public function getMinimumAmount()
    {
        return $this->minimumAmount;
    }

    /**
     * @param mixed $minimumAmount
     */
    public function setMinimumAmount($minimumAmount)
    {
        $this->minimumAmount = $minimumAmount;
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
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

    /**
     * @return mixed
     */
    public function getIngredientMoving()
    {
        return $this->ingredientMoving;
    }

    /**
     * @param mixed $ingredientMoving
     */
    public function setIngredientMoving($ingredientMoving)
    {
        $this->ingredientMoving = $ingredientMoving;
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
    }


    /**
     * @return mixed
     */
    public function getIngredientInProduct()
    {
        return $this->ingredientInProduct;
    }

    /**
     * @param mixed $ingredientInProduct
     */
    public function setIngredientInProduct($ingredientInProduct)
    {
        $this->ingredientInProduct = $ingredientInProduct;
    }







    public function getArrayCopy() //Doctrinehoz kell
    {
        $arrayCopy = [];
        $arrayCopy['Ingredient'] = get_object_vars($this);
        if (array_key_exists('sm', $arrayCopy['Ingredient'])) {
            unset($arrayCopy['Ingredient']['sm']);
        }
        return $arrayCopy;
    }

}