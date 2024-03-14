<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.13.
 * Time: 19:32
 */

namespace Transaction\Service\StockTransaction;

use Application\Filter\NumericToFloat;
use Transaction\Model\StockTransaction\ItemCollectionModel;
use Transaction\Model\StockTransaction\ItemTransactionEventModel;
use Transaction\Model\StockTransaction\IStockTransactionEvent;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;
use Zend\Filter\FilterChain;
use Zend\Filter\StripTags;


/**
 * Class ItemTransactionService
 * @package Transaction\Service\StockTransaction
 * @property ItemTransactionEventModel $transactionEventModel
 */
class ItemTransactionService implements IStockTransactionService
{

    protected $postData;
    protected $em;
    protected $itemCollection;
    protected $transactionEventModel;
    protected $eventManager;
    protected $eventName;
    protected $stuffEntityFullName;


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
     * @return ItemTransactionEventModel
     */
    public function getTransactionEventModel()
    {
        return $this->transactionEventModel;
    }


    public function setItemCollectionModel(ItemCollectionModel $itemCollectionModel)
    {
        $this->getTransactionEventModel()->setItemCollectionModel($itemCollectionModel);
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
        $this->getEventManager()->trigger($this->eventName, null, ['transactionEventModel' => $this->transactionEventModel]);
    }


    public function isValidPostData()
    {
        if (!array_key_exists('amount', $this->postData) || empty($this->postData['amount'])) return true;


        $filtersForFloat = new FilterChain();
        $filtersForFloat
            ->attach(new StripTags())
            ->attach(new NumericToFloat());

        foreach ($this->postData['amount'] as $itemId => $amount) {
            if ($this->postData['amount'][$itemId] === '') {
                unset($this->postData['amount'][$itemId]);
                continue;
            }
            if (!is_numeric($this->postData['amount'][$itemId]) || strpos($this->postData['amount'][$itemId], '-') !== false) {
                throw new \Exception('Nem megfelelőek az adatok! (pl.szám helyett betű stb)');
            }

            $amount = $filtersForFloat->filter($amount);
            $amount = $this->roundAmountIfItemUnitIsInt($itemId,$amount);
            $this->postData['amount'][(int)$itemId] = $amount;
        }
        return true;
    }


    public function adjustTransactionEventModel(\DateTime $dateTime = null)
    {
        if ($this->isValidPostData() !== true) throw new \Exception('Az adatokat nem validálta a rendszer!');


        if (array_key_exists('amount', $this->postData)) {
            $this->transactionEventModel->getItemCollectionModel()->addItems($this->postData['amount']);
        } else {
            $this->transactionEventModel->getItemCollectionModel()->addItems([]);
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

    /**
     * @param mixed $eventName
     */
    public function setEventName($eventName)
    {
        $this->eventName = $eventName;
    }

    /**
     * @param mixed $stuffStringId
     */
    public function setStuffEntityFullName($stuffStringId)
    {
        $this->stuffEntityFullName = $stuffStringId;
    }


    //a stuffStringId-ből kitalálom hogy melyik típus ingredient tool vagy money és abból jöhet a többi cucc
    // és ha az unit egész számot kíván akkor kerekítés
    protected function roundAmountIfItemUnitIsInt($itemId, $amount)
    {
        $unitFunction = 'get' . explode('\\',$this->stuffEntityFullName)[2] . 'Unit';

        if (empty($this->em->getRepository($this->stuffEntityFullName)->find($itemId)->$unitFunction()->getIsDecimal())) {
            return round($amount);
        }

        return $amount;
    }

}