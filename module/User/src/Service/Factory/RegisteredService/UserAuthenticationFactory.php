<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.14.
 * Time: 11:28
 */

namespace User\Service\Factory\RegisteredService;


use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use User\Service\RegisteredService\UserAuthentication;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;

class UserAuthenticationFactory implements FactoryInterface
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
        $authService = $container->get(AuthenticationService::class);

        $userAuth = new UserAuthentication();
        $userAuth->setAuthService($authService);

        return $userAuth;

    }
}