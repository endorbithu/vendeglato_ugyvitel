<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Catalog;


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

        //** CURRENCY
        //Ha _GET vagy COOKIE tartalmaz currencyt akkor vátunk currencyt a cookieban meg eltároljuk
        //ha a POST-tal együtt akarják baszkurálni, tehát először átállítani Ft-ba aztán az
        // EUR-ban meadott értékekkel szétcseszné a konzisztenciát!!
        //DOCTRINE CONSOLE: csak úgymegy ha ezt kikommenteled mert eaz isPost() consoleban nem megy
        if (false === $e->getRequest()->isPost()) {
            $sm->get('currencyConverter')->changeCurrency($e);
        }
    }


}
