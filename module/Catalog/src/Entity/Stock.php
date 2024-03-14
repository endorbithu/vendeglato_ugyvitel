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





/**
 * @Entity(repositoryClass="Catalog\Repository\StockRepository")
 * @Table(name="cat_stock")
 */
class Stock extends ServiceMangerAwareEntity
{

    /**
     * @Id
     * @GeneratedValue(strategy="AUTO")
     * @Column(type="integer")
     */
    private $id;

    

    /**
     * @ManyToOne(targetEntity="Storage", inversedBy="stock", fetch="EAGER")
     * @JoinColumn( name="storage", referencedColumnName="id")
     **/
    private $storage;



    /**
     * @ManyToOne(targetEntity="Ingredient", inversedBy="stock", fetch="EAGER")
     * @JoinColumn(name="ingredient", referencedColumnName="id")
     **/
    private $ingredient;



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
        $arrayCopy['Stock'] = get_object_vars($this);
        if (array_key_exists('sm', $arrayCopy['Stock'])) {
            unset($arrayCopy['Stock']['sm']);
        }
        return $arrayCopy;
    }





}