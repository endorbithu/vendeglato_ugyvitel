<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.14.
 * Time: 9:52
 * @copyright Copyright (c) 2011, Marco Neumann
 * @license   http://binware.org/license/index/type:new-bsd New BSD License
 */

/**
 * Module Class
 *
 * Handles Module Initialization
 *
 * @category  User
 * @package   User
 * @copyright Copyright (c) 2011, Marco Neumann
 * @license   http://binware.org/license/index/type:new-bsd New BSD License
 */

namespace User;

/**
 * @uses Zend\Module\Consumer\AutoloaderProvider
 * @uses Zend\EventManager\SharedEventManager
 */
use Zend\Mvc\MvcEvent;


class Module
{
    /**
     * Get Module Configuration
     *
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * Bootstrap function
     *
     * @return void
     */
    function onBootstrap(MvcEvent $e)
    {


    }





}