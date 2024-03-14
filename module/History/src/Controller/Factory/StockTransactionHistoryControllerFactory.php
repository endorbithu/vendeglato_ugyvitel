<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.11.01.
 * Time: 10:09
 */

namespace History\Controller\Factory;


use Application\Model\RetrieveByDatatableModel;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class StockTransactionHistoryControllerFactory implements FactoryInterface
{
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
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $misc = [];
        $misc['datatableModelClass'] = RetrieveByDatatableModel::class;

        if (!class_exists($requestedName))
            throw new ServiceNotFoundException("Requested controller name " . $requestedName . " does not exists.");

        return new $requestedName($container, $misc);

    }

}