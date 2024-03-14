<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Transaction\Controller;

use Catalog\Entity\Storage;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

//Itt kell majd mint a másiknál is a Factoryban összepakolni a cuccokat és params() szerint megépíteni az oldalt
//Ide fognak érkezni a usertől a postok amik a transactio adatait tartalmazzák,
class MoneyTransactionChooseController extends AbstractActionController
{
    private $sm;
    private $misc;
    private $viewModel;

    public function __construct($sm, $misc)
    {
        $this->sm = $sm;
        $this->em = $this->sm->get('Doctrine\ORM\EntityManager');
        $this->misc = $misc;
        $this->viewModel = new ViewModel();
        $this->misc['selectedFrom'] = '';
        $this->misc['selectedTo'] = '';
        
    }


    //Dispatch előtt megnézzük, hogy van-e ilyen tartalom
    public function onDispatch(\Zend\Mvc\MvcEvent $e)
    {
        $this->misc['selectedFrom'] = (int)$this->params()->fromQuery('from');
        $this->misc['selectedTo'] = (int)$this->params()->fromQuery('to');
        return parent::onDispatch($e);
    }


    protected function moneyTransactionChoose()
    {
        $from = [];
        foreach ($this->misc['from'] as $fromEntity) {
            $from[$fromEntity->getId()] = $fromEntity->getName();
        }

        $to = [];
        foreach ($this->misc['to'] as $toEntity) {
            $to[$toEntity->getId()] = $toEntity->getName();
        }

        $this->viewModel->setVariables(array(
            'from' => $this->misc['from'],
            'to' => $this->misc['to'],
            'selectedTo' => $this->misc['selectedTo'],
            'selectedFrom' => $this->misc['selectedFrom'],
            'transactionType' => $this->misc['transactionType'],
            'title' => $this->misc['title'],
        ));

        return $this->viewModel;

    }



    public function moneyinAction()
    {

        $this->viewModel->setTemplate('transaction/money-transaction-choose/_money-transaction-choose.phtml');
        $this->misc['from'] = $this->em->getRepository(Storage::class)->findby(['storageType' => 4], ['name' => 'ASC']);
        $this->misc['to'] = $this->em->getRepository(Storage::class)->getRealStorage(['money']);
        $this->misc['title'] = "Befizetés";

        return $this->moneyTransactionChoose();
    }


    public function moneyoutAction()
    {
        $this->viewModel->setTemplate('transaction/money-transaction-choose/_money-transaction-choose.phtml');
        $this->misc['from'] = $this->em->getRepository(Storage::class)->getRealStorage(['money']);
        $this->misc['to'] = $this->em->getRepository(Storage::class)->findby(['storageType' => 4], ['name' => 'ASC']);
        $this->misc['title'] = "Kifizetés";

        return $this->moneyTransactionChoose();
    }

    public function moneymoveAction()
    {
        $this->viewModel->setTemplate('transaction/money-transaction-choose/_money-transaction-choose.phtml');
        $this->misc['from'] = $this->em->getRepository(Storage::class)->getRealStorage(['money']);
        $this->misc['to'] = $this->em->getRepository(Storage::class)->getRealStorage(['money']);
        $this->misc['title'] = "Pénzeszköz áthelyezés";

        return $this->moneyTransactionChoose();

    }


    public function moneystockcorrectionAction()
    {
        $this->viewModel->setTemplate('transaction/money-transaction-choose/_money-transaction-choose.phtml');
        $this->misc['from'] = $this->em->getRepository(Storage::class)->getRealStorage(['money']);
        $this->misc['to'] = $this->em->getRepository(Storage::class)->getRealStorage(['money']);
        $this->misc['title'] = "Pénztárkorrekció";
        $this->sm->get('ViewHelperManager')->get('inlineScript')->appendFile($this->sm->get('ViewHelperManager')->get('basePath')->__invoke('/js/ingr-transaction-choose/stockcorrection.js'));

        return $this->moneyTransactionChoose();
    }



    public function moneyuniversalAction()
    {
        $this->viewModel->setTemplate('transaction/money-transaction-choose/_money-transaction-choose.phtml');
        $this->misc['from'] = $this->em->getRepository(Storage::class)->findAll();
        $this->misc['to'] = $this->em->getRepository(Storage::class)->findAll();
        $this->misc['title'] = "Univerzális pénzeszköz-mozgatás";

        return $this->moneyTransactionChoose();
    }


}
