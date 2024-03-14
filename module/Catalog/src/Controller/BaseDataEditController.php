<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Catalog\Controller;

use Application\Service\Crud\DeleteEntityItems;
use Catalog\Model\BaseData\BaseDataEditControllerModel;
use Catalog\Service\BaseDataEdit\BaseDataEditService;
use Catalog\Service\BaseDataEdit\ProductDataEditService;
use Catalog\Service\BaseDataEdit\StorageTypeDataEditService;
use Doctrine\ORM\EntityManager;
use User\Service\RegisteredService\UserAuthentication;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


/**
 * Ez a controller a törzsadatok Create, Updatem, Delete-ért felelős, a delete kivétel, mert ott tömbbe is kaphatjuk
 * a content ID-kat, tehát csoportosan is törölhetünk, a Factoryjában állítjuk össze az egyes törzsadatokhoz szükséges
 * objektumokat => Form (filedset), Entity, Repository stb és CRUD műveletes osztály (BaseDataEditService) az
 * ManipulateEntityByForm (Application)-ből örökölve ami végzi az effektív művleteket
 *
 * Class BaseDataEditController
 * @package Catalog\Controller
 */
class BaseDataEditController extends AbstractActionController
{

    private $sm;
    private $misc;
    private $id;
    private $modifyDbByForm;
    private $viewModel;
    private $actualUserId;
    private $entityName;
    private $form;
    private $entity;
    private $repository;
    private $deleteService;
    private $actionName;

    public function __construct($sm, BaseDataEditControllerModel $controllerModel)
    {
        $this->sm = $sm;
        $this->form = $controllerModel->getForm();
        $this->misc = $controllerModel->getMisc();
        $this->misc['v'] = []; //a viesnek szánt adaotk
        $this->id = $controllerModel->getId();
        $this->getAc = $controllerModel->getEntity();
        $this->entity = $controllerModel->getEntity();
        $this->actionName = $controllerModel->getActionName();
        $this->repository = $controllerModel->getRepository();
        $this->entityName = substr(strrchr(get_class($this->entity), "\\"), 1);
        $this->modifyDbByForm = $controllerModel->getModifyDbByFormService();
        $this->deleteService = $controllerModel->getDeleteFromDbService();
        $this->actualUserId = $this->sm->get(UserAuthentication::class)->getIdentity()->getId();
        $this->viewModel = new ViewModel();

        //alapesetben nincs more info az egyes actionökben felül lehet írni
    }

    //Dispatch előtt megnézzük, hogy van-e ilyen tartalom
    public function onDispatch(\Zend\Mvc\MvcEvent $e)
    {

        if (($this->id !== 'delete') && ($this->id != 'new') && (empty($this->repository->find($this->id)))) {
            $this->getEvent()->getViewModel()->getVariable('statusMessages')->addMessage('Nincs ilyen tartalom!', 'error');
            $this->getResponse()->setStatusCode(404);
            return;
        }

        //ha delete van akkor nem is kell dispatcholni, hanem csak meghívni a fg
        //ha esetleg speck fg kell vmelyik base datához, akkor meg azt kell beparaméterezni a deleteService-be
        if ($this->id === 'delete') {
            $this->deleteContent();
        }

        return parent::onDispatch($e);
    }


    /**
     * a Actionöknél úgy van, hogy mielőtt meghívják ezt a fg-t, addig tudák módosítani egyéb servicekkell az adatokat!
     * és a misc-be tenni egyéb cuccokat
     * @return bool|void|ViewModel
     */

    protected function stockDataModifyCommon()
    {
        /** @var BaseDataEditService $modifyDb */
        $prg = $this->prg();
        if ($prg instanceof \Zend\Http\PhpEnvironment\Response) return $prg; //PRG TRÜKK

        $postData = $prg;
        $msg = $this->getEvent()->getViewModel()->getVariable('statusMessages');

        //Ha VAN POST
        if (!empty($postData)) {
            try {
                $modifyDb = $this->modifyDbByForm;
                $modifyDb->setContentId($this->id);
                $modifyDb->setForm($this->form);
                $modifyDb->setPostData($postData);
                $modifyDb->setEntity($this->entity);
                $modifyDb->setRepository($this->repository);
                $modifyDb->isValid();
                $modifiedId = $modifyDb->manipulateDb();
                $this->flashmessenger()->addMessage('Sikeres művelet, az adatok bekerültek az adatbázisba!', 'success');
                return $this->redirect()->toRoute('basedata', ['action' => $this->actionName, 'id' => $modifiedId]);
            } catch (\Exception $e) {
                $msg->addMessage($e->getMessage(), 'error');
            }


            $this->form = $modifyDb->getForm();
        }


        //HA NINCS POST
        if ($postData === false) {
            $this->repository = $this->repository->find($this->id);
            if (!empty($this->repository)) {
                $this->misc['v']['title'] = '"' . $this->repository->getName() . '" módosítása';
                $this->form->get('submit')->setValue('Módosítás');

                $this->form->bind($this->repository);
            } else {
                $this->form->get('submit')->setValue('Feltöltés');
                $this->misc['v']['title'] = 'Új ' . $this->misc['v']['title'];
            }
        }

        $this->form->prepare();

        $this->viewModel->setVariables(array(
            'form' => $this->form,
            'v' => $this->misc['v'],
        ));

        return $this->viewModel;
    }





    public function ingredientAction()
    {
        //itt felül tudom írni a misc-eket is!
        $this->misc['v']['title'] = 'alapanyag';
        $this->viewModel->setTemplate('catalog/base-data-edit/_base-data-edit.phtml');
        return $this->stockDataModifyCommon();

    }

    public function ingredientgroupAction()
    {
        //itt felül tudom írni a misc-eket is!
        $this->misc['v']['title'] = 'alapanyag csoport';
        $this->viewModel->setTemplate('catalog/base-data-edit/_base-data-edit.phtml');
        return $this->stockDataModifyCommon();

    }

    public function ingredientunitAction()
    {
        //itt felül tudom írni a misc-eket is!
        $this->misc['v']['title'] = 'alapanyag mértékegység';
        $view = $this->stockDataModifyCommon();
        $view->setTemplate('catalog/base-data-edit/_base-data-edit.phtml');
        return $view;

    }

    public function toolAction()
    {
        //itt felül tudom írni a misc-eket is!
        $this->misc['v']['title'] = 'eszköz';
        $this->viewModel->setTemplate('catalog/base-data-edit/_base-data-edit.phtml');
        return $this->stockDataModifyCommon();

    }

    public function toolgroupAction()
    {
        //itt felül tudom írni a misc-eket is!
        $this->misc['v']['title'] = 'eszköz csoport';
        $this->viewModel->setTemplate('catalog/base-data-edit/_base-data-edit.phtml');
        return $this->stockDataModifyCommon();

    }

    public function toolunitAction()
    {
        //itt felül tudom írni a misc-eket is!
        $this->misc['v']['title'] = 'eszköz mértékegység';
        $view = $this->stockDataModifyCommon();
        $view->setTemplate('catalog/base-data-edit/_base-data-edit.phtml');
        return $view;

    }


    public function moneyAction()
    {
        //itt felül tudom írni a misc-eket is!
        $this->misc['v']['title'] = 'pénzeszköz';
        $this->viewModel->setTemplate('catalog/base-data-edit/_base-data-edit.phtml');
        return $this->stockDataModifyCommon();

    }

    public function moneygroupAction()
    {
        //itt felül tudom írni a misc-eket is!
        $this->misc['v']['title'] = 'pénzeszköz csoport';
        $this->viewModel->setTemplate('catalog/base-data-edit/_base-data-edit.phtml');
        return $this->stockDataModifyCommon();

    }

    public function moneyunitAction()
    {
        //itt felül tudom írni a misc-eket is!
        $this->misc['v']['title'] = 'pénznem';
        $view = $this->stockDataModifyCommon();
        $view->setTemplate('catalog/base-data-edit/_base-data-edit.phtml');
        return $view;

    }



    public function productAction()
    {
        //TODO: #141 ezt is meg lehet csinálni díszítő patternben, mint az InventoryTransactiontService
        $this->misc['v']['title'] = 'termék';
        /** @var ProductDataEditService $productModify */
        $prg = $this->prg();
        if ($prg instanceof \Zend\Http\PhpEnvironment\Response) return $prg; //PRG TRÜKK

        $postData = $prg;
        $msg = $this->getEvent()->getViewModel()->getVariable('statusMessages');

        $productModify = $this->modifyDbByForm;
        $productModify->setSm($this->sm);
        $productModify->setContentId($this->id);
        //Ha VAN POST
        //TODO: #142 try catchel mint a
        if (!empty($postData)) {
            try {
                $productModify->setForm($this->form);
                $productModify->setPostData($postData);
                $productModify->setEntity($this->entity);
                $productModify->setRepository($this->repository);
                $productModify->isValid();
                $modifiedProductId = $productModify->manipulateDb();
                $productModify->setContentId($modifiedProductId);

                $productModify->isValidIngredient();
                $productModify->insertIngredientProduct();

                $this->flashmessenger()->addMessage('Sikeres művelet, az adatok bekerültek az adatbázisba!', 'success');
                return $this->redirect()->toRoute('basedata', ['action' => $this->actionName, 'id' => $modifiedProductId]);
            } catch (\Exception $e) {
                $msg->addMessage($e->getMessage(), 'error');
            }


            $this->form = $productModify->getForm();
        }


        //HA NINCS POST
        if ($postData === false) {
            $this->repository = $this->repository->find($this->id);

            if (!empty($this->repository)) {
                $this->misc['v']['title'] = '"' . ucfirst($this->repository->getName()) . '" módosítása';
                $this->form->bind($this->repository);
                $this->form->get('submit')->setValue('Módosítás');
            } else {
                $this->form->get('submit')->setValue('Feltöltés');
                $this->misc['v']['title'] = 'Új ' . $this->misc['v']['title'];
            }

        }

        $this->misc['v']['allIngredients'] = $productModify->getAllIngredient();
        $this->misc['v']['containingIngredientIds'] = $productModify->getContainingIngredientId();
        $this->form->prepare();
        $this->viewModel->setVariables(array(
            'action' => $this->getEvent()->getRouteMatch()->getParam('action'),
            'form' => $this->form,
            'id' => $this->id,
            'v' => $this->misc['v'],
        ));

        return $this->viewModel;

    }


    public function productgroupAction()
    {
        $this->misc['v']['title'] = 'termék csoport';
        //itt felül tudom írni a misc-eket is!
        $view = $this->stockDataModifyCommon();
        $view->setTemplate('catalog/base-data-edit/_base-data-edit.phtml');
        return $view;

    }


    public function storageAction()
    {
        $this->misc['v']['title'] = 'tároló';
        //itt felül tudom írni a misc-eket is!
        $view = $this->stockDataModifyCommon();
        $view->setTemplate('catalog/base-data-edit/_base-data-edit.phtml');
        return $view;

    }

    public function storagetypeAction()
    {

        //TODO: #141 ezt is meg lehet csinálni díszítő patternben, mint az InventoryTransactiontService
        $this->misc['v']['title'] = 'tároló típus';
        /** @var StorageTypeDataEditService $storageTypeModify */
        $prg = $this->prg();
        if ($prg instanceof \Zend\Http\PhpEnvironment\Response) return $prg; //PRG TRÜKK

        $postData = $prg;
        $msg = $this->getEvent()->getViewModel()->getVariable('statusMessages');

        $storageTypeModify = $this->modifyDbByForm;
        $storageTypeModify->setSm($this->sm);
        $storageTypeModify->setContentId($this->id);
        //Ha VAN POST
        //TODO: #142 try catchel mint a
        if (!empty($postData)) {
            try {
                $storageTypeModify->setForm($this->form);
                $storageTypeModify->setPostData($postData);
                $storageTypeModify->setEntity($this->entity);
                $storageTypeModify->setRepository($this->repository);
                $storageTypeModify->isValid();
                $modifiedStorageTypeId = $storageTypeModify->manipulateDb();
                $storageTypeModify->setContentId($modifiedStorageTypeId);

                $storageTypeModify->isValidStuff();
                $storageTypeModify->insertStuffInStorageType();

                $this->flashmessenger()->addMessage('Sikeres művelet, az adatok bekerültek az adatbázisba!', 'success');
                return $this->redirect()->toRoute('basedata', ['action' => $this->actionName, 'id' => $modifiedStorageTypeId]);
            } catch (\Exception $e) {
                $msg->addMessage($e->getMessage(), 'error');
            }

            $this->form = $storageTypeModify->getForm();
        }


        //HA NINCS POST
        if ($postData === false) {
            $this->repository = $this->repository->find($this->id);

            if (!empty($this->repository)) {
                $this->misc['v']['title'] = '"' . ucfirst($this->repository->getName()) . '" módosítása';
                $this->form->bind($this->repository);
                $this->form->get('submit')->setValue('Módosítás');
            } else {
                $this->form->get('submit')->setValue('Feltöltés');
                $this->misc['v']['title'] = 'Új ' . $this->misc['v']['title'];
            }

        }

        $this->misc['v']['allStuff'] = $storageTypeModify->getAllStuff();
        $this->misc['v']['containingStuffIds'] = $storageTypeModify->getContainingStuffId();
        $this->form->prepare();
        $this->viewModel->setVariables(array(
            'action' => $this->getEvent()->getRouteMatch()->getParam('action'),
            'form' => $this->form,
            'id' => $this->id,
            'v' => $this->misc['v'],
        ));

        return $this->viewModel;

    }

    public function supplierAction()
    {
        $this->misc['v']['title'] = 'beszállító';
        //itt felül tudom írni a misc-eket is!
        $view = $this->stockDataModifyCommon();
        $view->setTemplate('catalog/base-data-edit/_base-data-edit.phtml');
        return $view;

    }

    public function vatgroupAction()
    {
        $this->misc['v']['title'] = 'ÁFA csoport';
        //itt felül tudom írni a misc-eket is!
        $view = $this->stockDataModifyCommon();
        $view->setTemplate('catalog/base-data-edit/_base-data-edit.phtml');
        return $view;

    }

    /**
     * általános feldolgozó: tömbbe kapjuk a viewtől az ID-kat, amiket foreachhel törünk a db-ből!
     */
    protected function deleteContent()
    {
        $prg = $this->prg();
        if ($prg instanceof \Zend\Http\PhpEnvironment\Response) return $prg; //PRG TRÜKK
        $postData = $prg;
        $msg = $this->getEvent()->getViewModel()->getVariable('statusMessages');
        $baseDataType = $this->actionName;

        if (empty($postData[$baseDataType])) {
            $msg->addMessage('Nincs kijelölve tartalom a törléshez!', 'error');
            $this->getResponse()->setStatusCode(404);
            return;
        }

        /** @var DeleteEntityItems $deleteService */
        $deleteService = $this->deleteService;
        $deleteService->setPostData($postData);
        $deleteService->setRepository($this->repository);
        $deleteService->setEntityName($baseDataType);
        $deleteService->setEm($this->sm->get(EntityManager::class));
        $deleteService->setActualUserId($this->actualUserId);


        if ($deleteService->allElementsExist() !== true) {
            $this->flashmessenger()->addMessage('Hiba lépett fel a feldolgozás során!');
            return $this->redirect()->toRoute('basedata', ['action' => $baseDataType]);
        }

        $deleteService->deleteFromDb();

        if (!empty($deleteService->getCantDeleteElements())) {
            $errorElements = implode(" ", $deleteService->getCantDeleteElements());
            $this->flashmessenger()->addMessage("Ezeket az elemeket nem sikerült törölni, mert még függnek tőlük más adatok:<br>" . $errorElements, 'warning');
        }
        if (!empty($deleteService->getSuccessDeleteElements())) {
            $successElements = implode(" ", $deleteService->getSuccessDeleteElements());
            $this->flashmessenger()->addMessage("Ezek az elemek sikeresen törlődtek az adatbázisból:<br>" . $successElements, 'success');
        }

        return $this->redirect()->toRoute('basedatacollection', ['action' => $baseDataType]);

    }


}