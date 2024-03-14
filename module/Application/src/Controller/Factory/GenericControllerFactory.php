<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.07.
 * Time: 0:38
 */

namespace Application\Controller\Factory;


use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

class GenericControllerFactory implements AbstractFactoryInterface
{


    /**
     * Can the factory create an instance for the service?
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return class_exists($requestedName);
    }

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $serviceManager, $controllerName, array $options = null)
    {
        if (!class_exists($controllerName))
            throw new ServiceNotFoundException("Requested controller name " . $controllerName . " does not exists.");

        return new $controllerName($serviceManager);
    }
}