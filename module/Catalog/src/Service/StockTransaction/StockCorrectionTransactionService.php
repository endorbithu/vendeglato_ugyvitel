<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.14.
 * Time: 6:12
 */

namespace Catalog\Service\StockTransaction;


use Catalog\Entity\Stock;
use Catalog\Entity\Storage;
use Catalog\Model\StockTransaction\IngredientCollectionModel;
use Catalog\Model\StockTransaction\IStockTransactionEvent;
use Zend\EventManager\EventManagerInterface;

class StockCorrectionTransactionService implements IStockTransactionService
{

    private $postData;
    protected $ingrTransactionServices = [];
    protected $transactionModels = [];
    protected $ingredientCollections = [];
    protected $em;


    public function __construct($em)
    {
        $this->em = $em;

    }


    public function setIngredientCollectionModel(IngredientCollectionModel $ingredientCollectionModel)
    {
        $ingredientCollectionModelClass = get_class($ingredientCollectionModel);

        $this->ingrTransactionServices[0]->setIngredientCollectionModel(new $ingredientCollectionModelClass($this->em));
        $this->ingrTransactionServices[1]->setIngredientCollectionModel(new $ingredientCollectionModelClass($this->em));
        $this->ingrTransactionServices[2]->setIngredientCollectionModel(new $ingredientCollectionModelClass($this->em));

    }


    /**
     * @param IStockTransactionService $ingrTransactions
     */
    public function setIngrTransactionServices(IStockTransactionService $ingrTransactions)
    {
        $ingrTransactionServiceClass = get_class($ingrTransactions);

        $this->ingrTransactionServices[0] = new $ingrTransactionServiceClass($this->em);
        $this->ingrTransactionServices[1] = new $ingrTransactionServiceClass($this->em);
        $this->ingrTransactionServices[2] = new $ingrTransactionServiceClass($this->em);
    }


    public function setTransactionEventModel(IStockTransactionEvent $eventModel)
    {
        $eventModelClass = get_class($eventModel);

        $this->ingrTransactionServices[0]->setTransactionEventModel(new $eventModelClass($this->em));
        $this->ingrTransactionServices[1]->setTransactionEventModel(new $eventModelClass($this->em));
        $this->ingrTransactionServices[2]->setTransactionEventModel(new $eventModelClass($this->em));
    }


    public function setPostData($postData)
    {
        $this->postData = $postData;

        $this->ingrTransactionServices[0]->setPostData($this->differentNegativPositivePostData()[0]);
        $this->ingrTransactionServices[1]->setPostData($this->differentNegativPositivePostData()[1]);
        $this->ingrTransactionServices[2]->setPostData($this->differentNegativPositivePostData()[2]);
    }


    public function isValidPostData()
    {

        foreach ($this->postData['amount'] as $ingrId => $amount) {
            if (!is_numeric($amount)) throw new \Exception('Egy vagy több tételhez nem lett mennyiség megadva!');
        }

        $this->ingrTransactionServices[0]->isValidPostData();
        $this->ingrTransactionServices[1]->isValidPostData();
        $this->ingrTransactionServices[2]->isValidPostData();


    }

    public function validAmount()
    {
        $this->ingrTransactionServices[0]->validAmount();
        $this->ingrTransactionServices[1]->validAmount();
        $this->ingrTransactionServices[2]->validAmount();
    }

    public function triggerStockTransactionEvent()
    {
        $this->ingrTransactionServices[0]->triggerStockTransactionEvent();
        $this->ingrTransactionServices[1]->triggerStockTransactionEvent();
        $this->ingrTransactionServices[2]->triggerStockTransactionEvent();
    }


    public function adjustTransactionEventModel(\DateTime $dateTime = null)
    {
        $dateTime = new \DateTime();
        $this->ingrTransactionServices[0]->adjustTransactionEventModel($dateTime);
        $this->ingrTransactionServices[1]->adjustTransactionEventModel($dateTime);
        $this->ingrTransactionServices[2]->adjustTransactionEventModel($dateTime);
    }


    //AZ INVENTORYHOZ TARTOZÓ FG-EK --------------------------------------------------

    protected function differentNegativPositivePostData()
    {
        $postdataNegative = $this->postData;
        $postdataPositive = $this->postData;
        $postdataNull = $this->postData;
        $postdataNegative['amount'] = [];
        $postdataPositive['amount'] = [];
        $postdataNull['amount'] = [];

        $stockPositiveId = $this->em->getRepository(Storage::class)->getStockCorrectionStorage('stockCorrectionPositive')[0]->getId();
        $stockNegativeId = $this->em->getRepository(Storage::class)->getStockCorrectionStorage('stockcorrectionNegative')[0]->getId();
        $stockNullId = $this->em->getRepository(Storage::class)->getStockCorrectionStorage('stockCorrectionNull')[0]->getId();

        foreach ($this->postData['amount'] as $ingrId => $amounts) {

            if (empty($actualIngrQuery = $this->em->getRepository(Stock::class)->findBy(['storage' => $this->postData['StockTransaction']['fromStorage'], 'ingredient' => $ingrId]))) {
                throw new \Exception('Egy vagy több alapanyag nem létezik, vagy nincs a készlethez kapcsolva!');
            }
            $actualIngr = $actualIngrQuery[0];

            if (!is_numeric($amounts)) continue;

            if ($actualIngr->getAmount() < $amounts) {
                $postdataPositive['amount'][$ingrId] = ($amounts - $actualIngr->getAmount());
                $postdataPositive['StockTransaction']['fromStorage'] = $stockPositiveId;
            } elseif ($actualIngr->getAmount() > $amounts) {
                $postdataNegative['amount'][$ingrId] = ($actualIngr->getAmount() - $amounts);
                $postdataNegative['StockTransaction']['toStorage'] = $stockNegativeId;
            } elseif ($actualIngr->getAmount() == $amounts) {
                $postdataNull['amount'][$ingrId] = ($actualIngr->getAmount() - $amounts);
                $postdataNull['StockTransaction']['toStorage'] = $stockNullId;
            }
        }

        return [$postdataNegative, $postdataPositive, $postdataNull];
    }

    /**
     * Inject an EventManager instance
     *
     * @param  EventManagerInterface $eventManager
     * @return void
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $this->ingrTransactionServices[0]->setEventManager($eventManager);
        $this->ingrTransactionServices[1]->setEventManager($eventManager);
        $this->ingrTransactionServices[2]->setEventManager($eventManager);
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


}

