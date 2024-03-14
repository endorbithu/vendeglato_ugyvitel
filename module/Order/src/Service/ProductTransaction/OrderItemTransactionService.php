<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.23.
 * Time: 8:16
 */

namespace Order\Service\ProductTransaction;


use Transaction\Model\StockTransaction\IStockTransactionEvent;
use Transaction\Service\StockTransaction\IStockTransactionService;
use Order\Model\ProductTransaction\ProductTransactionEventModel;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;

class OrderItemTransactionService implements IStockTransactionService
{


    protected $postData;
    protected $em;
    protected $orderItemCollection;
    protected $transactionEventModel;
    protected $eventManager;

    public function __construct($em)
    {
        $this->em = $em;
    }

    /**
     * @param mixed $transactionEventModel
     */
    public function setTransactionEventModel(IStockTransactionEvent $transactionEventModel)
    {
        $this->transactionEventModel = $transactionEventModel;
    }

    /**
     * @return ProductTransactionEventModel
     */
    public function getTransactionEventModel()
    {
        return $this->transactionEventModel;
    }


    public function setOrderItemCollectionModel($productCollectonModel)
    {
        $this->getTransactionEventModel()->setOrderItemCollectionModel($productCollectonModel);
    }

    /**
     * @param mixed $postData
     */
    public function setPostData($postData)
    {
        $this->postData = $postData;
    }


    public function triggerStockTransactionEvent()
    {

        $this->getEventManager()->trigger('triggerStockTransactionEvent', null, ['transactionEventModel' => $this->transactionEventModel]);
        $this->getEventManager()->trigger('triggerIngredientTransactionEvent', null, ['transactionEventModel' => $this->transactionEventModel]);
        $this->getEventManager()->trigger('triggerOrderItemTransactionEvent', null, ['transactionEventModel' => $this->transactionEventModel]);

        //IDE MENJEN AZ EVENTLOG!
    }


    public function isValidPostData()
    {
        if (!array_key_exists('orderItem', $this->postData) || empty($this->postData['orderItem'])) return true;
        foreach ($this->postData['orderItem'] as $orderItemId => $productAmount) {

            if (!(in_array($orderItemId, $this->postData['destination']))) {
                unset($this->postData['orderItem'][$orderItemId]);
                continue;
            }

            foreach ($this->postData['orderItem'][$orderItemId] as $productId => $amount) {
                if (!is_numeric($amount) || strpos($amount, '-') !== false) {
                    throw new \Exception('Nem megfelelőek az adatok! (pl.szám helyett betű stb)');
                }

                $this->postData['orderItem'][(int)$orderItemId][(int)$productId] = (int)$amount;
            }
        }

        return true;
    }


    public function adjustTransactionEventModel(\DateTime $dateTime = null)
    {
        if ($this->isValidPostData() !== true) throw new \Exception('Nem érvényesek a megadott adatok, vagy még nem validálta a rendszer!');

        if (array_key_exists('orderItem', $this->postData)) {
            $this->transactionEventModel->getOrderItemCollectionModel()->addOrderItems($this->postData['orderItem']);
        } else {
            $this->transactionEventModel->getOrderItemCollectionModel()->addOrderItems([]);
        }


        if ($dateTime === null) {
            $this->transactionEventModel->setDateTime('');
        } else {
            $this->transactionEventModel->setDateTime($dateTime);
        }
        $this->transactionEventModel->setFromStorage($this->postData['StockTransaction']['fromStorage']);
        $this->transactionEventModel->setToStorage($this->postData['StockTransaction']['toStorage']);
        $this->transactionEventModel->setTransactionType($this->postData['StockTransaction']['stockTransactionType']);
        $this->transactionEventModel->setUser($this->postData['StockTransaction']['user']);
        $this->transactionEventModel->setMoreInfo($this->postData['StockTransaction']['moreInfo']);

    }


    /**
     * @param  EventManagerInterface $eventManager
     * @return void
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $eventManager->addIdentifiers(array(
            get_called_class()
        ));
        $this->eventManager = $eventManager;
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