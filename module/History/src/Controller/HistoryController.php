<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.11.01.
 * Time: 10:06
 */

namespace History\Controller;


use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;

class HistoryController extends AbstractActionController
{

    private $sm;
    private $em;
    private $msg;

    public function __construct($sm)
    {
        $this->sm = $sm;
        $this->em = $sm->get(EntityManager::class);
    }

    //Dispatch előtt megnézzük, hogy van-e ilyen tartalom
    public function onDispatch(\Zend\Mvc\MvcEvent $e)
    {
        $this->msg = $this->getEvent()->getViewModel()->getVariable('statusMessages');
        return parent::onDispatch($e);
    }


    public function indexAction()
    {

    }


}