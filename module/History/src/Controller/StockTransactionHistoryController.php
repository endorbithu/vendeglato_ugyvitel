<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.11.01.
 * Time: 10:06
 */

namespace History\Controller;


use Transaction\Entity\IngredientMoving;
use Transaction\Entity\StockTransaction;
use Transaction\Entity\StockTransactionType;
use History\Form\Fieldset\StockTransactionChooseFieldset;
use History\Form\StockTransactionHistoryForm;
use Doctrine\ORM\EntityManager;
use Order\Entity\DailyIncome;
use Order\Entity\OrderItemInStorage;
use Order\Entity\ProductMoving;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class StockTransactionHistoryController extends AbstractActionController
{

    private $sm;
    private $em;
    private $misc;
    private $id;
    private $msg;

    public function __construct($sm, $misc)
    {
        $this->sm = $sm;
        $this->em = $sm->get(EntityManager::class);
        $this->misc = $misc;
    }

    //Dispatch előtt megnézzük, hogy van-e ilyen tartalom
    public function onDispatch(\Zend\Mvc\MvcEvent $e)
    {
        $this->msg = $this->getEvent()->getViewModel()->getVariable('statusMessages');
        return parent::onDispatch($e);
    }


    public function stocktransactionAction()
    {
        $this->id = (int)$this->params()->fromRoute('id');

        if (empty($stockTransaction = $this->em->getRepository(StockTransaction::class)->find($this->id))) {
            $this->msg->addMessage('A megadott tranzakció azonosító nem létezik', 'error');
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $datatableOrderItem = null;
        $datatableProduct = null;
        $datatableIngredient = null;
        $total = null;

        if ($stockTransaction->getTransactionType()->getStringId() == 'paying'
            && !empty($orderItems = $this->em->getRepository(OrderItemInStorage::class)->findBy(['stockTransaction' => $this->id]))
        ) {
            $header = [
                'ID',
                'Termék',
                'Mennyiség',
                'Nettó',
                'Áfa',
                'Bruttó összeg',
            ];
            $datas = [];
            $total = 0;
            foreach ($orderItems as $orderItem) {
                $rowTotal = $orderItem->getPrice();
                $rowNet = ($orderItem->getPrice() / (1 + $orderItem->getProduct()->getVatGroup()->getVatValue()));
                $vatPrice = (($orderItem->getPrice() / (1 + $orderItem->getProduct()->getVatGroup()->getVatValue()) * $orderItem->getProduct()->getVatGroup()->getVatValue()));

                $datas[] = [
                    $orderItem->getId(),
                    '<a href="' . $this->url()->fromRoute('basedata', ['action' => 'product', 'id' => $orderItem->getProduct()->getId()]) . '">' . $orderItem->getProduct()->getName() . '</a>',
                    $orderItem->getAmount(),
                    $this->sm->get('ViewHelperManager')->get('money')->__invoke($rowNet),
                    $this->sm->get('ViewHelperManager')->get('money')->__invoke($vatPrice),
                    $this->sm->get('ViewHelperManager')->get('money')->__invoke($rowTotal),
                ];
                $total += $orderItem->getPrice();
            }

            $datatableOrderItem = new $this->misc['datatableModelClass']();
            $datatableOrderItem->setName('orderitem');
            $datatableOrderItem->setData($datas);
            $datatableOrderItem->setHeader($header);

        } elseif (!empty($products = $this->em->getRepository(ProductMoving::class)->findBy(['stockTransaction' => $this->id]))) {
            $header = [
                'ID',
                'Termék',
                'Mennyiség',
            ];
            $datas = [];
            foreach ($products as $product) {
                $datas[] = [
                    $product->getId(),
                    '<a href="' . $this->url()->fromRoute('basedata', ['action' => 'product', 'id' => $product->getProduct()->getId()]) . '">' . $product->getProduct()->getName() . '</a>',
                    $product->getAmount(),
                ];
            }
            $datatableProduct = new $this->misc['datatableModelClass']();
            $datatableProduct->setName('productmoving');
            $datatableProduct->setData($datas);
            $datatableProduct->setHeader($header);

        } elseif (!empty($ingredients = $this->em->getRepository(IngredientMoving::class)->findBy(['stockTransaction' => $this->id]))) {
            $header = [
                'ID',
                'Alapanyag',
                'Mennyiség',
            ];
            $datas = [];
            foreach ($ingredients as $ingredient) {
                $datas[] = [
                    $ingredient->getId(),
                    '<a href="' . $this->url()->fromRoute('basedata', ['action' => 'ingredient', 'id' => $ingredient->getItem()->getId()]) . '">' . $ingredient->getItem()->getName() . '</a>',
                    $ingredient->getAmount() . ' ' . $ingredient->getItem()->getIngredientUnit()->getShortName(),
                ];
            }

            $datatableIngredient = new $this->misc['datatableModelClass']();
            $datatableIngredient->setName('ingredientmove');
            $datatableIngredient->setData($datas);
            $datatableIngredient->setHeader($header);
        }


        return new ViewModel([
            'stockTransaction' => $stockTransaction,
            'datatableIngredient' => $datatableIngredient,
            'datatableProduct' => $datatableProduct,
            'datatableOrderItem' => $datatableOrderItem,
            'total' => $total,
        ]);


    }

    public function stocktransactionlistAction()
    {
        $type = strip_tags($this->params()->fromRoute('type'));
        $yf = (int)$this->params()->fromRoute('yf');
        $mf = (int)$this->params()->fromRoute('mf');
        $df = (int)$this->params()->fromRoute('df');
        $yt = (int)$this->params()->fromRoute('yt');
        $mt = (int)$this->params()->fromRoute('mt');
        $dt = (int)$this->params()->fromRoute('dt');
        $total = false;

        try {
            $fromDate = new \DateTime($yf . '-' . $mf . '-' . $df);
        } catch (\Exception $e) {
            $fromDate = new \DateTime();
            $fromDate->modify('-2 month');
        }

        try {
            $toDate = new \DateTime($yt . '-' . $mt . '-' . $dt);
        } catch (\Exception $e) {
            $toDate = new \DateTime();
        }


        $stockTransactions = $this->em->getRepository(StockTransaction::class)->getStockTransaction($type, $fromDate, $toDate);

        $typeName = (empty($typeRep = $this->em->getRepository(StockTransactionType::class)->findBy(['stringId' => strip_tags($type)]))) ? 'Műveletek (összes típus)' : $typeRep[0]->getName();
        if ($type != 'paying') {
            $header = [
                'ID',
                'Dátum/idő',
                'Típus',
                'Felhasználó',
                'Tárolóból',
                'Tárolóba',
            ];
            $datas = [];
            foreach ($stockTransactions as $stockTransaction) {
                $datas[] = [
                    $stockTransaction->getId(),
                    '<a href="' . $this->url()->fromRoute('stocktransactionhistory', ['action' => 'stocktransaction', 'id' => $stockTransaction->getId()]) . '"> ' . $stockTransaction->getDateTime()->format('Y.m.d H:i:s') . '</a>',
                    $stockTransaction->getTransactionType()->getName(),
                    $stockTransaction->getUser()->getName(),
                    $stockTransaction->getFromStorage()->getName(),
                    $stockTransaction->getToStorage()->getName(),
                ];
            }

        } else {
            $header = [
                'ID',
                'Rendelés',
                'Felhasználó',
                'Rendelési hely',
                'Nettó',
                'ÁFA',
                'Bruttó összeg',
            ];
            $datas = [];

            $total = 0;
            foreach ($stockTransactions as $stockTransaction) {
                $rowTotal = 0;
                $rowNet = 0;
                $vatPrice = 0;
                foreach ($this->em->getRepository(OrderItemInStorage::class)->findBy(['stockTransaction' => $stockTransaction->getId()]) as $orderItem) {
                    $rowTotal += $orderItem->getPrice();
                    $rowNet += ($orderItem->getPrice() / (1 + $orderItem->getProduct()->getVatGroup()->getVatValue()));
                    $vatPrice += (($orderItem->getPrice() / (1 + $orderItem->getProduct()->getVatGroup()->getVatValue()) * $orderItem->getProduct()->getVatGroup()->getVatValue()));
                }


                $datas[] = [
                    $stockTransaction->getId(),
                    '<a href="' . $this->url()->fromRoute('stocktransactionhistory', ['action' => 'stocktransaction', 'id' => $stockTransaction->getId()]) . '"> ' . $stockTransaction->getDateTime()->format('Y.m.d H:i:s') . '</a>',
                    $stockTransaction->getUser()->getName(),
                    $stockTransaction->getFromStorage()->getName(),
                    $this->sm->get('ViewHelperManager')->get('money')->__invoke($rowNet),
                    $this->sm->get('ViewHelperManager')->get('money')->__invoke($vatPrice),
                    $this->sm->get('ViewHelperManager')->get('money')->__invoke($rowTotal),
                ];

                $total += $rowTotal;
            }
        }


        $datatableStockTransaction = new $this->misc['datatableModelClass']();
        $datatableStockTransaction->setName('stocktransactiolist');
        $datatableStockTransaction->setData($datas);
        $datatableStockTransaction->setHeader($header);
        $datatableStockTransaction->setOrderColumn(1);
        $datatableStockTransaction->setOrderColumnDir('desc');


        return new ViewModel(['datatableStockTransaction' => $datatableStockTransaction,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'typeName' => $typeName,
            'total' => $total
        ]);

    }

    public function incomeAction()
    {

        $dailyIncomes = $this->em->getRepository(DailyIncome::class)->findAll();

        $header = [
            'ID',
            'Nap',
            'Nettó',
            'ÁFA',
            'Bruttó',
        ];
        $datas = [];
        foreach ($dailyIncomes as $dailyIncome) {
            $datas[] = [
                $dailyIncome->getId(),
                '<a href="' . $this->url()->fromRoute('stocktransactionhistorylist',
                    [
                        'action' => 'stocktransactionlist',
                        'type' => 'paying',
                        'yf' => $dailyIncome->getDateTime()->format('Y'),
                        'mf' => $dailyIncome->getDateTime()->format('m'),
                        'df' => $dailyIncome->getDateTime()->format('d'),
                        'yt' => $dailyIncome->getDateTime()->format('Y'),
                        'mt' => $dailyIncome->getDateTime()->format('m'),
                        'dt' => $dailyIncome->getDateTime()->format('d'),
                    ])
                . '"> ' . $dailyIncome->getDateTime()->format('Y.m.d') . '</a>',
                $this->sm->get('ViewHelperManager')->get('money')->__invoke($dailyIncome->getNetIncome()),
                $this->sm->get('ViewHelperManager')->get('money')->__invoke($dailyIncome->getVatAmount()),
                $this->sm->get('ViewHelperManager')->get('money')->__invoke($dailyIncome->getNetIncome() + $dailyIncome->getVatAmount()),
            ];
        }

        $datatableIncome = new $this->misc['datatableModelClass']();
        $datatableIncome->setName('dailyincome');
        $datatableIncome->setData($datas);
        $datatableIncome->setHeader($header);
        $datatableIncome->setOrderColumn(1);
        $datatableIncome->setOrderColumnDir('desc');

        return new ViewModel(
            [
                'datatableIncome' => $datatableIncome,
            ]
        );
    }


    public function stocktransactionchooseAction()
    {
        $postData = $this->prg();
        if ($postData instanceof \Zend\Http\PhpEnvironment\Response) return $postData;

        $fieldset = new StockTransactionChooseFieldset($this->em);
        $form = new StockTransactionHistoryForm('StockTransactionHistory', $fieldset);

        if (!empty($postData)) {
            $form->setData($postData);
            if ($form->isValid()) {
                $transactionType = (empty($type = $this->em->getRepository(StockTransactionType::class)->find((int)$postData['StockTransactionChoose']['stockTransactionType'])) ? 'all' : $type->getStringId());
                return $this->redirect()->toRoute('stocktransactionhistorylist', [
                    'action' => 'stocktransactionlist',
                    'type' => $transactionType,
                    'yf' => $postData['StockTransactionChoose']['yearFrom'],
                    'mf' => $postData['StockTransactionChoose']['monthFrom'],
                    'df' => $postData['StockTransactionChoose']['dayFrom'],
                    'yt' => $postData['StockTransactionChoose']['yearTo'],
                    'mt' => $postData['StockTransactionChoose']['monthTo'],
                    'dt' => $postData['StockTransactionChoose']['dayTo'],
                ]);
                return;
            }
        }


        return new ViewModel(
            [
                'form' => $form,
            ]
        );

    }

}