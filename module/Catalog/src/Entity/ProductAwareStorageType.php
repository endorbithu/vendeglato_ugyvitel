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
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\JoinColumn;


/**
 * @Entity
 * @Table(name="cat_product_aware_storage_type")
 */
class ProductAwareStorageType
{
//TODO: #146 Megoldani, hogy az idegen kulcs a stringId legyen??
    /**
     * @Id
     * @GeneratedValue(strategy="AUTO")
     * @Column(type="integer")
     */
    private $id;


    /**
     * @OneToOne(targetEntity="StorageType", inversedBy="productAwareStorageType", fetch="EAGER")
     * @JoinColumn( name="storage_type_id", referencedColumnName="id")
     **/
    private $storageType;

    /**
     * @return mixed
     */
    public function getStorageType()
    {
        return $this->storageType;
    }

    /**
     * @param mixed $storageType
     */
    public function setStorageType($storageType)
    {
        $this->storageType = $storageType;
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


    public function getArrayCopy() //Doctrinehoz kell
    {
        $arrayCopy = [];
        $arrayCopy['ProductAwareStorageType'] = get_object_vars($this);
        if (array_key_exists('sm', $arrayCopy['ProductAwareStorageType'])) {
            unset($arrayCopy['ProductAwareStorageType']['sm']);
        }
        return $arrayCopy;
    }


}