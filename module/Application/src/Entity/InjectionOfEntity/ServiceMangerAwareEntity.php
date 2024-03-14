<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.15.
 * Time: 15:51
 */

namespace Application\Entity\InjectionOfEntity;

use Interop\Container\ContainerInterface;

class ServiceMangerAwareEntity
{
    protected $sm;

    public function setServiceManager(ContainerInterface $sm)
    {
        if (empty($this->sm)) {
            $this->sm = $sm;
        }
    }

    public function getServiceManager()
    {
        return $this->sm;
    }
}