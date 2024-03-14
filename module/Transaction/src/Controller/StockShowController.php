<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.01.
 * Time: 10:46
 */

namespace Transaction\Controller;

use Transaction\Entity\Stock;
use Catalog\Entity\Storage;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class StockShowController extends AbstractActionController
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

            $ingredientStorage = $this->sm->get(EntityManager::class)->getRepository(Stock::class)->findBy(['storage' => $storageId]);
            $i = 0;
            foreach ($ingredientStorage as $ingredient) {
                $datas[$i][] = $ingredient->getItem()->getId();
                $datas[$i][] = $ingredient->getItem()->getIngredientGroup()->getName();
                $datas[$i][] = $ingredient->getItem()->getName();
                $datas[$i][] = $this->sm->get('nf')->nf($ingredient->getAmount(),true) . ' ' . $ingredient->getItem()->getIngredientUnit()->getShortName();
                $i++;
            }

            $this->misc['datatableModel']->setName('stockshow');
            $this->misc['datatableModel']->setData($datas);
            $this->misc['datatableModel']->setHeader(['ID', 'Alapanyagcsoport', 'Alapanyag', 'Mennyiség']);

            return [
                'datatableModel' => $this->misc['datatableModel'],
                'title' => $this->sm->get(EntityManager::class)->getRepository(Storage::class)->find($storageId)->getName(),
                'storageId'=> $storageId
            ];

        } else {
            $allRealStorage = $this->sm->get(EntityManager::class)->getRepository(Storage::class)->getRealStorage(['ingredient']);

            $datas = [];
            $i = 0;
            foreach ($allRealStorage as $storage) {
                $datas[$i][] = $storage->getId();
                $datas[$i][] = '<a href="' . $this->url()->fromRoute('stock', ['id' => $storage->getId()]) . '">' . $storage->getName() . '</a>';
                $datas[$i][] = $storage->getStorageType()->getName();
                $i++;
            }

            $this->misc['datatableModel']->setName('stockshowall');
            $this->misc['datatableModel']->setData($datas);
            $this->misc['datatableModel']->setHeader(['ID', 'Készlet neve', 'Típus']);
            $this->misc['datatableModel']->setNaked(true);

            $viewModel = new ViewModel();
            $viewModel->setTemplate('transaction/stock-show/_all-storage.phtml');

            $viewModel->setVariables(['datatableModel' => $this->misc['datatableModel']]);

            return $viewModel;
        }


    }

}