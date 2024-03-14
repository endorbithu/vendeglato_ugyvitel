<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.13.
 * Time: 19:32
 */

namespace Catalog\Service\StockTransaction;

use Application\Filter\NumericToFloat;
use Catalog\Model\StockTransaction\IngredientCollectionModel;
use Catalog\Model\StockTransaction\IngredientTransactionEventModel;
use Catalog\Model\StockTransaction\IStockTransactionEvent;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;
use Zend\Filter\FilterChain;
use Zend\Filter\StripTags;


/**
 * Class IngredientTransactionService
 * @package Catalog\Service\StockTransaction
 * @property IngredientTransactionEventModel $transactionEventModel
 */
class IngredientTransactionService implements IStockTransactionService
{

    protected $postData;
    protected $em;
    protected $ingredientCollection;
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
     * @return IngredientTransactionEventModel
     */
    public function getTransactionEventModel()
    {
        return $this->transactionEventModel;
    }


    public function setIngredientCollectionModel(IngredientCollectionModel $ingredientCollectionModel)
    {
        $this->getTransactionEventModel()->setIngredientCollectionModel($ingredientCollectionModel);
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
    }


    public function isValidPostData()
    {
        if (!array_key_exists('amount', $this->postData) || empty($this->postData['amount'])) return true;


        //Átszűrjük a két tömböt, (kulcscsal együtt) azért van kettő, hogy js hiba itt kiderüljön, egyszerre két helyen kisebb eséllyel baxa el
        $filtersForFloat = new FilterChain();
        $filtersForFloat
            ->attach(new StripTags())
            ->attach(new NumericToFloat());

        foreach ($this->postData['amount'] as $ingrId => $amount) {
            if ($this->postData['amount'][$ingrId] === '') {
                unset($this->postData['amount'][$ingrId]);
                continue;
            }
            if (!is_numeric($this->postData['amount'][$ingrId]) || strpos($this->postData['amount'][$ingrId], '-') !== false) {
                throw new \Exception('Nem megfelelőek az adatok! (pl.szám helyett betű stb)');
            }

            $amount = $filtersForFloat->filter($amount);
            $this->postData['amount'][(int)$ingrId] = $amount;
        }
        return true;
    }


    public function adjustTransactionEventModel(\DateTime $dateTime = null)
    {
        if ($this->isValidPostData() !== true) throw new \Exception('Az adatokat nem validálta a rendszer!');


        if (array_key_exists('amount', $this->postData)) {
            $this->transactionEventModel->getIngredientCollectionModel()->addIngredients($this->postData['amount']);
        } else {
            $this->transactionEventModel->getIngredientCollectionModel()->addIngredients([]);
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
}