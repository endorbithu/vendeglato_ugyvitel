<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.25.
 * Time: 8:24
 */

namespace Order\Controller;


use Catalog\Entity\Storage;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{

    private $sm;

    public function __construct($sm)
    {
        $this->sm = $sm;
        $this->em = $sm->get(EntityManager::class);
    }

    public function indexAction()
    {
        $destinations = $this->em->getRepository(Storage::class)->getDestination();

        return ['destinations' => $destinations];
    }

}