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
class NewOrderTransactionService implements IStockTransactionService
{
    protected $postData;
    protected $em;
    protected $productCollection;
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


    public function setProductCollectionModel($productCollectonModel)
    {
        $this->getTransactionEventModel()->setProductCollectionModel($productCollectonModel);
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
        $this->getEventManager()->trigger('triggerProductTransactionEvent', null, ['transactionEventModel' => $this->transactionEventModel]);
    }


    public function isValidPostData()
    {
        if (!array_key_exists('amount', $this->postData) || empty($this->postData['amount'])) return true;

        foreach ($this->postData['amount'] as $productId => $amount) {
            if ($this->postData['amount'][$productId] === '') {
                unset($this->postData['amount'][$productId]);
                continue;
            }

            if (!is_numeric($this->postData['amount'][$productId]) || strpos($this->postData['amount'][$productId], '-') !== false) {
                throw new \Exception('Nem megfelelőek az adatok! (pl.szám helyett betű stb)');
            }

            $this->postData['amount'][(int)$productId] = (int)$amount;
        }

        return true;
    }


    public function adjustTransactionEventModel(\DateTime $dateTime = null)
    {
        if ($this->isValidPostData() !== true) throw new \Exception('Nem érvényesek a megadott adatok, vagy még nem validálta a rendszer!');

        if (array_key_exists('amount', $this->postData)) {
            $this->transactionEventModel->getProductCollectionModel()->addProducts($this->postData['amount']);
        } else {
            $this->transactionEventModel->getProductCollectionModel()->addProducts([]);
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