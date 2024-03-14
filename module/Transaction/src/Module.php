<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Transaction;


use Transaction\Entity\IngredientMoving;
use Transaction\Entity\MoneyMoving;
use Transaction\Entity\MoneyStock;
use Transaction\Entity\Stock;
use Transaction\Entity\StockTransaction;
use Transaction\Entity\ToolMoving;
use Transaction\Entity\ToolStock;
use Transaction\Service\StockTransaction\ItemTransactionDbListener;
use Transaction\Service\StockTransaction\StockTransactionDbListener;
use Zend\Mvc\MvcEvent;


class Module
{
    //const VERSION = '3.0.0dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        $sm = $e->getApplication()->getServiceManager();
        $eventManager = $e->getApplication()->getEventManager();
        $sharedEventManager = $eventManager->getSharedManager();


        //TODO: #147 ezt is meg lehetne factoryval asszem lehet listener a module.config-ba is beregisztrálni é stalán ott lehet factoryt is adni neki
        //ELŐSZÖR A STOCKTRANSACTION üres letárolása
        $entitiesStock = [];
        $entitiesStock['StockTransaction'] = StockTransaction::class;
        $stockTransactionToDb = new StockTransactionDbListener($sm, $entitiesStock);

        $sharedEventManager->attach('*', 'triggerStockTransactionEvent',
            $stockTransactionToDb, 100);



        //INGREDIENT TRANSACTION letárolása
        $entitiesIngr['Stock'] = Stock::class;
        $entitiesIngr['ItemMoving'] = IngredientMoving::class;
        $ingrTransactionToDb = new ItemTransactionDbListener($sm, $entitiesIngr);

        $sharedEventManager->attach('*', 'triggerIngredientTransactionEvent',
            [$ingrTransactionToDb, 'transactionModelTodb'], 110);


        //TOOL TRANSACTION letárolása
        $entitiesTool['Stock'] = ToolStock::class;
        $entitiesTool['ItemMoving'] = ToolMoving::class;
        $toolTransactionToDb = new ItemTransactionDbListener($sm, $entitiesTool);

        $sharedEventManager->attach('*', 'triggerToolTransactionEvent',
            [$toolTransactionToDb, 'transactionModelTodb'], 110);


        //MONEY TRANSACTION letárolása
        $entitiesMoney['Stock'] = MoneyStock::class;
        $entitiesMoney['ItemMoving'] = MoneyMoving::class;
        $moneyTransactionToDb = new ItemTransactionDbListener($sm, $entitiesMoney);

        $sharedEventManager->attach('*', 'triggerMoneyTransactionEvent',
            [$moneyTransactionToDb, 'transactionModelTodb'], 110);


    }

}
