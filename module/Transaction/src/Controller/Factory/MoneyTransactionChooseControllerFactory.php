<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.07.
 * Time: 0:38
 */

namespace Transaction\Controller\Factory;


use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class MoneyTransactionChooseControllerFactory implements FactoryInterface
{


    /**
     * Create an object
     *
     * @param  ContainerInterface $serviceManager
     * @param  string $controllerName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $serviceManager, $controllerName, array $options = null)
    {

        $misc = [];
        $em = $serviceManager->get('Doctrine\ORM\EntityManager');
        $actionName = $serviceManager->get('application')->getMvcEvent()->getRouteMatch()->getParam('action');
        $misc['transactionType'] = $actionName;
        $misc['em'] = $em;


        if (!class_exists($controllerName))
            throw new ServiceNotFoundException("Requested controller name " . $controllerName . " does not exists.");

        return new $controllerName($serviceManager, $misc);
    }
}