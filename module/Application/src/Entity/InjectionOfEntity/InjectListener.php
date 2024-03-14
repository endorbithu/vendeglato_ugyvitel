<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.15.
 * Time: 16:09
 */

namespace Application\Entity\InjectionOfEntity;
use Interop\Container\ContainerInterface;

class InjectListener
{
    private $sm;
    public function __construct(ContainerInterface $sm)
    {
        $this->sm = $sm;
    }

    public function postload($eventArgs)
    {
        $entity = $eventArgs->getEntity();

        if($entity instanceof ServiceMangerAwareEntity)
            $entity->setServiceManager($this->sm);
    }
}