<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.07.
 * Time: 0:38
 */

namespace Transaction\Controller\Factory;


use Application\Model\RetrieveByDatatableModel;
use Catalog\Entity\Ingredient;
use Transaction\Entity\Stock;
use Transaction\Entity\StockTransaction;
use Transaction\Form\Fieldset\IngrTransactionFieldset;
use Transaction\Form\IngrTransactionForm;
use Transaction\Model\StockTransaction\ItemCollectionModel;
use Transaction\Service\StockTransaction\ItemTransactionService;
use Transaction\Service\StockTransaction\StockCorrectionTransactionService;
use Transaction\Model\StockTransaction\ItemTransactionEventModel;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class IngrTransactionControllerFactory implements FactoryInterface
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

        $misc['ingrTransactionService'] = new ItemTransactionService($em);

        $misc['actionName'] = $actionName;
        $misc['showWithDatatable'] = new RetrieveByDatatableModel();
        $entity = new StockTransaction();
        $misc['fieldset'] = new IngrTransactionFieldset($em, $entity);
        $misc['form'] = new IngrTransactionForm('IngrTransactionForm', $misc['fieldset']);

        switch ($actionName) {
            case 'stockcorrection':
                $misc['ingrTransactionService'] = new StockCorrectionTransactionService($em);
                $misc['ingrTransactionService']->setItemTransactionServices(new ItemTransactionService($em));
                $misc['ingrTransactionService']->setStockEntityName(Stock::class);
                $misc['ingrTransactionService']->setStuffEntityFullName(Ingredient::class);
                break;
        }

        $misc['ingrTransactionService']->setTransactionEventModel(new ItemTransactionEventModel());
        $misc['ingrTransactionService']->setItemCollectionModel(new ItemCollectionModel());
        $misc['ingrTransactionService']->setEventName('triggerIngredientTransactionEvent');
        $misc['ingrTransactionService']->setStuffEntityFullName(Ingredient::class);


        if (!class_exists($controllerName))
            throw new ServiceNotFoundException("Requested controller name " . $controllerName . " does not exists.");

        return new $controllerName($serviceManager, $misc);
    }
}