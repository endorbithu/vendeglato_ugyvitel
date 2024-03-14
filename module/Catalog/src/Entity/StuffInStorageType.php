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
 * @Table(name="cat_stuff_in_storage_type")
 */
class StuffInStorageType extends ServiceMangerAwareEntity
{

    /**
     * @Id
     * @GeneratedValue(strategy="AUTO")
     * @Column(type="integer")
     */
    private $id;



    /**
     * @ManyToOne(targetEntity="StorageType", inversedBy="stuffInStorageType", fetch="EAGER")
     * @JoinColumn(name="storage_type_id", referencedColumnName="id", onDelete="cascade")
     **/
    private $storageType;

    /**
     * @ManyToOne(targetEntity="Stuff", inversedBy="stuffInStorageType", fetch="EAGER")
     * @JoinColumn(name="stuff_id", referencedColumnName="id")
     **/
    private $stuff;




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
    public function getStorageType()
    {
        return $this->storageType;
    }

    /**
     * @param mixed $storageType
     */
    public function setStorageType($storageType)
    {
        if (is_object($storageType)) {
            $this->storageType = $storageType;
        } else {
            $storageFromEm = $this->sm->get('Doctrine\ORM\EntityManager')->getRepository(StorageType::class)->find($storageType);
            $this->storageType = $storageFromEm;
        }
    }

    /**
     * @return mixed
     */
    public function getStuff()
    {
        return $this->stuff;
    }

    /**
     * @param mixed $stuff
     */
    public function setStuff($stuff)
    {
        if (is_object($stuff)) {
            $this->stuff = $stuff;
        } else {
            $stuffFromEm = $this->sm->get('Doctrine\ORM\EntityManager')->getRepository(Stuff::class)->find($stuff);
            $this->stuff = $stuffFromEm;
        }
    }



    public function getArrayCopy() //Doctrinehoz kell
    {
        $arrayCopy = [];
        $arrayCopy['StuffInStorageType'] = get_object_vars($this);
        if (array_key_exists('sm', $arrayCopy['StuffInStorageType'])) {
            unset($arrayCopy['StuffInStorageType']['sm']);
        }
        return $arrayCopy;
    }





}