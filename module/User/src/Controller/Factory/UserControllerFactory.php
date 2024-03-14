<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.07.
 * Time: 0:38
 */

namespace User\Controller\Factory;


use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use User\Controller\UserController;
use User\Entity\User;
use User\Form\LoginForm;
use Zend\Captcha\Figlet;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class UserControllerFactory implements FactoryInterface
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
        $captcha = new Figlet([
            'name' => 'foo',
            'wordLen' => 4,
            'timeout' => 300,
            'outputWidth' => 45
        ]);

        $internalService = array();
        $internalService['LoginForm'] = new LoginForm($captcha, $container->get(EntityManager::class), new User());


        return new UserController($container, $internalService);
    }
}