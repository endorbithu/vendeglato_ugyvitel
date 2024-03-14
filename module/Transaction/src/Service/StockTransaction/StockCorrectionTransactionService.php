<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.14.
 * Time: 6:12
 */

namespace Transaction\Service\StockTransaction;


use Catalog\Entity\Storage;
use Transaction\Model\StockTransaction\IStockTransactionEvent;
use Transaction\Model\StockTransaction\ItemCollectionModel;
use Zend\EventManager\EventManagerInterface;

class StockCorrectionTransactionService implements IStockTransactionService
{

    private $postData;
    protected $itemTransactionServices = [];
    protected $transactionModels = [];
    protected $itemCollections = [];
    protected $em;
    protected $stockEntityName;
    protected $stuffEntityFullName;




    public function __construct($em)
    {
        $this->em = $em;

    }

    /**
     * @param mixed $stuffEntityFullName
     */
    public function setStuffEntityFullName($stuffEntityFullName)
    {
        $this->stuffEntityFullName = $stuffEntityFullName;
        $this->itemTransactionServices[0]->setStuffEntityFullName($stuffEntityFullName);
        $this->itemTransactionServices[1]->setStuffEntityFullName($stuffEntityFullName);
    }



    public function setItemCollectionModel(ItemCollectionModel $itemCollectionModel)
    {
        $itemCollectionModelClass = get_class($itemCollectionModel);

        $this->itemTransactionServices[0]->setItemCollectionModel(new $itemCollectionModelClass($this->em));
        $this->itemTransactionServices[1]->setItemCollectionModel(new $itemCollectionModelClass($this->em));

    }


    /**
     * @param IStockTransactionService $itemTransactions
     */
    public function setItemTransactionServices(IStockTransactionService $itemTransactions)
    {
        $itemTransactionServiceClass = get_class($itemTransactions);

        $this->itemTransactionServices[0] = new $itemTransactionServiceClass($this->em);
        $this->itemTransactionServices[1] = new $itemTransactionServiceClass($this->em);
    }


    public function setTransactionEventModel(IStockTransactionEvent $eventModel)
    {
        $eventModelClass = get_class($eventModel);

        $this->itemTransactionServices[0]->setTransactionEventModel(new $eventModelClass($this->em));
        $this->itemTransactionServices[1]->setTransactionEventModel(new $eventModelClass($this->em));
    }


    public function setPostData($postData)
    {
        $this->postData = $postData;

        $this->itemTransactionServices[0]->setPostData($this->differentNegativPositivePostData()[0]);
        $this->itemTransactionServices[1]->setPostData($this->differentNegativPositivePostData()[1]);
    }


    public function isValidPostData()
    {

        foreach ($this->postData['amount'] as $itemId => $amount) {
            if (!is_numeric($amount)) throw new \Exception('Egy vagy több tételhez nem lett mennyiség megadva!');
        }

        $this->itemTransactionServices[0]->isValidPostData();
        $this->itemTransactionServices[1]->isValidPostData();


    }

    public function validAmount()
    {
        $this->itemTransactionServices[0]->validAmount();
        $this->itemTransactionServices[1]->validAmount();
    }

    public function triggerStockTransactionEvent()
    {
        $this->itemTransactionServices[0]->triggerStockTransactionEvent();
        $this->itemTransactionServices[1]->triggerStockTransactionEvent();
    }


    public function adjustTransactionEventModel(\DateTime $dateTime = null)
    {
        $dateTime = new \DateTime();
        $this->itemTransactionServices[0]->adjustTransactionEventModel($dateTime);
        $this->itemTransactionServices[1]->adjustTransactionEventModel($dateTime);
    }


    //AZ INVENTORYHOZ TARTOZÓ FG-EK --------------------------------------------------

    protected function differentNegativPositivePostData()
    {
        $postdataNegative = $this->postData;
        $postdataPositive = $this->postData;
        $postdataNegative['amount'] = [];
        $postdataPositive['amount'] = [];

        $stockPositiveId = $this->em->getRepository(Storage::class)->getStockCorrectionStorage(lcfirst(explode('\\',$this->stuffEntityFullName)[2]),'positive')[0]->getId();
        $stockNegativeId = $this->em->getRepository(Storage::class)->getStockCorrectionStorage(lcfirst(explode('\\',$this->stuffEntityFullName)[2]),'negative')[0]->getId();

        foreach ($this->postData['amount'] as $itemId => $amounts) {

            if (empty($actualItemQuery = $this->em->getRepository($this->stockEntityName)->findBy(['storage' => $this->postData['StockTransaction']['fromStorage'], 'item' => $itemId]))) {
                throw new \Exception('Egy vagy több elem nem létezik, vagy nincs a készlethez kapcsolva!');
            }
            $actualItem = $actualItemQuery[0];

            if (!is_numeric($amounts)) continue;

            if ($actualItem->getAmount() < $amounts) {
                $postdataPositive['amount'][$itemId] = ($amounts - $actualItem->getAmount());
                $postdataPositive['StockTransaction']['fromStorage'] = $stockPositiveId;
            } elseif ($actualItem->getAmount() > $amounts) {
                $postdataNegative['amount'][$itemId] = ($actualItem->getAmount() - $amounts);
                $postdataNegative['StockTransaction']['toStorage'] = $stockNegativeId;
            }

        }

        return [$postdataNegative, $postdataPositive];
    }

    /**
     * Inject an EventManager instance
     *
     * @param  EventManagerInterface $eventManager
     * @return void
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $this->itemTransactionServices[0]->setEventManager($eventManager);
        $this->itemTransactionServices[1]->setEventManager($eventManager);
    }

    /**
     * Retrieve the event manage r
     *
     * Lazy-loads an EventManager instance if none registered.
     *
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        // TODO: #153 Implement getEventManager() method.
    }

    /**
     * @param mixed $stockEntityName
     */
    public function setStockEntityName($stockEntityName)
    {
        $this->stockEntityName = $stockEntityName;
    }

    public function setEventName($eventName)
    {
        $this->itemTransactionServices[0]->setEventName($eventName);
        $this->itemTransactionServices[1]->setEventName($eventName);
    }
}

