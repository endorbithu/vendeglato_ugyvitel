<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.07.
 * Time: 0:38
 */

namespace Application\Controller\Factory;


use Application\Entity\EventLog;
use Application\Service\Crud\ManipulateEntityByForm;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use User\Entity\User;
use User\Form\Fieldset\UserFieldset;
use User\Form\UserRegisterForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class SettingsControllerFactory implements FactoryInterface
{


    public function __invoke(ContainerInterface $serviceManager, $controllerName, array $options = null)
    {
        $misc = [];

        $userEntity = new User();
        $userEntity->setServiceManager($serviceManager);

        $misc['userForm'] = new UserRegisterForm(new UserFieldset($serviceManager->get(EntityManager::class), $userEntity));

        $misc['modifyDbByForm'] = new ManipulateEntityByForm($serviceManager->get(EntityManager::class));

        $misc['entity'] = [];
        $misc['entity']['User'] = clone $userEntity;
        $misc['entity']['EventLog'] = new EventLog();
        $misc['entity']['EventLog']->setServiceManager($serviceManager);

        if (!class_exists($controllerName))
            throw new ServiceNotFoundException("Requested controller name " . $controllerName . " does not exists.");


        return new $controllerName($serviceManager, $misc);
    }
}