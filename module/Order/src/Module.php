<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Order;


use Order\Entity\DailyIncome;
use Order\Entity\OrderItemInStorage;
use Order\Entity\ProductMoving;
use Order\Service\Income\IncomeListener;
use Order\Service\ProductTransaction\NewOrderTransactionDbListener;
use Order\Service\ProductTransaction\NewOrderTransactionService;
use Order\Service\ProductTransaction\OrderItemTransactionDbListener;
use Order\Service\ProductTransaction\OrderItemTransactionService;
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


        //TODO: #162 itt az összes oldalon példányosulnak a cuccok, ha lehet, csak ::class t küldj be
        //ezt is meg lehetne factoryval asszem lehet listener a module.config-ba is beregisztrálni é stalán ott lehet factoryt is adni neki
        $entities = [];
        $entities['OrderItemInStorage'] = OrderItemInStorage::class;
        $entities['ProductMoving'] = ProductMoving::class;
        $productTransactionToDb = new NewOrderTransactionDbListener($sm, $entities);
        //feliratkozás
        $sharedEventManager->attach(NewOrderTransactionService::class, 'triggerProductTransactionEvent',
            [$productTransactionToDb, 'transactionModelTodb'], 100);


        $entities = [];
        $entities['OrderItemInStorage'] = OrderItemInStorage::class;
        $entities['ProductMoving'] = ProductMoving::class;
        $orderItemDbListener = new OrderItemTransactionDbListener($sm, $entities);
        //feliratkozás
        $sharedEventManager->attach(OrderItemTransactionService::class, 'triggerOrderItemTransactionEvent',
            [$orderItemDbListener, 'transactionModelTodb'], 100);


        $entities = [];
        $entities['DailyIncome'] = DailyIncome::class;
        $incomeListener = new IncomeListener($sm, $entities);

        $sharedEventManager->attach(OrderItemTransactionService::class, 'triggerOrderItemTransactionEvent',
            $incomeListener, 110);


    }

}
