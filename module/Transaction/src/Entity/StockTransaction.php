<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.12.
 * Time: 22:57
 */


namespace Transaction\Entity;

use Application\Entity\InjectionOfEntity\ServiceMangerAwareEntity;
use Catalog\Entity\Storage;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use User\Entity\User;


/**
 * @Entity(repositoryClass="Transaction\Repository\StockTransactionRepository")
 * @Table(name="tra_stock_transaction")
 */
class StockTransaction extends ServiceMangerAwareEntity
{

    /**
     * @Id
     * @GeneratedValue(strategy="AUTO")
     * @Column(type="integer")
     */
    private $id;


    /**
     * @Column(name="date_time", type="datetime")
     */
    private $dateTime;


    /**
     * @ManyToOne(targetEntity="StockTransactionType", inversedBy="stockTransaction", fetch="EAGER")
     * @JoinColumn(name="stock_transaction_type_id", referencedColumnName="id")
     **/
    private $transactionType;


    /**
     * @ManyToOne(targetEntity="Catalog\Entity\Storage", inversedBy="stockTransactionFrom", fetch="EAGER")
     * @JoinColumn(name="from_storage_id", referencedColumnName="id")
     **/
    private $fromStorage;


    /**
     * @ManyToOne(targetEntity="Catalog\Entity\Storage", inversedBy="stockTransactionTo", fetch="EAGER")
     * @JoinColumn(name="to_storage_id", referencedColumnName="id")
     **/
    private $toStorage;


    /**
     * @Column(name="more_info", type="text")
     */
    private $moreInfo;

    /**
     * @ManyToOne(targetEntity="User\Entity\User", inversedBy="stockTransaction", fetch="EAGER")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     **/
    private $user;


    /**
     * @OneToMany(targetEntity="IngredientMoving", mappedBy="stockTransaction")
     **/
    private $ingredientMoving;


    /**
     * @OneToMany(targetEntity="Order\Entity\ProductMoving", mappedBy="stockTransaction")
     **/
    private $productMoving;

    /**
     * @OneToMany(targetEntity="ToolMoving", mappedBy="stockTransaction")
     **/
    private $toolMoving;



    /**
     * @OneToMany(targetEntity="Order\Entity\OrderItemInStorage", mappedBy="stockTransaction")
     **/
    private $orderItemInStorage;

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

    /**
     * @return mixed
     */
    public function getOrderItemInStorage()
    {
        return $this->orderItemInStorage;
    }

    /**
     * @param mixed $orderItemInStorage
     */
    public function setOrderItemInStorage($orderItemInStorage)
    {
        $this->orderItemInStorage = $orderItemInStorage;
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
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * @param mixed $dateTime
     */
    public function setDateTime($dateTime)
    {
        if (empty($dateTime)) {
            $this->dateTime = new \DateTime();
        } elseif (is_string($dateTime)) {
            $this->dateTime = new \DateTime($dateTime);
        } else {
            $this->dateTime = $dateTime;
        }
    }

    /**
     * @return mixed
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }

    /**
     * @param mixed $transactionType
     */
    public function setTransactionType($transactionType)
    {
        if (is_object($transactionType)) {
            $this->transactionType = $transactionType;
        } elseif (is_numeric($transactionType)) {
            $stockTransactionTypeFromEm = $this->sm->get('Doctrine\ORM\EntityManager')->getRepository(StockTransactionType::class)->find($transactionType);
            $this->transactionType = $stockTransactionTypeFromEm;
        } else {
            $stockTransactionTypeFromEm = $this->sm->get('Doctrine\ORM\EntityManager')->getRepository(StockTransactionType::class)->findBy(['stringId' => $transactionType])[0];
            $this->transactionType = $stockTransactionTypeFromEm;
        }
    }


    /**
     * @return mixed
     */
    public function getFromStorage()
    {
        return $this->fromStorage;
    }

    /**
     * @param mixed $fromStorage
     */
    public function setFromStorage($fromStorage)
    {
        if (is_object($fromStorage)) {
            $this->fromStorage = $fromStorage;
        } else {
            $fromStorageFromEm = $this->sm->get('Doctrine\ORM\EntityManager')->getRepository(Storage::class)->find($fromStorage);
            $this->fromStorage = $fromStorageFromEm;
        }
    }

    /**
     * @return mixed
     */
    public function getToStorage()
    {
        return $this->toStorage;
    }

    /**
     * @param mixed $toStorage
     */
    public function setToStorage($toStorage)
    {
        if (is_object($toStorage)) {
            $this->toStorage = $toStorage;
        } else {
            $toStorageFromEm = $this->sm->get('Doctrine\ORM\EntityManager')->getRepository(Storage::class)->find($toStorage);
            $this->toStorage = $toStorageFromEm;
        }
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
    public function getIngredientMoving()
    {
        return $this->ingredientMoving;
    }

    /**
     * @param mixed $ingredientMoving
     */
    public function setIngredientMoving($ingredientMoving)
    {
        $this->ingredientMoving = $ingredientMoving;
    }

    /**
     * @return mixed
     */
    public function getToolMoving()
    {
        return $this->toolMoving;
    }

    /**
     * @param mixed $toolMoving
     */
    public function setToolMoving($toolMoving)
    {
        $this->toolMoving = $toolMoving;
    }



    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        if (is_object($user)) {
            $this->user = $user;
        } else {
            $userFromEm = $this->sm->get('Doctrine\ORM\EntityManager')->getRepository(User::class)->find($user);
            $this->user = $userFromEm;
        }
    }


    public function getArrayCopy() //Doctrinehoz kell
    {
        $arrayCopy = [];
        $arrayCopy['StockTransaction'] = get_object_vars($this);
        if (array_key_exists('sm', $arrayCopy['StockTransaction'])) {
            unset($arrayCopy['StockTransaction']['sm']);
        }
        return $arrayCopy;
    }
}