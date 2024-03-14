<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Transaction\Controller;

use Application\Model\RetrieveByDatatableModel;
use Application\Service\Log\EventLogService;
use Catalog\Entity\Money;
use Transaction\Entity\MoneyStock;
use Transaction\Entity\StockTransactionType;
use Catalog\Entity\Storage;
use Transaction\Model\StockTransaction\ItemTransactionEventModel;
use Transaction\Service\StockTransaction\ItemTransactionService;
use Doctrine\ORM\EntityManager;
use User\Service\RegisteredService\UserAuthentication;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class MoneyTransactionController
 * @package Transaction\Controller
 * @property ItemTransactionEventModel $transactionModel
 * @property ItemTransactionService $moneyTransactionService
 * @property RetrieveByDatatableModel $datatableModel
 */
class MoneyTransactionController extends AbstractActionController
{

    /** @var $moneyTransactionService \Transaction\Service\StockTransaction\IStockTransactionService */
    private $sm;
    private $moneyTransactionService;
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
        $this->moneyTransactionService = $misc['moneyTransactionService'];
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

        if ((empty($this->em->getRepository(Storage::class)->find($this->from))
                || !($this->em->getRepository(Storage::class)->getIsStuffInStorage($this->to, 'money'))) ||
            (empty($this->em->getRepository(Storage::class)->find($this->to))
                || !($this->em->getRepository(Storage::class)->getIsStuffInStorage($this->to, 'money')))
        ) {
            $this->flashMessenger()->addMessage('Nem helyesek a tárolók típusai, kérjük állítsa be!', 'error');
            return $this->redirect()->toRoute('moneytransactionchoose', ['action' => $this->actionName]);
        }
        return parent::onDispatch($e);
    }



    //LEHET ITT A KÜLÖNBÖZŐ ACTIONÖKNÉL FELDOLGOZNI PL A BIZONYLATKOMPATIBILIS ADATOKAT STB
    //De alapból egy-egy datatable fog megjelenni a FROM storage stockja
    protected function stockTransaction()
    {
        /** @var $transactionService \Transaction\Service\StockTransaction\IStockTransactionService */
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
                $this->moneyTransactionService->setPostData($postData);
                $this->moneyTransactionService->adjustTransactionEventModel();
                $this->moneyTransactionService->setEventManager($this->getEventManager());
                $this->moneyTransactionService->triggerStockTransactionEvent();

                $this->sm->get(EventLogService::class)->__invoke('Sikeres pénzeszközművelet: ' . $this->actionName, 100);

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

            $header = ['Id', 'Pénzeszköz'];
            $datas = [];
            //ha valódi stock, akkor csak a saját pénzeszközt bontsuk ki
            if (!empty($this->em->getRepository(Storage::class)->find($this->from)->getStorageType()->getIsRealStorageType())) {
                foreach ($this->em->getRepository(MoneyStock::class)->getMoneysAmount($this->from) as $stockRow) {
                    $datas[] = [
                        $stockRow->getItem()->getId(),
                        $stockRow->getItem()->getName(),
                        $this->sm->get('nf')->nf($stockRow->getAmount(), true) . ' ' . $stockRow->getItem()->getMoneyUnit()->getShortName() . '<input type="hidden" class="stock-amount" id="money-' . $stockRow->getItem()->getId() . '" value="' . $this->sm->get('nf')->nf($stockRow->getAmount(), true) . '">',
                    ];
                }
                $inputs[0]['whichColumn'] = 3;
                $header[] = 'Kasszában';
            } else {
                foreach ($this->em->getRepository(Money::class)->findAll() as $moneyRow) {
                    $datas[] = [
                        $moneyRow->getId(),
                        $moneyRow->getName(),
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

        $this->viewModel->setTemplate('transaction/money-transaction/_money-transaction.phtml');
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


    public function moneyinAction()
    {

        $storageTypeFrom = $this->em->getRepository(Storage::class)->find($this->from)->getStorageType()->getStringId();
        $storageTypeTo = $this->em->getRepository(Storage::class)->find($this->to)->getStorageType()->getIsRealStorageType();
        if ($storageTypeFrom != 'supplier' || empty($storageTypeTo)) {
            $this->flashMessenger()->addMessage('Nem helyesek a tárolók típusai, kérjük állítsa be!', 'error');
            return $this->redirect()->toRoute('moneytransactionchoose', ['action' => $this->actionName]);
        }

        $this->stockTransaction();

        $datas = [];
        foreach ($this->em->getRepository(Money::class)->findAll() as $aMoney) {
            $datas[] = [
                $aMoney->getId(),
                $aMoney->getName(),
            ];
        }
        $this->datatableModel->setData($datas);
        $this->datatableModel->setHeader(['Id', 'Pénzeszköz', 'Mennyiség']);
        $this->datatableModel->setInput([['inputType' => 'number', 'inputName' => 'amount', 'whichColumn' => 2, 'valueColumn' => null]]);
        $this->datatableModel->setSelectable('select');

        return $this->viewModel;
    }


    public function moneyoutAction()
    {
        $storageTypeFrom = $this->em->getRepository(Storage::class)->find($this->from)->getStorageType()->getIsRealStorageType();
        $storageTypeTo = $this->em->getRepository(Storage::class)->find($this->to)->getStorageType()->getStringId();
        if (empty($storageTypeFrom) || $storageTypeTo != 'supplier') {
            $this->flashMessenger()->addMessage('Nem helyesek a tárolók típusai, kérjük állítsa be!', 'error');
            return $this->redirect()->toRoute('moneytransactionchoose', ['action' => $this->actionName]);
        }
        $this->stockTransaction();
        return $this->viewModel;
    }

    public function moneymoveAction()
    {
        $storageTypeFrom = $this->em->getRepository(Storage::class)->find($this->from)->getStorageType()->getIsRealStorageType();
        $storageTypeTo = $this->em->getRepository(Storage::class)->find($this->to)->getStorageType()->getIsRealStorageType();
        if ((empty($storageTypeFrom) || empty($storageTypeTo)) || $this->from == $this->to) {
            $this->flashMessenger()->addMessage('Nem helyesek a tárolók típusai, kérjük állítsa be!', 'error');
            return $this->redirect()->toRoute('moneytransactionchoose', ['action' => $this->actionName]);
        } elseif ($this->from == $this->to) {
            $this->flashMessenger()->addMessage('Nem lehet ugyanaz a két tároló!', 'error');
            return $this->redirect()->toRoute('moneytransactionchoose', ['action' => $this->actionName]);
        }

        $this->stockTransaction();

        return $this->viewModel;
    }


    public function moneystockcorrectionAction()
    {
        $storageTypeFrom = $this->em->getRepository(Storage::class)->find($this->from)->getStorageType()->getIsRealStorageType();
        $storageTypeTo = $this->em->getRepository(Storage::class)->find($this->to)->getStorageType()->getIsRealStorageType();
        if ((empty($storageTypeFrom) || empty($storageTypeTo)) || $storageTypeFrom != $storageTypeTo) {
            $this->flashMessenger()->addMessage('Nem helyesek a tárolók típusai, kérjük állítsa be!', 'error');
            return $this->redirect()->toRoute('moneytransactionchoose', ['action' => $this->actionName]);
        }

        $this->stockTransaction();

        $datas = [];
        foreach ($this->em->getRepository(MoneyStock::class)->getMoneysAmount($this->from) as $stockRow) {
            $datas[] = [
                $stockRow->getItem()->getId(),
                $stockRow->getItem()->getName(),
                $this->sm->get('nf')->nf($stockRow->getAmount(), true),
            ];
        }
        $this->datatableModel->setData($datas);

        return $this->viewModel;
    }


    public function moneyuniversalAction()
    {
        if ($this->from == $this->to) {
            $this->flashMessenger()->addMessage('Nem lehet ugyanaz a két tároló!', 'error');
            return $this->redirect()->toRoute('moneytransactionchoose', ['action' => $this->actionName]);
        }

        $this->stockTransaction();

        return $this->viewModel;
    }


}
