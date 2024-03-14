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
use Doctrine\ORM\Mapping\OneToOne;


/**
 * @Entity(repositoryClass="Catalog\Repository\StorageTypeRepository")
 * @Table(name="cat_storage_type")
 */
class StorageType
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
     * @Column(name="is_real_storage_type", type="boolean", options={"default":0})
     */
    private $isRealStorageType;


    /**
     * @OneToMany(targetEntity="Storage", mappedBy="storageType")
     **/
    private $storage;



    /**
     * @OneToMany(targetEntity="StuffInStorageType", mappedBy="storageType")
     **/
    private $stuffInStorageType;


    /**
     * @OneToOne(targetEntity="ProductAwareStorageType", mappedBy="storageType")
     **/
    private $productAwareStorageType;



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



    /**
     * @return mixed
     */
    public function getProductAwareStorageType()
    {
        return $this->productAwareStorageType;
    }

    /**
     * @param mixed $productAwareStorageType
     */
    public function setProductAwareStorageType($productAwareStorageType)
    {
        $this->productAwareStorageType = $productAwareStorageType;
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

    /**
     * @return mixed
     */
    public function getIsRealStorageType()
    {
        return $this->isRealStorageType;
    }

    /**
     * @param mixed $isRealStorageType
     */
    public function setIsRealStorageType($isRealStorageType)
    {
        $this->isRealStorageType = $isRealStorageType;
    }




    public function getArrayCopy() //Doctrinehoz kell
    {
        $arrayCopy = [];
        $arrayCopy['StorageType'] = get_object_vars($this);
        if (array_key_exists('sm', $arrayCopy['StorageType'])) {
            unset($arrayCopy['StorageType']['sm']);
        }
        return $arrayCopy;
    }


}