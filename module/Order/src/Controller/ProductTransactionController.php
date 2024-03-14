<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.19.
 * Time: 16:32
 */

namespace Order\Controller;


use Application\Service\Log\EventLogService;
use Catalog\Entity\Product;
use Catalog\Entity\Storage;
use Catalog\Entity\StorageType;
use Transaction\Service\StockTransaction\IStockTransactionService;
use Doctrine\ORM\EntityManager;
use Order\Entity\OrderItemInStorage;
use User\Service\RegisteredService\UserAuthentication;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class ProductTransactionController
 * @package Order\Controller
 * @property IStockTransactionService $productTransactionService
 */
class ProductTransactionController extends AbstractActionController
{
    private $sm;
    private $productTransactionService;
    private $msg;
    private $actionName;
    private $datatableModelClass;
    private $from;
    private $to;
    private $datas;
    private $header;


    public function __construct($sm, $misc)
    {
        $this->sm = $sm;
        $this->em = $this->sm->get(EntityManager::class);
        $this->productTransactionService = $misc['productTransactionService'];
        $this->datatableModelClass = get_class($misc['showWithDatatable']);
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

        if (empty($this->to)) $this->to = $this->from;

        if ((
                empty($this->em->getRepository(Storage::class)->find($this->from))
                || empty($this->em->getRepository(Storage::class)->find($this->to))
            ) ||
            (
                empty($this->em->getRepository(Storage::class)->find($this->from)->getStorageType()->getProductAwareStorageType())
                || empty($this->em->getRepository(Storage::class)->find($this->to)->getStorageType()->getProductAwareStorageType())
            )
        ) {
            $this->msg->addMessage('A megadott tárolók nem léteznek, vagy nem tudnak termékeket kezelni', 'error');
            $this->getResponse()->setStatusCode(404);
            return;
        }
        return parent::onDispatch($e);
    }


    protected function _productTransaction()
    {
        $postData = $this->prg();
        if ($postData instanceof \Zend\Http\PhpEnvironment\Response) return $postData; //PRG TRÜKK

        //Ha VAN POST
        if (!empty($postData)) {
            try {

                $postData['StockTransaction']['stockTransactionType'] = $this->actionName;
                $this->form->setData($postData);
                $this->form->isValid();
                $this->productTransactionService->setPostData($postData);
                $this->productTransactionService->adjustTransactionEventModel();
                $this->productTransactionService->setEventManager($this->getEventManager());
                $this->productTransactionService->triggerStockTransactionEvent();
                $this->sm->get(EventLogService::class)->__invoke('Sikeres rendelési tétel mozgatás: ' . $this->actionName, 200);
                $reloadStore = (empty($destinationStorage = $this->em->getRepository(Storage::class)->getDestinationType($this->from))) ? $this->from : $destinationStorage[0]->getId();
                $reloadStore = (empty($destinationStorage = $this->em->getRepository(Storage::class)->getDestinationType($this->to))) ? $reloadStore : $destinationStorage[0]->getId();

                $this->flashMessenger()->addMessage('A művelet sikeres volt!', 'success');
                return $this->redirect()->toRoute('producttransaction', ['action' => 'destination', 'from' => $reloadStore]);
            } catch (\Exception $e) {
                error_log($e);
                $this->flashMessenger()->addMessage($e->getMessage(), 'error');
                return $this->redirect()->refresh();
            }
        }
    }


    /**
     * A rendelési egység actual állapota, és az eddigi rendelések
     * @return ViewModel
     */
    public function destinationAction()
    {
        $total = 0;
        $this->datas = [];

        $datatableModel = new $this->datatableModelClass();

        $this->_productTransaction();

        $consumingType = $this->em->getRepository(StorageType::class)->findBy(['stringId' => 'consumption'])[0]->getId();
        $discardType = $this->em->getRepository(StorageType::class)->findBy(['stringId' => 'discard'])[0]->getId();
        $localStorageType = $this->em->getRepository(StorageType::class)->findBy(['stringId' => 'localstorage'])[0]->getId();

        $consumingId = $this->em->getRepository(Storage::class)->findBy(['storageType' => $consumingType])[0]->getId();
        $discardId = $this->em->getRepository(Storage::class)->findBy(['storageType' => $discardType])[0]->getId();
        $localStorage = $this->em->getRepository(Storage::class)->findBy(['storageType' => $localStorageType])[0]->getId();

        $destinations = $this->em->getRepository(Storage::class)->getDestination();


        $datatableModel->setAction([
            ['name' => 'Összes fizetése', 'actionUrl' => $this->url()->fromRoute('producttransaction', ['action' => 'paying', 'from' => $this->from, 'to' => $consumingId]), 'moreInfo' => $consumingId, 'warningText' => 'A kijelölt elemek fizetése?', 'icon' => 'usd'],
            ['name' => 'Kijelöltek fizetése', 'actionUrl' => $this->url()->fromRoute('producttransaction', ['action' => 'paying', 'from' => $this->from, 'to' => $consumingId]), 'moreInfo' => $consumingId, 'warningText' => 'A kijelölt elemek fizetése?', 'icon' => 'usd'],
            ['name' => 'Selejtezés', 'actionUrl' => $this->url()->fromRoute('producttransaction', ['action' => 'productbacktotrash', 'from' => $this->from, 'to' => $discardId]), 'moreInfo' => $discardId, 'warningText' => 'Biztos selejtezi a tételeket?', 'icon' => 'trash'],
            ['name' => 'Visszavételezés', 'actionUrl' => $this->url()->fromRoute('producttransaction', ['action' => 'productback', 'from' => $this->from, 'to' => $localStorage]), 'moreInfo' => $localStorage, 'warningText' => 'Biztos visszavételezi a tételeket?', 'icon' => 'arrow-left'],
            ['name' => 'Áthelyezés', 'actionUrl' => $this->url()->fromRoute('producttransaction', ['action' => 'moveorderitem', 'from' => $this->from, 'to' => '']), 'moreInfo' => '', 'warningText' => 'Tétel(ek) áthelyezése ide:', 'icon' => 'arrow-right']
        ]);


        $datatableModel->setSelectable('checkbox');
        $datatableModel->setNaked(true);

        $this->header = ['Id', 'Időpont', 'Név', 'Egységár', 'Mennyiség', 'Számított ár'];
        $productStorages = $this->em->getRepository(OrderItemInStorage::class)->findBy(['storage' => $this->from]);
        $hiddenAmount = [];
        foreach ($productStorages as $id => $productStorage) {
            $this->datas[] = [
                $productStorage->getId(),
                $productStorage->getStockTransaction()->getDateTime()->format('H:i'),
                $productStorage->getProduct()->getName(),
                $this->sm->get('ViewHelperManager')->get('money')->__invoke($productStorage->getProduct()->getPrice()),
                $productStorage->getAmount(),
                '<input type="hidden" id="price-' . $productStorage->getId() . '" class="order-item-price" value="' . $productStorage->getPrice() . '">' . $this->sm->get('ViewHelperManager')->get('money')->__invoke($productStorage->getPrice()),
            ];
            $total += $productStorage->getPrice();
            $hiddenAmount[] = '<input type="hidden" name="orderItem[' . $productStorage->getId() . '][' . $productStorage->getProduct()->getId() . ']" value="' . $productStorage->getAmount() . '">';
        }

        if (empty($this->datas)) {
            $localStorageTypeId = $this->em->getRepository(StorageType::class)->findBy(['stringId' => 'localstorage'])[0]->getId();
            $localStorageId = $this->em->getRepository(Storage::class)->findBy(['storageType' => $localStorageTypeId])[0]->getId();

            $this->flashMessenger()->addMessage('A művelet sikeres volt!', 'success');
            return $this->redirect()->toRoute('producttransaction', ['action' => 'order', 'from' => $localStorageId, 'to' => $this->from]);
        }

        $datatableModel->setData($this->datas);
        $datatableModel->setHeader($this->header);
        $datatableModel->setName($this->actionName);
        $datatableModel->setOrderColumn(2);
        $this->viewModel->setVariables(['total' => $total, 'datatableModel' => $datatableModel]);

        //TODO: #159 beállítani, hogy a megfelelő transactiontype legyen move paying stb
        $this->form->get('StockTransaction')->get('stockTransactionType')->setValue('universal');
        $this->form->get('StockTransaction')->get('fromStorage')->setValue($this->from);
        $this->form->get('StockTransaction')->get('toStorage')->setValue($this->to);
        $this->form->get('StockTransaction')->get('user')->setValue($this->actualUserId);

        $this->form->prepare();

        $this->viewModel->setVariables([
            'form' => $this->form,
            'hiddenAmount' => $hiddenAmount,
            'destinationName' => $this->em->getRepository(Storage::class)->find($this->from)->getName(),
            'from' => $this->from,
            'destinations' => $destinations
        ]);
        return $this->viewModel;

    }


    //NE NEVEZD ÁT AZ ACTIONÖKET MERT A STOCKTRANSACTION TYPENAK IS EZEK A NEVEI
    public function orderAction()
    {
        //Ha jön a POST, akkor ez dolgozza fel
        $this->_productTransaction();


        //AZ EDDIGI RENDELT TÉTELEK
        $header = ['Id', 'Időpont', 'Termék', 'Egységár', 'Mennyiség', 'Számított ár'];
        $productStorages = $this->em->getRepository(OrderItemInStorage::class)->findBy(['storage' => $this->to]);
        $total = 0;
        $datas = [];
        foreach ($productStorages as $id => $productStorage) {
            $datas[] = [
                $productStorage->getId(),
                $productStorage->getStockTransaction()->getDateTime()->format('H:i'),
                $productStorage->getProduct()->getName(),
                $this->sm->get('ViewHelperManager')->get('money')->__invoke($productStorage->getProduct()->getPrice()),
                $productStorage->getAmount(),
                $this->sm->get('ViewHelperManager')->get('money')->__invoke($productStorage->getPrice()),
            ];
            $total += $productStorage->getPrice();
        }
        $datatableListing = new $this->datatableModelClass();
        $datatableListing->setData($datas);
        $datatableListing->setHeader($header);
        $datatableListing->setName($this->actionName . 'list');
        $datatableListing->setOrderColumn(2);
        $datatableListing->setNaked(true);


        /////////////////////////////////////////////////////////////////////////////////////////////

        //ÚJ TERMÉKEK RENDELÉSE

        $this->from = $this->em->getRepository(Storage::class)->getActualLocalStorage($this->actualUserId)[0]['id'];

        $this->header = ['Id', 'Termék', 'Kategória', 'Ár', 'Mennyiség'];
        $datatableModel = new $this->datatableModelClass();

        $this->datas = [];
        // csak a számára elérhető productokat listázza
        foreach ($this->em->getRepository(Product::class)->getAvailableProducts($this->from) as $productRow) {
            $this->datas[] = [
                $productRow->getId(),
                $productRow->getName(),
                $productRow->getProductGroup()->getName(),
                $this->sm->get('ViewHelperManager')->get('money')->__invoke($productRow->getPrice()),
                '1'
            ];
        }
        $datatableModel->setInput([['inputType' => 'number', 'inputName' => 'amount', 'whichColumn' => 4, 'valueColumn' => 4]]);
        $datatableModel->setAction([['name' => 'Rendelésfelvétel', 'actionUrl' => '', 'warningText' => 'Biztos megrendeli a tételeket?', 'icon' => 'ok']]);
        $datatableModel->setSelectable('select');
        $datatableModel->setData($this->datas);
        $datatableModel->setHeader($this->header);
        $datatableModel->setName($this->actionName);
        $datatableModel->setOrderColumn(2);

        $this->form->get('StockTransaction')->get('stockTransactionType')->setValue('universal');
        $this->form->get('StockTransaction')->get('fromStorage')->setValue($this->from);
        $this->form->get('StockTransaction')->get('toStorage')->setValue($this->to);
        $this->form->get('StockTransaction')->get('user')->setValue($this->actualUserId);
        $this->form->prepare();


        $this->viewModel->setVariables([
            'total' => $total,
            'datatableListing' => $datatableListing,
            'datatableModel' => $datatableModel,
            'from' => $this->from,
            'fromName' => $this->em->getRepository(Storage::class)->find($this->from)->getName(),
            'to' => $this->to,
            'toName' => $this->em->getRepository(Storage::class)->find($this->to)->getName(),
            // 'datatableModel' => $datatableModel,
            'form' => $this->form,
            'actionName' => $this->actionName,
        ]);

        return $this->viewModel;
    }


    //NE NEVEZD ÁT AZ ACTIONÖKET MERT A STOCKTRANSACTION TYPENAK IS EZEK A NEVEI
    public function productbackAction()
    {
        $this->_productTransaction();

        return $this->viewModel;
    }

    //NE NEVEZD ÁT AZ ACTIONÖKET MERT A STOCKTRANSACTION TYPENAK IS EZEK A NEVEI
    public function productbacktotrashAction()
    {
        $this->_productTransaction();

        return $this->viewModel;
    }

    //NE NEVEZD ÁT AZ ACTIONÖKET MERT A STOCKTRANSACTION TYPENAK IS EZEK A NEVEI
    public function payingAction()
    {
        $this->_productTransaction();

        //Itt elsütünk egy új paying nevű eventet is majd

        return $this->viewModel;
    }


    //NE NEVEZD ÁT AZ ACTIONÖKET MERT A STOCKTRANSACTION TYPENAK IS EZEK A NEVEI
    public function moveorderitemAction()
    {
        $this->_productTransaction();

        //Itt elsütünk egy új paying nevű eventet is majd

        return $this->viewModel;
    }


    //TODO:#160 ITT LEHET MAJD SELEJTBŐL FOGYASZTÁSBÓL STB VISSZATENNI A TERMÉKEKET, HASONLÓ LESZ MINT A STOCKTRANSACTIONCHOOSE
    public function universalAction()
    {
        //ITT SZÉPEN FELÜL TUDO MAJD ÍRNI A _productTransaction() CUCCAIT HOVA REDIRECTELJEN STB
    }

}