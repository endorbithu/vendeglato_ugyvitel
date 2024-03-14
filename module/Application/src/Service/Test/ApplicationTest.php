<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2017.03.03.
 * Time: 13:34
 */

namespace Application\Service\Test;


use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\Application;

class ApplicationTest
{
    protected $application;
    protected $event;


    public function __construct(Application $application)
    {
        $this->application = $application;
    }


    public function setMvcEvent($event)
    {
        $this->event = $event;
    }


    public function getConfig()
    {
        return $this->application->getConfig();
    }


    public function bootstrap(array $listeners = [])
    {
        return $this->application->bootstrap($listeners);
    }


    public function getServiceManager()
    {
        return $this->application->getServiceManager();
    }


    public function getRequest()
    {
        return $this->application->getRequest();
    }


    public function getResponse()
    {
        return $this->application->getResponse();
    }


    public function getMvcEvent()
    {
        return $this->event;
    }


    public function setEventManager(EventManagerInterface $eventManager)
    {
        return $this->application->setEventManager($eventManager);
    }


    public function getEventManager()
    {
        return $this->application->getEventManager();
    }


    public static function init($configuration = [])
    {
        return Application::init($configuration);
    }


    public function run()
    {
        return $this->application->run();
    }

}