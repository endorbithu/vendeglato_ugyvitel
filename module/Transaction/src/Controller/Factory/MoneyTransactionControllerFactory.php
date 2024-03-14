<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.07.
 * Time: 0:38
 */

namespace Transaction\Controller\Factory;


use Application\Model\RetrieveByDatatableModel;
use Catalog\Entity\Money;
use Transaction\Service\StockTransaction\ItemTransactionService;
use Transaction\Service\StockTransaction\StockCorrectionTransactionService;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Transaction\Entity\MoneyStock;
use Transaction\Entity\StockTransaction;
use Transaction\Form\Fieldset\MoneyTransactionFieldset;
use Transaction\Form\MoneyTransactionForm;
use Transaction\Model\StockTransaction\ItemCollectionModel;
use Transaction\Model\StockTransaction\ItemTransactionEventModel;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class MoneyTransactionControllerFactory implements FactoryInterface
{


    /**
     * Create an object
     *
     * @param  ContainerInterface $serviceManager
     * @param  string $controllerName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $serviceManager, $controllerName, array $options = null)
    {

        $misc = [];
        $em = $serviceManager->get('Doctrine\ORM\EntityManager');
        $actionName = $serviceManager->get('application')->getMvcEvent()->getRouteMatch()->getParam('action');

        $misc['moneyTransactionService'] = new ItemTransactionService($em);

        $misc['actionName'] = $actionName;
        $misc['showWithDatatable'] = new RetrieveByDatatableModel();
        $entity = new StockTransaction();
        $misc['fieldset'] = new MoneyTransactionFieldset($em, $entity);
        $misc['form'] = new MoneyTransactionForm('MoneyTransactionForm', $misc['fieldset']);

        switch ($actionName) {
            case 'moneystockcorrection':
                $misc['moneyTransactionService'] = new StockCorrectionTransactionService($em);
                $misc['moneyTransactionService']->setItemTransactionServices(new ItemTransactionService($em));
                $misc['moneyTransactionService']->setStockEntityName(MoneyStock::class);
                $misc['moneyTransactionService']->setStuffEntityFullName(Money::class);
                break;
        }

        $misc['moneyTransactionService']->setTransactionEventModel(new ItemTransactionEventModel());
        $misc['moneyTransactionService']->setItemCollectionModel(new ItemCollectionModel());
        $misc['moneyTransactionService']->setEventName('triggerMoneyTransactionEvent');
        $misc['moneyTransactionService']->setStuffEntityFullName(Money::class);



        if (!class_exists($controllerName))
            throw new ServiceNotFoundException("Requested controller name " . $controllerName . " does not exists.");

        return new $controllerName($serviceManager, $misc);
    }
}