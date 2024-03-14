<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.01.
 * Time: 10:46
 */

namespace Catalog\Controller;

use Catalog\Entity\Stock;
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

            $ingredients = $this->sm->get(EntityManager::class)->getRepository(Stock::class)->findBy(['storage' => $storageId]);
            $i = 0;
            foreach ($ingredients as $ingredient) {
                $datas[$i][] = $ingredient->getIngredient()->getId();
                $datas[$i][] = $ingredient->getIngredient()->getIngredientGroup()->getName();
                $datas[$i][] = $ingredient->getIngredient()->getName();
                $datas[$i][] = $ingredient->getAmount() . ' ' . $ingredient->getIngredient()->getIngredientUnit()->getShortName();
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
            $allRealStorage = $this->sm->get(EntityManager::class)->getRepository(Storage::class)->getRealStorage();

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
            $viewModel->setTemplate('catalog/stock-show/_all-storage.phtml');

            $viewModel->setVariables(['datatableModel' => $this->misc['datatableModel']]);

            return $viewModel;
        }


    }

}