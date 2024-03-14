<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.01.
 * Time: 10:46
 */

namespace Transaction\Controller;

use Catalog\Entity\Storage;
use Doctrine\ORM\EntityManager;
use Transaction\Entity\ToolStock;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ToolStockShowController extends AbstractActionController
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

            $toolStorage = $this->sm->get(EntityManager::class)->getRepository(ToolStock::class)->findBy(['storage' => $storageId]);
            $i = 0;
            foreach ($toolStorage as $tool) {
                $datas[$i][] = $tool->getItem()->getId();
                $datas[$i][] = $tool->getItem()->getToolGroup()->getName();
                $datas[$i][] = $tool->getItem()->getName();
                $datas[$i][] = $this->sm->get('nf')->nf($tool->getAmount(),true) . ' ' . $tool->getItem()->getToolUnit()->getShortName();

                $i++;
            }

            $this->misc['datatableModel']->setName('stockshow');
            $this->misc['datatableModel']->setData($datas);
            $this->misc['datatableModel']->setHeader(['ID', 'Eszközcsoport', 'Eszköz', 'Mennyiség']);

            return [
                'datatableModel' => $this->misc['datatableModel'],
                'title' => $this->sm->get(EntityManager::class)->getRepository(Storage::class)->find($storageId)->getName(),
                'storageId'=> $storageId
            ];

        } else {
            $allRealStorage = $this->sm->get(EntityManager::class)->getRepository(Storage::class)->getRealStorage(['tool']);

            $datas = [];
            $i = 0;
            foreach ($allRealStorage as $storage) {
                $datas[$i][] = $storage->getId();
                $datas[$i][] = '<a href="' . $this->url()->fromRoute('toolstock', ['id' => $storage->getId()]) . '">' . $storage->getName() . '</a>';
                $datas[$i][] = $storage->getStorageType()->getName();
                $i++;
            }

            $this->misc['datatableModel']->setName('stockshowall');
            $this->misc['datatableModel']->setData($datas);
            $this->misc['datatableModel']->setHeader(['ID', 'Készlet neve', 'Típus']);
            $this->misc['datatableModel']->setNaked(true);

            $viewModel = new ViewModel();
            $viewModel->setTemplate('transaction/tool-stock-show/_all-storage.phtml');

            $viewModel->setVariables(['datatableModel' => $this->misc['datatableModel']]);

            return $viewModel;
        }


    }

}