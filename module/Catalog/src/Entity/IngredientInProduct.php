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
 * @Entity
 * @Table(name="cat_ingredient_in_product")
 */
class IngredientInProduct extends ServiceMangerAwareEntity
{

    /**
     * @Id
     * @GeneratedValue(strategy="AUTO")
     * @Column(type="integer")
     */
    private $id;



    /**
     * @ManyToOne(targetEntity="Product", inversedBy="ingredientInProduct", fetch="EAGER")
     * @JoinColumn(name="product_id", referencedColumnName="id", onDelete="cascade")
     **/
    private $product;

    /**
     * @ManyToOne(targetEntity="Ingredient", inversedBy="ingredientInProduct", fetch="EAGER")
     * @JoinColumn(name="ingredient_id", referencedColumnName="id")
     **/
    private $ingredient;


    /**
     * @Column(name="amount", type="decimal", precision=8, scale=2, options={"default":1.0})
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
    public function getIngredient()
    {
        return $this->ingredient;
    }

    /**
     * @param mixed $ingredient
     */
    public function setIngredient($ingredient)
    {
        if (is_object($ingredient)) {
            $this->ingredient = $ingredient;
        } else {
            $ingredientFromEm = $this->sm->get('Doctrine\ORM\EntityManager')->getRepository(Ingredient::class)->find($ingredient);
            $this->ingredient = $ingredientFromEm;
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
        $arrayCopy['IngredientInProduct'] = get_object_vars($this);
        if (array_key_exists('sm', $arrayCopy['IngredientInProduct'])) {
            unset($arrayCopy['IngredientInProduct']['sm']);
        }
        return $arrayCopy;
    }





}