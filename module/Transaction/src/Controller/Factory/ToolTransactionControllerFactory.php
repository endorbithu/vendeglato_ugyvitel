<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.07.
 * Time: 0:38
 */

namespace Transaction\Controller\Factory;


use Application\Model\RetrieveByDatatableModel;
use Catalog\Entity\Tool;
use Transaction\Service\StockTransaction\ItemTransactionService;
use Transaction\Service\StockTransaction\StockCorrectionTransactionService;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Transaction\Entity\StockTransaction;
use Transaction\Entity\ToolStock;
use Transaction\Form\Fieldset\ToolTransactionFieldset;
use Transaction\Form\ToolTransactionForm;
use Transaction\Model\StockTransaction\ItemCollectionModel;
use Transaction\Model\StockTransaction\ItemTransactionEventModel;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class ToolTransactionControllerFactory implements FactoryInterface
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
        $actionName = $serviceManager->get('application')->getMvcEvent()->getRouteMatch()->getParam('action'); //pl ingredient van megadva paraméterbe

        $misc['toolTransactionService'] = new ItemTransactionService($em);

        $misc['actionName'] = $actionName;
        $misc['showWithDatatable'] = new RetrieveByDatatableModel();
        $entity = new StockTransaction();
        $misc['fieldset'] = new ToolTransactionFieldset($em, $entity);
        $misc['form'] = new ToolTransactionForm('ToolTransactionForm', $misc['fieldset']);

        switch ($actionName) {
            case 'toolstockcorrection':
                $misc['toolTransactionService'] = new StockCorrectionTransactionService($em);
                $misc['toolTransactionService']->setItemTransactionServices(new ItemTransactionService($em));
                $misc['toolTransactionService']->setStockEntityName(ToolStock::class);
                $misc['toolTransactionService']->setStuffEntityFullName(Tool::class);
                break;
        }

        $misc['toolTransactionService']->setTransactionEventModel(new ItemTransactionEventModel());
        $misc['toolTransactionService']->setItemCollectionModel(new ItemCollectionModel());
        $misc['toolTransactionService']->setEventName('triggerToolTransactionEvent');
        $misc['toolTransactionService']->setStuffEntityFullName(Tool::class); //jó lesz az ingredient hiány többlet tároló is



        if (!class_exists($controllerName))
            throw new ServiceNotFoundException("Requested controller name " . $controllerName . " does not exists.");

        return new $controllerName($serviceManager, $misc);
    }
}