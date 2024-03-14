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
 * @Table(name="cat_supplier")
 */
class Supplier
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
     * @Column(name="tax_number", type="string")
     */
    private $taxNumber;


    /**
     * @Column(name="contact_person", type="string")
     */
    private $contactPerson;


    /**
     * @Column(name="tel_number", type="string")
     */
    private $telNumber;


    /**
     * @Column(name="email", type="string")
     */
    private $email;


    /**
     * @Column(name="seat", type="string")
     */
    private $seat;


    /**
     * @Column(name="site", type="string", nullable=true)
     */
    private $site;


    /**
     * @Column(name="more_info", type="string", nullable=true)
     */
    private $moreInfo;


    /**
     * @OneToMany(targetEntity="Storage", mappedBy="supplier")
     **/
    private $storage;

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
    public function getTaxNumber()
    {
        return $this->taxNumber;
    }

    /**
     * @param mixed $taxNumber
     */
    public function setTaxNumber($taxNumber)
    {
        $this->taxNumber = $taxNumber;
    }

    /**
     * @return mixed
     */
    public function getContactPerson()
    {
        return $this->contactPerson;
    }

    /**
     * @param mixed $contactPerson
     */
    public function setContactPerson($contactPerson)
    {
        $this->contactPerson = $contactPerson;
    }

    /**
     * @return mixed
     */
    public function getTelNumber()
    {
        return $this->telNumber;
    }

    /**
     * @param mixed $telNumber
     */
    public function setTelNumber($telNumber)
    {
        $this->telNumber = $telNumber;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getSeat()
    {
        return $this->seat;
    }

    /**
     * @param mixed $seat
     */
    public function setSeat($seat)
    {
        $this->seat = $seat;
    }

    /**
     * @return mixed
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param mixed $site
     */
    public function setSite($site)
    {
        $this->site = $site;
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
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * @param mixed $storage
     */
    public function setStorage($storage)
    {
        $this->storage = $storage;
    }


    public function getArrayCopy() //Doctrinehoz kell
    {
        $arrayCopy = [];
        $arrayCopy['Supplier'] = get_object_vars($this);
        if (array_key_exists('sm', $arrayCopy['Supplier'])) {
            unset($arrayCopy['Supplier']['sm']);
        }
        return $arrayCopy;
    }

}