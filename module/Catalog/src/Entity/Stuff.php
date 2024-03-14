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
 * @Table(name="cat_stuff")
 */
class Stuff
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
     * @Column(name="string_id", unique=true, type="string")
     */
    private $stringId;

    /**
     * @OneToMany(targetEntity="StuffInStorageType", mappedBy="stuff")
     **/
    private $stuffInStorageType;


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
    public function getStuffInStorageType()
    {
        return $this->stuffInStorageType;
    }

    /**
     * @param mixed $stuffInStorageType
     */
    public function setStuffInStorageType($stuffInStorageType)
    {
        $this->stuffInStorageType = $stuffInStorageType;
    }

    /**
     * @return mixed
     */
    public function getStringId()
    {
        return $this->stringId;
    }

    /**
     * @param mixed $stringId
     */
    public function setStringId($stringId)
    {
        $this->stringId = $stringId;
    }


    public function getArrayCopy() //Doctrinehoz kell
    {
        $arrayCopy = [];
        $arrayCopy['Stuff'] = get_object_vars($this);
        if (array_key_exists('sm', $arrayCopy['Stuff'])) {
            unset($arrayCopy['Stuff']['sm']);
        }
        return $arrayCopy;
    }


}