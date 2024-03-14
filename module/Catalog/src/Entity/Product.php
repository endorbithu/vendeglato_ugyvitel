<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.12.
 * Time: 22:57
 */


namespace Catalog\Entity;

use Application\Entity\InjectionOfEntity\ServiceMangerAwareEntity;
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
 * @Entity(repositoryClass="Catalog\Repository\ProductRepository")
 * @Table(name="cat_product")
 */
class Product extends ServiceMangerAwareEntity
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
     * @Column(name="price", type="decimal", precision=8, scale=2)
     */
    private $price;


    /**
     * @Column(name="is_negative_price", type="boolean", options={"default":0})
     */
    // private $isNegativePrice;


    /**
     * @ManyToOne(targetEntity="ProductGroup", inversedBy="product", fetch="EAGER")
     * @JoinColumn(name="product_group_id", referencedColumnName="id")
     **/
    private $productGroup;


    /**
     * @ManyToOne(targetEntity="VatGroup", inversedBy="product", fetch="EAGER")
     * @JoinColumn(name="vat_group_id", referencedColumnName="id")
     **/
    private $vatGroup;


    /**
     * @Column(name="can_half", type="boolean", options={"default":0})
     */
    //private $canHalf;


    /**
     * @Column(name="half_portion", type="decimal", precision=8, scale=2, options={"default":0.0})
     */
    //private $halfPortion;

    /**
     * @Column(name="is_active", type="boolean", options={"default":0})
     */
    private $isActive;

    /**
     * @Column(name="prescription", type="string", nullable=true)
     */
    private $prescription;


    /**
     * @Column(name="more_info", type="string", nullable=true)
     */
    private $moreInfo;


    /**
     * @OneToMany(targetEntity="IngredientInProduct", mappedBy="product")
     **/
    private $ingredientInProduct;


    /**
     * @OneToMany(targetEntity="Order\Entity\OrderItemInStorage", mappedBy="product")
     **/
    private $productStorage;

    /**
     * @OneToMany(targetEntity="Order\Entity\ProductMoving", mappedBy="product")
     **/
    private $productMoving;


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



    /**
     * @return mixed
     */
    public function getProductGroup()
    {
        return $this->productGroup;
    }

    /**
     * @param mixed $productGroup
     */
    public function setProductGroup($productGroup)
    {
        if (is_object($productGroup)) {
            $this->productGroup = $productGroup;
        } else {
            $productGroupFromEm = $this->sm->get('Doctrine\ORM\EntityManager')->getRepository(ProductGroup::class)->find($productGroup);
            $this->productGroup = $productGroupFromEm;
        }
    }


    /**
     * @return mixed
     */
    public function getVatGroup()
    {
        return $this->vatGroup;
    }

    /**
     * @param mixed $vatGroup
     */
    public function setVatGroup($vatGroup)
    {
        if (is_object($vatGroup)) {
            $this->vatGroup = $vatGroup;
        } else {
            $vatGroupFromEm = $this->sm->get('Doctrine\ORM\EntityManager')->getRepository(VatGroup::class)->find($vatGroup);
            $this->vatGroup = $vatGroupFromEm;
        }
    }

    /**
     * @return mixed
     */
    public function getCanHalf()
    {
        return $this->canHalf;
    }

    /**
     * @param mixed $canHalf
     */
    public function setCanHalf($canHalf)
    {
        $this->canHalf = $canHalf;
    }

    /**
     * @return mixed
     */
    public function getHalfPortion()
    {
        return $this->halfPortion;
    }

    /**
     * @param mixed $halfPortion
     */
    public function setHalfPortion($halfPortion)
    {
        $this->halfPortion = $halfPortion;
    }

    /**
     * @return mixed
     */
    public function getPrescription()
    {
        return $this->prescription;
    }

    /**
     * @param mixed $prescription
     */
    public function setPrescription($prescription)
    {
        $this->prescription = $prescription;
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
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @return mixed
     */
    public function getProductMoving()
    {
        return $this->productMoving;
    }

    /**
     * @param mixed $productMoving
     */
    public function setProductMoving($productMoving)
    {
        $this->productMoving = $productMoving;
    }




    public function getArrayCopy() //Doctrinehoz kell
    {
        $arrayCopy = [];
        $arrayCopy['Product'] = get_object_vars($this);
        if (array_key_exists('sm', $arrayCopy['Product'])) {
            unset($arrayCopy['Product']['sm']);
        }
        return $arrayCopy;
    }

}