<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.06.
 * Time: 10:27
 */

namespace Application\Service\Test;


use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;

class EgyServiceAzSajatEventhez
{

   private $valtozo;

    /**
     * @return mixed
     */
    public function getValtozo()
    {
        return $this->valtozo;
    }

    /**
     * @param mixed $valtozo
     */
    public function setValtozo($valtozo)
    {
        $this->valtozo = $valtozo;
    }




}