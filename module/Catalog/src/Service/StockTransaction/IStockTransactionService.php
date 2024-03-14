<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.14.
 * Time: 7:51
 */

namespace Catalog\Service\StockTransaction;


use Catalog\Model\StockTransaction\IStockTransactionEvent;
use Zend\EventManager\EventManagerAwareInterface;

interface IStockTransactionService extends EventManagerAwareInterface
{

    public function __construct($em);

    public function setTransactionEventModel(IStockTransactionEvent $eventModel);

    public function setPostData($postData);

    public function adjustTransactionEventModel(\DateTime $dateTime = null);

    public function triggerStockTransactionEvent();
    

}