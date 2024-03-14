<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Catalog\Controller;

use Application\Model\RetrieveByDatatableModel;
use Application\Service\Log\EventLogService;
use Catalog\Entity\Ingredient;
use Catalog\Entity\Stock;
use Catalog\Entity\StockTransactionType;
use Catalog\Entity\Storage;
use Catalog\Model\StockTransaction\IngredientTransactionEventModel;
use Catalog\Service\StockTransaction\IngredientTransactionService;
use Doctrine\ORM\EntityManager;
use User\Service\RegisteredService\UserAuthentication;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class IngredientTransactionController
 * @package Catalog\Controller
 * @property IngredientTransactionEventModel $transactionModel
 * @property IngredientTransactionService $ingrTransactionService
 * @property RetrieveByDatatableModel $datatableModel
 */
class IngredientTransactionController extends AbstractActionController
{

    /** @var $ingrTransactionService \Catalog\Service\StockTransaction\IStockTransactionService */
    private $sm;
    private $ingrTransactionService;
    private $msg;
    private $actionName;
    private $datatableModel;
    private $from;
    private $to;
    private $tableSelectable = 'select';
    private $selectableLabel = 'Összes elem mutatása';
    private $selectableId = 1;

    public function __construct($sm, $misc)
    {
        $this->sm = $sm;
        $this->em = $this->sm->get(EntityManager::class);
        $this->ingrTransactionService = $misc['ingrTransactionService'];
        $this->datatableModel = $misc['showWithDatatable'];
        $this->actionName = $misc['actionName'];
        $this->viewModel = new ViewModel();
        $this->form = $misc['form'];
        $this->actualUserId = $this->sm->get(UserAuthentication::class)->getIdentity()->getId();
    }


    //Dispatch előtt megnézzük, hogy van-e ilyen tartalom
    public function onDispatch(\Zend\Mvc\MvcEvent $e)
    {
        $this->msg = $this->getEvent()->getViewModel()->getVariable('statusMessages');
        $this->from = (int)$this->params()->fromRoute('from');
        $this->to = (int)$this->params()->fromRoute('to');

        if (empty($this->em->getRepository(Storage::class)->find($this->from)) ||
            empty($this->em->getRepository(Storage::class)->find($this->to))
        ) {
            $this->msg->addMessage('A megadott tárolók nem helyesek', 'error');
            $this->getResponse()->setStatusCode(404);
            return;
        }
        return parent::onDispatch($e);
    }


    //LEHET ITT A KÜLÖNBÖZŐ ACTIONÖKNÉL FELDOLGOZNI PL A BIZONYLATKOMPATIBILIS ADATOKAT STB
    //De alapból egy-egy datatable fog megjelenni a FROM storage stockja
    protected function stockTransaction()
    {
        /** @var $transactionService \Catalog\Service\StockTransaction\IStockTransactionService */
        $postData = $this->prg();
        if ($postData instanceof \Zend\Http\PhpEnvironment\Response) return $postData; //PRG TRÜKK

        //Ha VAN POST
        if (!empty($postData)) {
            if ($this->from != $postData['StockTransaction']['fromStorage'] || ($this->to != $postData['StockTransaction']['toStorage'])) {
                $this->flashMessenger()->addMessage('Nem helyes a tárolók Id-je!', 'error');
                return $this->redirect()->refresh();
            }

            try {
                $this->form->setData($postData);
                $this->ingrTransactionService->setPostData($postData);
                $this->ingrTransactionService->adjustTransactionEventModel();
                $this->ingrTransactionService->setEventManager($this->getEventManager());
                $this->ingrTransactionService->triggerStockTransactionEvent();

                $this->sm->get(EventLogService::class)->__invoke('Sikeres készletművelet: ' . $this->actionName, 100);

                $this->flashMessenger()->addMessage('A tranzakció sikeres volt!', 'success');
                return $this->redirect()->refresh();
            } catch (\Exception $e) {
                error_log($e);
                $this->flashMessenger()->addMessage($e->getMessage(), 'error');
                return $this->redirect()->refresh();

            }
        }

        //HA NINCS POST ÁLLÍTSUK BE A DATATABLET
        //TODO: #144 megcsinálni service-ben
        if ($postData === false) {
            if (!empty($selectableGet = (int)$this->params()->fromQuery('selectable'))) {
                if ($selectableGet === 1) {
                    $this->tableSelectable = '';
                    $this->selectableLabel = 'Egyenkénti kiválasztás';
                    $this->selectableId = 2;
                }
                if ($selectableGet === 2) {
                    $this->tableSelectable = 'select';
                    $this->selectableLabel = 'Összes elem mutatása';
                    $this->selectableId = 1;
                }
            }

            $inputs = [['inputType' => 'number', 'inputName' => 'amount', 'whichColumn' => 2, 'valueColumn' => null]];

            $header = ['Id', 'Alapanyag'];
            $datas = [];
            //ha valódi stock, akkor csak a saját alapanyagait bontsuk ki
            if (!empty($this->em->getRepository(Storage::class)->find($this->from)->getStorageType()->getRealStorageType())) {
                foreach ($this->em->getRepository(Stock::class)->getIngredientsAmount($this->from) as $stockRow) {
                    $datas[] = [
                        $stockRow->getIngredient()->getId(),
                        $stockRow->getIngredient()->getName(),
                        $stockRow->getAmount() . ' ' . $stockRow->getIngredient()->getIngredientUnit()->getShortName() . '<input type="hidden" class="stock-amount" id="ingr-' . $stockRow->getIngredient()->getId() . '" value="' . $stockRow->getAmount() . '">',
                    ];
                }
                $inputs[0]['whichColumn'] = 3;
                $header[] = 'Készleten';
            } else {
                foreach ($this->em->getRepository(Ingredient::class)->findAll() as $ingrRow) {
                    $datas[] = [
                        $ingrRow->getId(),
                        $ingrRow->getName(),
                    ];
                }
            }

            $header[] = 'Mennyiség';

            $this->datatableModel->setName($this->actionName);
            $this->datatableModel->setAction([['name' => 'Jóváhagyás', 'actionUrl' => '', 'warningText' => 'Biztos, hogy végrehajtja a műveletet?', 'icon' => 'ok']]);
            $this->datatableModel->setOrderColumn(2);
            $this->datatableModel->setData($datas);
            $this->datatableModel->setHeader($header);
            $this->datatableModel->setInput($inputs);

            if (!empty($this->tableSelectable)) $this->datatableModel->setSelectable($this->tableSelectable);

            $this->form->get('StockTransaction')->get('stockTransactionType')->setValue($this->em->getRepository(StockTransactionType::class)->findBy(['stringId' => $this->actionName])[0]->getId());
            $this->form->get('StockTransaction')->get('fromStorage')->setValue($this->from);
            $this->form->get('StockTransaction')->get('toStorage')->setValue($this->to);
            $this->form->get('StockTransaction')->get('user')->setValue($this->actualUserId);

            $this->form->prepare();
        }

        $this->viewModel->setTemplate('catalog/ingr-transaction/_ingr-transaction.phtml');
        $this->viewModel->setVariables([
            'from' => $this->from,
            'fromName' => $this->em->getRepository(Storage::class)->find($this->from)->getName(),
            'to' => $this->to,
            'toName' => $this->em->getRepository(Storage::class)->find($this->to)->getName(),
            'datatableModel' => $this->datatableModel,
            'title' => $this->em->getRepository(StockTransactionType::class)->findBy(['stringId' => $this->actionName])[0]->getName(),
            'form' => $this->form,
            'actionName' => $this->actionName,
            'changeSelectableLabel' => $this->selectableLabel,
            'selectableId' => $this->selectableId,
        ]);

    }


    public function receiveAction()
    {

        $storageTypeFrom = $this->em->getRepository(Storage::class)->find($this->from)->getStorageType()->getStringId();
        $storageTypeTo = $this->em->getRepository(Storage::class)->find($this->to)->getStorageType()->getRealStorageType();
        if ($storageTypeFrom != 'supplier' || empty($storageTypeTo)) {
            $this->flashMessenger()->addMessage('Nem helyesek a tárolók típusai, kérjük állítsa be!', 'error');
            return $this->redirect()->toRoute('ingrtransactionchoose', ['action' => $this->actionName]);
        }

        $this->stockTransaction();

        $datas = [];
        foreach ($this->em->getRepository(Ingredient::class)->findAll() as $anIngr) {
            $datas[] = [
                $anIngr->getId(),
                $anIngr->getName(),
            ];
        }
        $this->datatableModel->setData($datas);
        $this->datatableModel->setHeader(['Id', 'Alapanyag', 'Mennyiség']);
        $this->datatableModel->setInput([['inputType' => 'number', 'inputName' => 'amount', 'whichColumn' => 2, 'valueColumn' => null]]);
        $this->datatableModel->setSelectable('select');

        return $this->viewModel;
    }


    public function returnAction()
    {
        $storageTypeFrom = $this->em->getRepository(Storage::class)->find($this->from)->getStorageType()->getRealStorageType();
        $storageTypeTo = $this->em->getRepository(Storage::class)->find($this->to)->getStorageType()->getStringId();
        if (empty($storageTypeFrom) || $storageTypeTo != 'supplier') {
            $this->flashMessenger()->addMessage('Nem helyesek a tárolók típusai, kérjük állítsa be!', 'error');
            return $this->redirect()->toRoute('ingrtransactionchoose', ['action' => $this->actionName]);
        }
        $this->stockTransaction();
        return $this->viewModel;
    }

    public function moveAction()
    {
        $storageTypeFrom = $this->em->getRepository(Storage::class)->find($this->from)->getStorageType()->getRealStorageType();
        $storageTypeTo = $this->em->getRepository(Storage::class)->find($this->to)->getStorageType()->getRealStorageType();
        if ((empty($storageTypeFrom) || empty($storageTypeTo)) || $this->from == $this->to) {
            $this->flashMessenger()->addMessage('Nem helyesek a tárolók típusai, kérjük állítsa be!', 'error');
            return $this->redirect()->toRoute('ingrtransactionchoose', ['action' => $this->actionName]);
        } elseif ($this->from == $this->to) {
            $this->flashMessenger()->addMessage('Nem lehet ugyanaz a két tároló!', 'error');
            return $this->redirect()->toRoute('ingrtransactionchoose', ['action' => $this->actionName]);
        }

        $this->stockTransaction();

        return $this->viewModel;
    }


    public function discardingredientAction()
    {
        $storageTypeFrom = $this->em->getRepository(Storage::class)->find($this->from)->getStorageType()->getRealStorageType();
        $storageTypeTo = $this->em->getRepository(Storage::class)->find($this->to)->getStorageType()->getStringId();
        if (empty($storageTypeFrom) || $storageTypeTo != 'discard') {
            $this->flashMessenger()->addMessage('Nem helyesek a tárolók típusai, kérjük állítsa be!', 'error');
            return $this->redirect()->toRoute('ingrtransactionchoose', ['action' => $this->actionName]);
        }

        $this->stockTransaction();

        return $this->viewModel;
    }

    public function discardproductAction()
    {
        $storageTypeFrom = $this->em->getRepository(Storage::class)->find($this->from)->getStorageType()->getRealStorageType();
        $storageTypeTo = $this->em->getRepository(Storage::class)->find($this->to)->getStorageType()->getStringId();
        if (empty($storageTypeFrom) || $storageTypeTo != 'discard') {
            $this->flashMessenger()->addMessage('Nem helyesek a tárolók típusai, kérjük állítsa be!', 'error');
            return $this->redirect()->toRoute('ingrtransactionchoose', ['action' => $this->actionName]);
        }

        $this->stockTransaction();

        return $this->viewModel;
    }

    public function stockcorrectionAction()
    {
        $storageTypeFrom = $this->em->getRepository(Storage::class)->find($this->from)->getStorageType()->getRealStorageType();
        $storageTypeTo = $this->em->getRepository(Storage::class)->find($this->to)->getStorageType()->getRealStorageType();
        if ((empty($storageTypeFrom) || empty($storageTypeTo)) || $storageTypeFrom != $storageTypeTo) {
            $this->flashMessenger()->addMessage('Nem helyesek a tárolók típusai, kérjük állítsa be!', 'error');
            return $this->redirect()->toRoute('ingrtransactionchoose', ['action' => $this->actionName]);
        }

        $this->stockTransaction();

        $datas = [];
        foreach ($this->em->getRepository(Stock::class)->getIngredientsAmount($this->from) as $stockRow) {
            $datas[] = [
                $stockRow->getIngredient()->getId(),
                $stockRow->getIngredient()->getName(),
                $stockRow->getAmount(),
            ];
        }
        $this->datatableModel->setData($datas);

        return $this->viewModel;
    }


    public function universalAction()
    {
        if ($this->from == $this->to) {
            $this->flashMessenger()->addMessage('Nem lehet ugyanaz a két tároló!', 'error');
            return $this->redirect()->toRoute('ingrtransactionchoose', ['action' => $this->actionName]);
        }

        $this->stockTransaction();

        return $this->viewModel;
    }


}
