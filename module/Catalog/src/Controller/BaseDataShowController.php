<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.01.
 * Time: 10:46
 */

namespace Catalog\Controller;


use Catalog\Model\BaseData\BaseDataControllerModel;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class BaseDataShowController extends AbstractActionController
{

    private $sm;
    private $misc;
    private $translator;
    private $id;
    private $repository;
    private $title;
    private $em;
    private $viewModel;
    private $actionName;

    public function __construct($sm, BaseDataControllerModel $controllerModel)
    {
        $this->sm = $sm;
        $this->misc = $controllerModel->getMisc();

        $this->id = $controllerModel->getId();
        $this->actionName = $controllerModel->getActionName();
        $this->repository = $controllerModel->getRepository();
        $this->title = $controllerModel->getTitle();
        $this->misc['v'] = [];
        $this->misc['v']['id'] = $this->id;
        $this->em = $this->sm->get('Doctrine\ORM\EntityManager');
        $this->translator = $this->sm->get('ViewHelperManager')->get('translate');

        $this->viewModel = new ViewModel();
    }

//Dispatch előtt megnézzük, hogy van-e ilyen tartalom
    public function onDispatch(\Zend\Mvc\MvcEvent $e)
    {
        //0 = új tartalom
        if (empty($this->repository->find($this->id))) {
            $this->getEvent()->getViewModel()->getVariable('statusMessages')->addMessage('Nincs ilyen tartalom!', 'error');
            $this->getResponse()->setStatusCode(404);
            return;
        }
        return parent::onDispatch($e);
    }


    protected function _baseDataRetrieve()
    {

        $this->viewModel->setTemplate('catalog/base-data/_base-data.phtml');
        $this->viewModel->setVariables(array(
            'v' => $this->misc['v'],
        ));

        return $this->viewModel;

    }


    public function ingredientAction()
    {
        $content = $this->repository->find($this->id);
        $this->misc['v']['headerData'] = [
            'id' => $this->translator->__invoke('Id'),
            'name' => $this->translator->__invoke('Név'),
            'ingredientGroup' => $this->translator->__invoke('Csoport'),
            'ingredientUnit' => $this->translator->__invoke('Alapanyag mértékegység'),
            'minimumAmount' => $this->translator->__invoke('Minimum mennyiség'),
            'moreInfo' => $this->translator->__invoke('Egyéb')];

        $this->misc['v']['datas'] = [
            'id' => $content->getId(),
            'name' => $content->getName(),
            'ingredientGroup' => '<a href="' . $this->url()->fromRoute('basedata', ['action' => 'ingredientgroup', 'id' => $content->getIngredientGroup()->getId()]) . '">' . $content->getIngredientGroup()->getName() . '</a>',
            'ingredientUnit' => $content->getIngredientUnit()->getName(),
            'minimumAmount' => $this->sm->get('nf')->nf($content->getMinimumAmount(), true) . ' ' . $content->getIngredientUnit()->getShortName(),
            'moreInfo' => $content->getMoreInfo(),
        ];


        $this->misc['v']['baseDataType'] = 'ingredient';
        $this->misc['v']['title'] = $this->translator->__invoke('Alapanyagok');
        $this->misc['v']['moreInfo'] = '';

        return $this->_baseDataRetrieve();

    }

    public function ingredientgroupAction()
    {

        $content = $this->repository->find($this->id);
        $this->misc['v']['headerData'] = [
            'id' => $this->translator->__invoke('Id'),
            'name' => $this->translator->__invoke('Név')
        ];

        $this->misc['v']['datas'] = [
            'id' => $content->getId(),
            'name' => $content->getName(),
        ];

        $this->misc['v']['baseDataType'] = 'ingredientgroup';
        $this->misc['v']['title'] = $this->translator->__invoke('Alapanyagokcsoportok');
        $this->misc['v']['moreInfo'] = '';

        return $this->_baseDataRetrieve();

    }

    public function ingredientunitAction()
    {

        $content = $this->repository->find($this->id);
        $this->misc['v']['headerData'] = [
            'id' => $this->translator->__invoke('Id'),
            'name' => $this->translator->__invoke('Név'),
            'shortName' => $this->translator->__invoke('Rövid név'),
            'isDecimal' => $this->translator->__invoke('Tizedesek?')
        ];

        $this->misc['v']['datas'] = [
            'id' => $content->getId(),
            'name' => $content->getName(),
            'shortName' => $content->getShortName(),
            'isDecimal' => (($content->getIsDecimal()) ? $this->translator->__invoke('Igen') : $this->translator->__invoke('Nem')),

        ];

        $this->misc['v']['baseDataType'] = 'ingredientunit';
        $this->misc['v']['title'] = $this->translator->__invoke('Alapanyag mértékegységek');
        $this->misc['v']['moreInfo'] = '';

        return $this->_baseDataRetrieve();

    }

    public function toolAction()
    {
        $content = $this->repository->find($this->id);
        $this->misc['v']['headerData'] = [
            'id' => $this->translator->__invoke('Id'),
            'name' => $this->translator->__invoke('Név'),
            'toolGroup' => $this->translator->__invoke('Csoport'),
            'toolUnit' => $this->translator->__invoke('Eszköz mértékegység'),
            'moreInfo' => $this->translator->__invoke('Egyéb')];

        $this->misc['v']['datas'] = [
            'id' => $content->getId(),
            'name' => $content->getName(),
            'toolGroup' => '<a href="' . $this->url()->fromRoute('basedata', ['action' => 'toolgroup', 'id' => $content->getToolGroup()->getId()]) . '">' . $content->getToolGroup()->getName() . '</a>',
            'toolUnit' => $content->getToolUnit()->getName(),
            'moreInfo' => $content->getMoreInfo(),
        ];


        $this->misc['v']['baseDataType'] = 'tool';
        $this->misc['v']['title'] = $this->translator->__invoke('Eszközök');
        $this->misc['v']['moreInfo'] = '';

        return $this->_baseDataRetrieve();

    }

    public function toolgroupAction()
    {

        $content = $this->repository->find($this->id);
        $this->misc['v']['headerData'] = [
            'id' => $this->translator->__invoke('Id'),
            'name' => $this->translator->__invoke('Név')
        ];

        $this->misc['v']['datas'] = [
            'id' => $content->getId(),
            'name' => $content->getName(),
        ];

        $this->misc['v']['baseDataType'] = 'toolgroup';
        $this->misc['v']['title'] = $this->translator->__invoke('Eszközcsoportok');
        $this->misc['v']['moreInfo'] = '';

        return $this->_baseDataRetrieve();

    }

    public function toolunitAction()
    {

        $content = $this->repository->find($this->id);
        $this->misc['v']['headerData'] = [
            'id' => $this->translator->__invoke('Id'),
            'name' => $this->translator->__invoke('Név'),
            'shortName' => $this->translator->__invoke('Rövid név'),
            'isDecimal' => $this->translator->__invoke('Tizedesek?')
        ];

        $this->misc['v']['datas'] = [
            'id' => $content->getId(),
            'name' => $content->getName(),
            'shortName' => $content->getShortName(),
            'isDecimal' => (($content->getIsDecimal()) ? $this->translator->__invoke('Igen') : $this->translator->__invoke('Nem')),

        ];

        $this->misc['v']['baseDataType'] = 'toolunit';
        $this->misc['v']['title'] = $this->translator->__invoke('Eszköz mértékegységek');
        $this->misc['v']['moreInfo'] = '';

        return $this->_baseDataRetrieve();

    }

    public function moneyAction()
    {
        $content = $this->repository->find($this->id);
        $this->misc['v']['headerData'] = [
            'id' => $this->translator->__invoke('Id'),
            'name' => $this->translator->__invoke('Név'),
            'moneyGroup' => $this->translator->__invoke('Pénzeszköz csoport'),
            'moneyUnit' => $this->translator->__invoke('Pénznem'),
            'moreInfo' => $this->translator->__invoke('Egyéb')];

        $this->misc['v']['datas'] = [
            'id' => $content->getId(),
            'name' => $content->getName(),
            'moneyGroup' => '<a href="' . $this->url()->fromRoute('basedata', ['action' => 'moneygroup', 'id' => $content->getMoneyGroup()->getId()]) . '">' . $content->getMoneyGroup()->getName() . '</a>',
            'moneyUnit' => $content->getMoneyUnit()->getName(),
            'moreInfo' => $content->getMoreInfo(),
        ];


        $this->misc['v']['baseDataType'] = 'money';
        $this->misc['v']['title'] = $this->translator->__invoke('Eszközök');
        $this->misc['v']['moreInfo'] = '';

        return $this->_baseDataRetrieve();

    }

    public function moneygroupAction()
    {

        $content = $this->repository->find($this->id);
        $this->misc['v']['headerData'] = [
            'id' => $this->translator->__invoke('Id'),
            'name' => $this->translator->__invoke('Név')
        ];

        $this->misc['v']['datas'] = [
            'id' => $content->getId(),
            'name' => $content->getName(),
        ];

        $this->misc['v']['baseDataType'] = 'moneygroup';
        $this->misc['v']['title'] = $this->translator->__invoke('Pénzeszköz csoportok');
        $this->misc['v']['moreInfo'] = '';

        return $this->_baseDataRetrieve();

    }

    public function moneyunitAction()
    {

        $content = $this->repository->find($this->id);
        $this->misc['v']['headerData'] = [
            'id' => $this->translator->__invoke('Id'),
            'name' => $this->translator->__invoke('Név'),
            'shortName' => $this->translator->__invoke('Rövid név'),
            'isDecimal' => $this->translator->__invoke('Tizedesek?'),
        ];

        $this->misc['v']['datas'] = [
            'id' => $content->getId(),
            'name' => $content->getName(),
            'shortName' => $content->getShortName(),
            'isDecimal' => (($content->getIsDecimal()) ? $this->translator->__invoke('Igen') : $this->translator->__invoke('Nem')),

        ];

        $this->misc['v']['baseDataType'] = 'moneyunit';
        $this->misc['v']['title'] = $this->translator->__invoke('Pénznemek');
        $this->misc['v']['moreInfo'] = '';

        return $this->_baseDataRetrieve();

    }

    public function productAction()
    {

        $content = $this->repository->find($this->id);

        $ingredients = $this->repository->getIngredientsOfProductForBaseData($this->id);

        $this->misc['v']['headerData'] = [
            'id' => $this->translator->__invoke('Id'),
            'name' => $this->translator->__invoke('Név'),
            'price' => $this->translator->__invoke('Ár'),
            'isActive' => $this->translator->__invoke('Aktív?'),
            'shortName' => $this->translator->__invoke('Rövid név'),
            'productGroup' => $this->translator->__invoke('Termékcsoport'),
            'ingredient' => $this->translator->__invoke('Alapanyagok'),
            'vatGroup' => $this->translator->__invoke('ÁFA csoport'),
            'prescription' => $this->translator->__invoke('Recept'),
            'moreInfo' => $this->translator->__invoke('Egyéb'),
        ];

        $ingredientsList = '<table>';
        foreach ($ingredients as $ingr) {
            $ingredientsList .= '<tr><td class="ingredient-list">' . $ingr['ingredientProductAmount'] . ' ' . $ingr['ingredientUnitShortName'] . '</td><td> <a href="' . $this->url()->fromRoute('basedata', ['action' => 'ingredient', 'id' => $ingr['ingredientId']]) . '">' . $ingr['ingredientName'] . '</a></td></tr>';
        }
        $ingredientsList .= '</table>';

        $this->misc['v']['datas'] = [
            'id' => $content->getId(),
            'name' => $content->getName(),
            'price' => $this->sm->get('ViewHelperManager')->get('money')->__invoke($content->getPrice()),
            'isActive' => (($content->getIsActive()) ? $this->translator->__invoke('Igen') : $this->translator->__invoke('Nem')),
            'shortName' => $content->getShortName(),
            'productGroup' => '<a href="' . $this->url()->fromRoute('basedata', ['action' => 'productgroup', 'id' => $content->getProductGroup()->getId()]) . '">' . $content->getProductGroup()->getName() . '</a>',
            'ingredient' => $ingredientsList,
            'vatGroup' => $content->getVatGroup()->getName(),
            'prescription' => $content->getPrescription(),
            'moreInfo' => $content->getMoreInfo(),
        ];


        $this->misc['v']['baseDataType'] = 'product';
        $this->misc['v']['title'] = $this->translator->__invoke('Termék');
        $this->misc['v']['moreInfo'] = '';

        return $this->_baseDataRetrieve();

    }

    public function productgroupAction()
    {

        $content = $this->repository->find($this->id);
        $this->misc['v']['headerData'] = [
            'id' => $this->translator->__invoke('Id'),
            'name' => $this->translator->__invoke('Név')
        ];

        $this->misc['v']['datas'] = [
            'id' => $content->getId(),
            'name' => $content->getName(),
        ];

        $this->misc['v']['baseDataType'] = 'productgroup';
        $this->misc['v']['title'] = $this->translator->__invoke('Termék csoportok');
        $this->misc['v']['moreInfo'] = '';

        return $this->_baseDataRetrieve();

    }

    public function storageAction()
    {

        $content = $this->repository->find($this->id);
        $this->misc['v']['headerData'] = [
            'id' => $this->translator->__invoke('Id'),
            'name' => $this->translator->__invoke('Név'),
            'parentStorage' => $this->translator->__invoke('Szülő tároló'),
            'storageType' => $this->translator->__invoke('Tároló típus'),
            'supplier' => $this->translator->__invoke('Cég/beszállító'),
        ];

        $parentStorage = (!empty($content->getParentStorage())) ?
            '<a href="' . $this->url()->fromRoute('basedata', ['action' => 'storage', 'id' => $content->getParentStorage()->getId()]) . '">' . $content->getParentStorage()->getName() . '</a>'
            : $this->translator->__invoke('NINCS');

        $this->misc['v']['datas'] = [
            'id' => $content->getId(),
            'name' => $content->getName(),
            'storageType' => '<a href="' . $this->url()->fromRoute('basedata', ['action' => 'storagetype', 'id' => $content->getStorageType()->getId()]) . '">' . $content->getStorageType()->getName() . '</a>',
            'parentStorage' => $parentStorage,
            'supplier' => '<a href="' . $this->url()->fromRoute('basedata', ['action' => 'supplier', 'id' => $content->getSupplier()->getId()]) . '">' . $content->getSupplier()->getName() . '</a>',
        ];


        $this->misc['v']['baseDataType'] = 'storage';
        $this->misc['v']['title'] = $this->translator->__invoke('Tárolók');
        $this->misc['v']['moreInfo'] = '';
        return $this->_baseDataRetrieve();

    }

    public function storagetypeAction()
    {

        $content = $this->repository->find($this->id);
        $this->misc['v']['headerData'] = [
            'id' => $this->translator->__invoke('Id'),
            'name' => $this->translator->__invoke('Név'),
            'stringId' => $this->translator->__invoke('Azonosító'),
            'elements' => $this->translator->__invoke('Elemek'),
            'isRealStorageType' => $this->translator->__invoke('Elemek mennyiségének nyilvántartása?'),
        ];

        $stuffes = $this->repository->getStuffesOfStorageTypeForBaseData($this->id);
        $stuffesList = '<table>';
        foreach ($stuffes as $stuff) {
            $stuffesList .= '<tr><td class="ingredient-list">' . $stuff->getName() . '</td></tr>';
        }
        $stuffesList .= '</table>';

        $this->misc['v']['datas'] = [
            'id' => $content->getId(),
            'name' => $content->getName(),
            'stringId' => $content->getStringId(),
            'elements' => $stuffesList,
            'isRealStorageType' => (($content->getIsRealStorageType()) ? $this->translator->__invoke('Igen') : $this->translator->__invoke('Nem')),

        ];

        $this->misc['v']['baseDataType'] = 'storagetype';
        $this->misc['v']['title'] = $this->translator->__invoke('Tároló típusok');
        $this->misc['v']['moreInfo'] = '';

        return $this->_baseDataRetrieve();

    }

    public function supplierAction()
    {
        $content = $this->repository->find($this->id);

        $this->misc['v']['headerData'] = [
            'id' => $this->translator->__invoke('Id'),
            'name' => $this->translator->__invoke('Név'),
            'taxNumber' => $this->translator->__invoke('Adószám'),
            'contactPerson' => $this->translator->__invoke('Referens'),
            'telNumber' => $this->translator->__invoke('Telefon'),
            'email' => $this->translator->__invoke('Email'),
            'seat' => $this->translator->__invoke('Székhely'),
            'site' => $this->translator->__invoke('Telephely'),
            'moreInfo' => $this->translator->__invoke('Egyéb'),
        ];

        $this->misc['v']['datas'] = [
            'id' => $content->getId(),
            'name' => $content->getName(),
            'taxNumber' => $content->getTaxNumber(),
            'contactPerson' => $content->getContactPerson(),
            'telNumber' => $content->getTelNumber(),
            'email' => $content->getEmail(),
            'seat' => $content->getSeat(),
            'site' => $content->getSite(),
            'moreInfo' => $content->getMoreInfo(),
        ];


        $this->misc['v']['baseDataType'] = 'supplier';
        $this->misc['v']['title'] = $this->translator->__invoke('Beszállítók');
        $this->misc['v']['moreInfo'] = '';

        return $this->_baseDataRetrieve();

    }

    public function vatgroupAction()
    {

        $content = $this->repository->find($this->id);
        $this->misc['v']['headerData'] = [
            'id' => $this->translator->__invoke('Id'),
            'name' => $this->translator->__invoke('Név'),
            'vatValue' => $this->translator->__invoke('ÁFA érték')
        ];

        $this->misc['v']['datas'] = [
            'id' => $content->getId(),
            'name' => $content->getName(),
            'vatValue' => ($content->getVatValue() * 100) . '%',
        ];

        $this->misc['v']['baseDataType'] = 'vatgroup';
        $this->misc['v']['title'] = $this->translator->__invoke('ÁFA kategóriák');

        return $this->_baseDataRetrieve();

    }


}