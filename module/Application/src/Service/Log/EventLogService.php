<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.13.
 * Time: 19:45
 */

namespace Application\Service\Log;


use Doctrine\ORM\EntityManager;
use User\Service\RegisteredService\UserAuthentication;

class EventLogService
{
    protected $sm;
    protected $em;
    protected $eventLogClass;

    public function __construct($sm, $eventLogClass)
    {
        $this->sm = $sm;
        $this->em = $sm->get(EntityManager::class);
        $this->eventLogClass = $eventLogClass;
    }

    public function __invoke($message, $eventType, $resourceId = '')
    {
        $userId = $this->sm->get(UserAuthentication::class)->getIdentity() ? $this->sm->get(UserAuthentication::class)->getIdentity()->getId() : 0;
        $eventLog = new $this->eventLogClass();
        $eventLog->setServiceManager($this->sm);

        $eventLog->setUser($userId);
        $eventLog->setEventType($eventType);
        $eventLog->setMessage($message);
        $eventLog->setResourceId($resourceId);
        $eventLog->setDateTime('');

        $this->em->persist($eventLog);
        $this->em->flush();
    }
}