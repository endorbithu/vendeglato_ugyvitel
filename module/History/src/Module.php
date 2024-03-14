<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace History;


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



    }

}
