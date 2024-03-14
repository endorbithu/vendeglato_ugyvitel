<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.30.
 * Time: 16:48
 */

namespace Order\Service\Income;


use Catalog\Entity\Product;
use Doctrine\ORM\EntityManager;
use DoctrineORMModule\Proxy\__CG__\Catalog\Entity\Storage;
use Order\Entity\DailyIncome;
use Order\Entity\OrderItemInStorage;

class IncomeListener
{

    protected $transactionEventModel;
    protected $sm;
    protected $em;
    protected $toStorageType;
    protected $fromStorageType;

    public function __construct($sm, $entities)
    {
        $this->sm = $sm;
        $this->em = $sm->get(EntityManager::class);
        $this->entityNames = $entities;

    }


    public function __invoke($transactionModel)
    {

        //TODO: #163 lecsekkolni a dbben az értékeket!

        $this->transactionEventModel = $transactionModel->getParams()['transactionEventModel'];
        $this->toStorageType = $this->em->getRepository(Storage::class)->find($this->transactionEventModel->getToStorage())->getStorageType()->getStringId();
        $this->fromStorageType = $this->em->getRepository(Storage::class)->find($this->transactionEventModel->getFromStorage())->getStorageType()->getStringId();
        if (!($this->toStorageType == 'consumption' || $this->fromStorageType == 'consumption')) return;

        if (empty($this->em->getRepository(DailyIncome::class)->getTodayIncome())) {
            $newDailyIncome = new $this->entityNames['DailyIncome']();
            $newDailyIncome->setDateTime(new \DateTime());
            $newDailyIncome->setNetIncome(0);
            $newDailyIncome->setVatAmount(0);
            $this->em->persist($newDailyIncome);
            $this->em->flush();
        }


        $this->dailyIncomeCorrection();

    }

    protected function dailyIncomeCorrection()
    {
        $signAndOne = ($this->fromStorageType === 'consumption') ? -1 : 1;



        $orderItems = $this->transactionEventModel->getOrderItemCollectionModel()->getOrderItems();
        foreach ($orderItems as $orderItemKey => $productAndAmount) {
            foreach ($productAndAmount as $productId => $amount) {
                $vatRate = $this->em->getRepository(Product::class)->find($productId)->getVatGroup()->getVatValue();
                $grosIncome = $this->em->getRepository(OrderItemInStorage::class)->find($orderItemKey)->getPrice();
                $netIncome = $signAndOne * ($grosIncome / (1 + $vatRate));
                $vatAmount = $signAndOne * ($grosIncome - $netIncome);

                $todayIncome = $this->em->getRepository(DailyIncome::class)->getTodayIncome()[0];
                $todayIncome->setNetIncome($todayIncome->getNetIncome() + $netIncome);
                $todayIncome->setVatAmount($todayIncome->getVatAmount() + $vatAmount);
                $todayIncome->setDateTime(new \DateTime());
                $this->em->flush();
            }
        }
    }


}