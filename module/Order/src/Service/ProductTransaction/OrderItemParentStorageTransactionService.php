<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.20.
 * Time: 21:18
 */

namespace Order\Service\ProductTransaction;

use Transaction\Model\StockTransaction\IStockTransactionEvent;
use Transaction\Service\StockTransaction\IStockTransactionService;
use Order\Model\ProductTransaction\ProductTransactionEventModel;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;

/**
 * Class NewOrderTransactionService
 * @package Order\Service\ProductTransaction
 * @property  ProductTransactionEventModel $transactionEventModel
 */
class OrderItemParentStorageTransactionService implements IStockTransactionService
{
    protected $em;
    protected $eventManager;
    protected $postData;
    protected $morePostDatas;

    protected $parentAwareStorageProductService;

    protected $orderItemTransactionService;
    protected $transactionEventModel;
    protected $productCollectionModel;
    protected $orderItemCollectionModel;

    protected $orderItemTransactionServices = [];
    protected $productTransactionClass;
    protected $productCollectionModelClass;
    protected $transactionEventModelClass;


    public function __construct($em)
    {
        $this->em = $em;
    }

    /**
     * @return mixed
     */
    public function getParentAwareStorageProductService()
    {
        return $this->parentAwareStorageProductService;
    }

    /**
     * @param mixed $parentAwareStorageProductService
     */
    public function setParentAwareStorageProductService($parentAwareStorageProductService)
    {
        $this->parentAwareStorageProductService = $parentAwareStorageProductService;
    }

    /**
     * @return mixed
     */
    public function getOrderItemCollectionModel()
    {
        return $this->orderItemCollectionModel;
    }

    /**
     * @param mixed $orderItemCollectionModel
     */
    public function setOrderItemCollectionModel($orderItemCollectionModel)
    {
        $this->orderItemCollectionModel = $orderItemCollectionModel;
    }


    public function setPostData($postData)
    {
        $this->morePostDatas = $this->getParentAwareStorageProductService()->differentoOrderItemStorage($postData);

        for ($i = 0; $i < count($this->morePostDatas); $i++) {
            $this->orderItemTransactionServices[$i] = clone $this->orderItemTransactionService;
            $this->orderItemTransactionServices[$i]->__construct($this->em);
            $this->orderItemTransactionServices[$i]->setTransactionEventModel(clone $this->transactionEventModel);
            $this->orderItemTransactionServices[$i]->setOrderItemCollectionModel(clone $this->orderItemCollectionModel);
            $this->orderItemTransactionServices[$i]->setPostData($this->morePostDatas[$i]);
        }

        $this->transactionEventModel = null;
        $this->productCollectionModel = null;
        $this->orderItemTransactionService = null;



    }



    /**
     * @param mixed $orderItemTransactionService
     */
    public function setOrderItemTransactionService($orderItemTransactionService)
    {
        $this->orderItemTransactionService = $orderItemTransactionService;
    }




    public function setProductCollectionModel($productCollectionModel)
    {
        $this->productCollectionModel = $productCollectionModel;

    }


    public function setTransactionEventModel(IStockTransactionEvent $transactionEventModel)
    {
        $this->transactionEventModel = $transactionEventModel;
    }


    public function triggerStockTransactionEvent()
    {
        for ($i = 0; $i < count($this->morePostDatas); $i++) {
            $this->orderItemTransactionServices[$i]->triggerStockTransactionEvent();
        }
    }


    public function adjustTransactionEventModel(\DateTime $dateTime = null)
    {

        $dateTime = new \DateTime();
        for ($i = 0; $i < count($this->morePostDatas); $i++) {
            $this->orderItemTransactionServices[$i]->adjustTransactionEventModel($dateTime);
        }
    }


    /**
     * @param  EventManagerInterface $eventManager
     * @return void
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        for ($i = 0; $i < count($this->morePostDatas); $i++) {
            $this->orderItemTransactionServices[$i]->setEventManager($eventManager);
        }
    }




    /**
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (null === $this->eventManager) {
            $this->setEventManager(new EventManager());
        }

        return $this->eventManager;
    }

    public function setEventName($eventName)
    {
        // nem kell megvalósítani, mert ez nincsen több alakban csak egyszer fordul el, nem mint az itemtransaction ahol
        //lehet ingredient is meg tool is
    }


}