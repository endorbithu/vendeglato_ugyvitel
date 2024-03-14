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
 * @Table(name="cat_ingredient_unit")
 */
class IngredientUnit
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
     * @OneToMany(targetEntity="Ingredient", mappedBy="ingredientUnit")
     **/
    private $ingredient;

    /**
     * @Column(name="is_decimal", type="boolean", options={"default":0})
     */
    private $isDecimal;




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
    public function getIngredient()
    {
        return $this->ingredient;
    }

    /**
     * @param mixed $ingredient
     */
    public function setIngredient($ingredient)
    {
        $this->ingredient = $ingredient;
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
        $arrayCopy['IngredientUnit'] = get_object_vars($this);
        if (array_key_exists('sm', $arrayCopy['IngredientUnit'])) {
            unset($arrayCopy['IngredientUnit']['sm']);
        }
        return $arrayCopy;
    }

}