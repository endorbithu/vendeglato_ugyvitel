<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.27.
 * Time: 23:41
 */

namespace Application\Service\Factory\Log;


use Application\Entity\EventLog;
use Application\Service\Log\EventLogService;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class EventLogServiceFactory implements FactoryInterface
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
        $eventLog = new EventLogService($container, EventLog::class);
        return $eventLog;
    }

}