<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.01.
 * Time: 10:46
 */

namespace Catalog\Controller;


use Catalog\Model\BaseData\BaseDataControllerModel;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class BaseDataShowTableController extends AbstractActionController
{
    private $sm;
    private $misc;
    private $translator;
    private $repository;
    private $title;
    private $em;
    private $viewModel;
    private $actionName;

    public function __construct($sm, BaseDataControllerModel $controllerModel)
    {
        $this->sm = $sm;
        $this->misc = $controllerModel->getMisc();

        $this->actionName = $controllerModel->getActionName();
        $this->repository = $controllerModel->getRepository();
        $this->title = $controllerModel->getTitle();
        $this->misc['v'] = [];
        $this->misc['v']['datas'] = [];
        $this->em = $this->sm->get(EntityManager::class);
        $this->translator = $this->sm->get('ViewHelperManager')->get('translate');

        $this->viewModel = new ViewModel();
    }


    protected function _baseDataRetrieve()
    {
        $this->misc['v']['actionName'] = $this->actionName;

        $this->viewModel->setTemplate('catalog/base-data-show-table/_base-data-collection.phtml');

        $this->viewModel->setVariables(array(
            'v' => $this->misc['v'],
        ));

        return $this->viewModel;

    }


    public function ingredientAction()
    {

        $this->misc['v']['headerData'] = [
            'Id',
            'Név',
            'Csoport',
            'Mértéke.',
            'Minimum',
            'Egyéb'];
        foreach ($this->repository->findAll() as $key => $content) {
            $this->misc['v']['datas'][$key][] = $content->getId();
            $this->misc['v']['datas'][$key][] = '<a href="' . $this->url()->fromRoute('basedata', ['action' => 'ingredient', 'id' => $content->getId()]) . '">' . $content->getName() . '</a>';
            $this->misc['v']['datas'][$key][] = '<a href="' . $this->url()->fromRoute('basedata', ['action' => 'ingredientgroup', 'id' => $content->getIngredientGroup()->getId()]) . '">' . $content->getIngredientGroup()->getName() . '</a>';
            $this->misc['v']['datas'][$key][] = $content->getIngredientUnit()->getName();
            $this->misc['v']['datas'][$key][] = $this->sm->get('nf')->nf($content->getMinimumAmount(),true) . ' ' . $content->getIngredientUnit()->getShortName();
            $this->misc['v']['datas'][$key][] = empty($content->getMoreInfo()) ? '' : substr($content->getMoreInfo(), 1, 16) . '...';
        }


        $this->misc['v']['baseDataType'] = 'ingredient';
        $this->misc['v']['title'] = 'Alapanyagok';
        $this->misc['v']['moreInfo'] = '';

        return $this->_baseDataRetrieve();


    }

    public function ingredientgroupAction()
    {

        $this->misc['v']['headerData'] = [
            'Id',
            'Név'
        ];
        foreach ($this->repository->findAll() as $key => $content) {
            $this->misc['v']['datas'][$key][] = $content->getId();
            $this->misc['v']['datas'][$key][] = '<a href="' . $this->url()->fromRoute('basedata', ['action' => 'ingredientgroup', 'id' => $content->getId()]) . '">' . $content->getName() . '</a>';
        }

        $this->misc['v']['baseDataType'] = 'ingredientgroup';
        $this->misc['v']['title'] = 'Alapanyagcsoportok';
        $this->misc['v']['moreInfo'] = '';

        return $this->_baseDataRetrieve();

    }


    public function ingredientunitAction()
    {

        $this->misc['v']['headerData'] = [
            'Id',
            'Név',
            'Rövid név',
            'Tizedesek?',
        ];
        foreach ($this->repository->findAll() as $key => $content) {
            $this->misc['v']['datas'][$key][] = $content->getId();
            $this->misc['v']['datas'][$key][] = '<a href="' . $this->url()->fromRoute('basedata', ['action' => 'ingredientunit', 'id' => $content->getId()]) . '">' . $content->getName() . '</a>';
            $this->misc['v']['datas'][$key][] = $content->getShortName();
            $this->misc['v']['datas'][$key][] = (($content->getIsDecimal()) ? $this->translator->__invoke('Igen') : $this->translator->__invoke('Nem'));
        }

        $this->misc['v']['baseDataType'] = 'ingredientunit';
        $this->misc['v']['title'] = 'Alapanyag mértékegységek';
        $this->misc['v']['moreInfo'] = '';

        return $this->_baseDataRetrieve();

    }

    public function toolAction()
    {

        $this->misc['v']['headerData'] = [
            'Id',
            'Név',
            'Csoport',
            'Mértéke.',
            'Egyéb'];
        foreach ($this->repository->findAll() as $key => $content) {
            $this->misc['v']['datas'][$key][] = $content->getId();
            $this->misc['v']['datas'][$key][] = '<a href="' . $this->url()->fromRoute('basedata', ['action' => 'tool', 'id' => $content->getId()]) . '">' . $content->getName() . '</a>';
            $this->misc['v']['datas'][$key][] = '<a href="' . $this->url()->fromRoute('basedata', ['action' => 'toolgroup', 'id' => $content->getToolGroup()->getId()]) . '">' . $content->getToolGroup()->getName() . '</a>';
            $this->misc['v']['datas'][$key][] = $content->getToolUnit()->getName();
            $this->misc['v']['datas'][$key][] = empty($content->getMoreInfo()) ? '' : substr($content->getMoreInfo(), 1, 16) . '...';
        }


        $this->misc['v']['baseDataType'] = 'tool';
        $this->misc['v']['title'] = 'Eszközök';
        $this->misc['v']['moreInfo'] = '';

        return $this->_baseDataRetrieve();


    }

    public function toolgroupAction()
    {

        $this->misc['v']['headerData'] = [
            'Id',
            'Név'
        ];
        foreach ($this->repository->findAll() as $key => $content) {
            $this->misc['v']['datas'][$key][] = $content->getId();
            $this->misc['v']['datas'][$key][] = '<a href="' . $this->url()->fromRoute('basedata', ['action' => 'toolgroup', 'id' => $content->getId()]) . '">' . $content->getName() . '</a>';
        }

        $this->misc['v']['baseDataType'] = 'toolgroup';
        $this->misc['v']['title'] = 'Eszközcsoportok';
        $this->misc['v']['moreInfo'] = '';

        return $this->_baseDataRetrieve();

    }


    public function toolunitAction()
    {

        $this->misc['v']['headerData'] = [
            'Id',
            'Név',
            'Rövid név',
            'Tizedesek?',
        ];
        foreach ($this->repository->findAll() as $key => $content) {
            $this->misc['v']['datas'][$key][] = $content->getId();
            $this->misc['v']['datas'][$key][] = '<a href="' . $this->url()->fromRoute('basedata', ['action' => 'toolunit', 'id' => $content->getId()]) . '">' . $content->getName() . '</a>';
            $this->misc['v']['datas'][$key][] = $content->getShortName();
            $this->misc['v']['datas'][$key][] = (($content->getIsDecimal()) ? $this->translator->__invoke('Igen') : $this->translator->__invoke('Nem'));

        }

        $this->misc['v']['baseDataType'] = 'toolunit';
        $this->misc['v']['title'] = 'Eszköz mértékegységek';
        $this->misc['v']['moreInfo'] = '';

        return $this->_baseDataRetrieve();

    }
    public function moneyAction()
    {

        $this->misc['v']['headerData'] = [
            'Id',
            'Név',
            'Csoport',
            'Pénznem',
            'Egyéb'];
        foreach ($this->repository->findAll() as $key => $content) {
            $this->misc['v']['datas'][$key][] = $content->getId();
            $this->misc['v']['datas'][$key][] = '<a href="' . $this->url()->fromRoute('basedata', ['action' => 'money', 'id' => $content->getId()]) . '">' . $content->getName() . '</a>';
            $this->misc['v']['datas'][$key][] = '<a href="' . $this->url()->fromRoute('basedata', ['action' => 'moneygroup', 'id' => $content->getMoneyGroup()->getId()]) . '">' . $content->getMoneyGroup()->getName() . '</a>';
            $this->misc['v']['datas'][$key][] = $content->getMoneyUnit()->getName();
            $this->misc['v']['datas'][$key][] = empty($content->getMoreInfo()) ? '' : substr($content->getMoreInfo(), 1, 16) . '...';
        }


        $this->misc['v']['baseDataType'] = 'money';
        $this->misc['v']['title'] = 'Pénzeszközök';
        $this->misc['v']['moreInfo'] = '';

        return $this->_baseDataRetrieve();


    }

    public function moneygroupAction()
    {

        $this->misc['v']['headerData'] = [
            'Id',
            'Név'
        ];
        foreach ($this->repository->findAll() as $key => $content) {
            $this->misc['v']['datas'][$key][] = $content->getId();
            $this->misc['v']['datas'][$key][] = '<a href="' . $this->url()->fromRoute('basedata', ['action' => 'moneygroup', 'id' => $content->getId()]) . '">' . $content->getName() . '</a>';
        }

        $this->misc['v']['baseDataType'] = 'moneygroup';
        $this->misc['v']['title'] = 'Pénzeszköz csoportok';
        $this->misc['v']['moreInfo'] = '';

        return $this->_baseDataRetrieve();

    }


    public function moneyunitAction()
    {

        $this->misc['v']['headerData'] = [
            'Id',
            'Név',
            'Rövid név',
            'Tizedesek?',
        ];
        foreach ($this->repository->findAll() as $key => $content) {
            $this->misc['v']['datas'][$key][] = $content->getId();
            $this->misc['v']['datas'][$key][] = '<a href="' . $this->url()->fromRoute('basedata', ['action' => 'moneyunit', 'id' => $content->getId()]) . '">' . $content->getName() . '</a>';
            $this->misc['v']['datas'][$key][] = $content->getShortName();
            $this->misc['v']['datas'][$key][] = (($content->getIsDecimal()) ? $this->translator->__invoke('Igen') : $this->translator->__invoke('Nem'));

        }

        $this->misc['v']['baseDataType'] = 'moneyunit';
        $this->misc['v']['title'] = 'Pénznemek';
        $this->misc['v']['moreInfo'] = '';

        return $this->_baseDataRetrieve();

    }

    public function productAction()
    {

        $this->misc['v']['headerData'] = [
            'Id',
            'Név',
            'Ár',
            'Rövid név',
            'Termékcsoport',
            'Áfa csoport',
            'Aktív?'
        ];
        foreach ($this->repository->findAll() as $key => $content) {
            $this->misc['v']['datas'][$key][] = $content->getId();
            $this->misc['v']['datas'][$key][] = '<a href="' . $this->url()->fromRoute('basedata', ['action' => 'product', 'id' => $content->getId()]) . '">' . $content->getName() . '</a>';
            $this->misc['v']['datas'][$key][] = $this->sm->get('ViewHelperManager')->get('money')->__invoke($content->getPrice());
            $this->misc['v']['datas'][$key][] = $content->getShortName();
            $this->misc['v']['datas'][$key][] = '<a href="' . $this->url()->fromRoute('basedata', ['action' => 'productgroup', 'id' => $content->getProductGroup()->getId()]) . '">' . $content->getProductGroup()->getName() . '</a>';
            $this->misc['v']['datas'][$key][] = $content->getVatGroup()->getName();
            $this->misc['v']['datas'][$key][] = empty($content->getIsActive()) ? $this->translator->__invoke('Nem') : $this->translator->__invoke('Igen');
        }

        $this->misc['v']['baseDataType'] = 'product';
        $this->misc['v']['title'] = 'Termékek';
        $this->misc['v']['moreInfo'] = '';

        return $this->_baseDataRetrieve();

    }

    public function productgroupAction()
    {

        $this->misc['v']['headerData'] = [
            'Id',
            'Név'
        ];
        foreach ($this->repository->findAll() as $key => $content) {
            $this->misc['v']['datas'][$key][] = $content->getId();
            $this->misc['v']['datas'][$key][] = '<a href="' . $this->url()->fromRoute('basedata', ['action' => 'productgroup', 'id' => $content->getId()]) . '">' . $content->getName() . '</a>';
        }

        $this->misc['v']['baseDataType'] = 'productgroup';
        $this->misc['v']['title'] = 'Termék csoportok';
        $this->misc['v']['moreInfo'] = '';

        return $this->_baseDataRetrieve();

    }

    public function storageAction()
    {


        $this->misc['v']['headerData'] = [
            'Id',
            'Név',
            'Típus'
        ];
        foreach ($this->repository->findAll() as $key => $content) {
            $this->misc['v']['datas'][$key][] = $content->getId();
            $this->misc['v']['datas'][$key][] = '<a href="' . $this->url()->fromRoute('basedata', ['action' => 'storage', 'id' => $content->getId()]) . '">' . $content->getName() . '</a>';
            $this->misc['v']['datas'][$key][] = '<a href="' . $this->url()->fromRoute('basedata', ['action' => 'storagetype', 'id' => $content->getStorageType()->getId()]) . '">' . $content->getStorageType()->getName() . '</a>';
        }

        $this->misc['v']['baseDataType'] = 'storage';
        $this->misc['v']['title'] = 'Tárolók';
        $this->misc['v']['moreInfo'] = '';
        return $this->_baseDataRetrieve();

    }

    public function storagetypeAction()
    {


        $this->misc['v']['headerData'] = [
            'Id',
            'Név',
            'Azonosító',
            'Nyilvántartás?',
        ];
        foreach ($this->repository->findAll() as $key => $content) {
            $this->misc['v']['datas'][$key][] = $content->getId();
            $this->misc['v']['datas'][$key][] = '<a href="' . $this->url()->fromRoute('basedata', ['action' => 'storagetype', 'id' => $content->getId()]) . '">' . $content->getName() . '</a>';
            $this->misc['v']['datas'][$key][] = $content->getStringId();
            $this->misc['v']['datas'][$key][] = (($content->getIsRealStorageType()) ? $this->translator->__invoke('Igen') : $this->translator->__invoke('Nem'));
        }

        $this->misc['v']['baseDataType'] = 'storagetype';
        $this->misc['v']['title'] = 'Tároló típusok';
        $this->misc['v']['moreInfo'] = '';

        return $this->_baseDataRetrieve();

    }

    public function supplierAction()
    {
        $this->misc['v']['headerData'] = [
            'Id',
            'Név',
            'Referens',
            'Telszám',
        ];

        foreach ($this->repository->findAll() as $key => $content) {
            $this->misc['v']['datas'][$key][] = $content->getId();
            $this->misc['v']['datas'][$key][] = '<a href="' . $this->url()->fromRoute('basedata', ['action' => 'supplier', 'id' => $content->getId()]) . '">' . $content->getName() . '</a>';
            $this->misc['v']['datas'][$key][] = $content->getContactPerson();
            $this->misc['v']['datas'][$key][] = $content->getTelNumber();
        }

        $this->misc['v']['baseDataType'] = 'supplier';
        $this->misc['v']['title'] = 'Beszállítók';
        $this->misc['v']['moreInfo'] = '';

        return $this->_baseDataRetrieve();

    }

    public function vatgroupAction()
    {

        $this->misc['v']['headerData'] = [
            'Id',
            'Név',
            'Érték'
        ];
        foreach ($this->repository->findAll() as $key => $content) {
            $this->misc['v']['datas'][$key][] = $content->getId();
            $this->misc['v']['datas'][$key][] = '<a href="' . $this->url()->fromRoute('basedata', ['action' => 'vatgroup', 'id' => $content->getId()]) . '">' . $content->getName() . '</a>';
            $this->misc['v']['datas'][$key][] = 100 * $content->getVatValue() . '%';
        }

        $this->misc['v']['baseDataType'] = 'vatgroup';
        $this->misc['v']['title'] = 'ÁFA kategóriák';
        $this->misc['v']['moreInfo'] = '';

        return $this->_baseDataRetrieve();

    }


}