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
 * @Table(name="cat_vat_group")
 */
class VatGroup
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
     * @Column(name="vat_value", type="decimal", precision=8, scale=2)
     */
    private $vatValue;



    /**
     * @OneToMany(targetEntity="Product", mappedBy="vatGroup")
     **/
    private $product;

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
    public function getVatValue()
    {
        return $this->vatValue;
    }

    /**
     * @param mixed $vatValue
     */
    public function setVatValue($vatValue)
    {
        $this->vatValue = $vatValue;
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





    public function getArrayCopy() //Doctrinehoz kell
    {
        $arrayCopy = [];
        $arrayCopy['VatGroup'] = get_object_vars($this);
        if (array_key_exists('sm', $arrayCopy['VatGroup'])) {
            unset($arrayCopy['VatGroup']['sm']);
        }
        return $arrayCopy;
    }



}