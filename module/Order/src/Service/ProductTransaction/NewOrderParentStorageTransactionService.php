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
class NewOrderParentStorageTransactionService implements IStockTransactionService
{
    protected $em;
    protected $eventManager;
    protected $postData;
    protected $morePostDatas;

    protected $parentAwareStorageProductService;

    protected $productTransactionService;
    protected $transactionEventModel;
    protected $productCollectionModel;

    protected $productTransactionServices = [];
    protected $productTransactionClass;
    protected $productCollectionModelClass;
    protected $transactionEventModelClass;


    public function __construct($em)
    {
        $this->em = $em;
    }


    public function setPostData($postData)
    {
        $this->morePostDatas = $this->getParentAwareStorageProductService()->differentProductStorage($postData);

        for ($i = 0; $i < count($this->morePostDatas); $i++) {
            $this->productTransactionServices[$i] = clone $this->productTransactionService;
            $this->productTransactionServices[$i]->__construct($this->em);
            $this->productTransactionServices[$i]->setTransactionEventModel(clone $this->transactionEventModel);
            $this->productTransactionServices[$i]->setProductCollectionModel(clone $this->productCollectionModel);
            $this->productTransactionServices[$i]->setPostData($this->morePostDatas[$i]);
        }
        $this->transactionEventModel = null;
        $this->productCollectionModel = null;
        $this->productTransactionService = null;


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


    public function setProductTransactionService($productTransactionService)
    {
        $this->productTransactionService = $productTransactionService;
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
            $this->productTransactionServices[$i]->triggerStockTransactionEvent();
        }
    }


    public function adjustTransactionEventModel(\DateTime $dateTime = null)
    {

        $dateTime = new \DateTime();
        for ($i = 0; $i < count($this->morePostDatas); $i++) {
            $this->productTransactionServices[$i]->adjustTransactionEventModel($dateTime);
        }
    }


    /**
     * @param  EventManagerInterface $eventManager
     * @return void
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        for ($i = 0; $i < count($this->morePostDatas); $i++) {
            $this->productTransactionServices[$i]->setEventManager($eventManager);
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
        // nincs értelme megvalósítani, hiszen ez továbbfejeszti az item transactiont , nem fogja más használni, csak egy eset
    }

}