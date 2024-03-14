<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.01.
 * Time: 10:46
 */

namespace Transaction\Controller;

use Transaction\Entity\MoneyStock;
use Catalog\Entity\Storage;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class MoneyStockShowController extends AbstractActionController
{

    protected $sm;
    protected $misc;

    public function __construct($sm, $misc)
    {
        $this->sm = $sm;
        $this->misc = $misc;
    }

    public function indexAction()
    {

        if (!empty($storageId = $this->params()->fromRoute('id'))) {
            $datas = [];

            $moneyStorage = $this->sm->get(EntityManager::class)->getRepository(MoneyStock::class)->findBy(['storage' => $storageId]);
            $i = 0;
            foreach ($moneyStorage as $money) {
                $datas[$i][] = $money->getItem()->getId();
                $datas[$i][] = $money->getItem()->getMoneyGroup()->getName();
                $datas[$i][] = $money->getItem()->getName();
                $datas[$i][] = $this->sm->get('nf')->nf($money->getAmount(),true) . ' ' . $money->getItem()->getMoneyUnit()->getShortName();
                $i++;
            }

            $this->misc['datatableModel']->setName('stockshow');
            $this->misc['datatableModel']->setData($datas);
            $this->misc['datatableModel']->setHeader(['ID', 'Pénzeszköz csoport', 'Pénzeszköz', 'Mennyiség']);

            return [
                'datatableModel' => $this->misc['datatableModel'],
                'title' => $this->sm->get(EntityManager::class)->getRepository(Storage::class)->find($storageId)->getName(),
                'storageId'=> $storageId
            ];

        } else {
            $allRealStorage = $this->sm->get(EntityManager::class)->getRepository(Storage::class)->getRealStorage(['money']);

            $datas = [];
            $i = 0;
            foreach ($allRealStorage as $storage) {
                $datas[$i][] = $storage->getId();
                $datas[$i][] = '<a href="' . $this->url()->fromRoute('moneystock', ['id' => $storage->getId()]) . '">' . $storage->getName() . '</a>';
                $i++;
            }

            $this->misc['datatableModel']->setName('stockshowall');
            $this->misc['datatableModel']->setData($datas);
            $this->misc['datatableModel']->setHeader(['ID', 'Pénztár neve']);
            $this->misc['datatableModel']->setNaked(true);

            $viewModel = new ViewModel();
            $viewModel->setTemplate('transaction/money-stock-show/_all-storage.phtml');

            $viewModel->setVariables(['datatableModel' => $this->misc['datatableModel']]);

            return $viewModel;
        }


    }

}