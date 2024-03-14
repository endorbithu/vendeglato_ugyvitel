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
use Order\Entity\DailyIncome;
use Zend\Mvc\Controller\AbstractActionController;

class IncomeHistoryController extends AbstractActionController
{

    private $sm;

    public function __construct($sm)
    {
        $this->sm = $sm;
        $this->em = $sm->get(EntityManager::class);
    }

    public function dailyincomeAction()
    {
        $destinations = $this->em->getRepository(Storage::class)->getDestination();

        return ['destinations' => $destinations];
    }

    public function monthlyincomeAction()
    {

        $monthlyByDay = $this->em->getRepository(DailyIncome::class)->getMonthIncome($year,$month);



    }

}