<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.07.
 * Time: 0:38
 */

namespace Order\Controller\Factory;


use Application\Model\RetrieveByDatatableModel;
use Transaction\Entity\StockTransaction;
use Catalog\Entity\Product;
use Catalog\Entity\Storage;
use Transaction\Form\Fieldset\IngrTransactionFieldset;
use Transaction\Form\IngrTransactionForm;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Order\Model\ProductTransaction\OrderItemCollectionModel;
use Order\Model\ProductTransaction\OrderItemTransactionEventModel;
use Order\Model\ProductTransaction\ProductCollectionModel;
use Order\Model\ProductTransaction\ProductTransactionEventModel;
use Order\Service\ProductTransaction\OrderItemParentStorageTransactionService;
use Order\Service\ProductTransaction\OrderItemTransactionService;
use Order\Service\ProductTransaction\NewOrderParentStorageTransactionService;
use Order\Service\ProductTransaction\NewOrderTransactionService;
use Order\Service\ProductTransaction\ParentStorageAwareProductService;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class ProductTransactionControllerFactory implements FactoryInterface
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
        $em = $serviceManager->get(EntityManager::class);
        $actionName = $serviceManager->get('application')->getMvcEvent()->getRouteMatch()->getParam('action');
        $storageFrom = $serviceManager->get('application')->getMvcEvent()->getRouteMatch()->getParam('from');
        $storageTo = $serviceManager->get('application')->getMvcEvent()->getRouteMatch()->getParam('to');



        $misc['actionName'] = $actionName;
        $misc['showWithDatatable'] = new RetrieveByDatatableModel();

        //Orernél van az a kivétel, hogy nem  productstorage-ből dolgozunk, hanem az ingredientekből állítja
        // össze a productokat, és itt csak a from lehet parent-storage mert csak destination felé mozoghat az anyag
        if ($actionName == 'order') {
            if (!empty($storages = $em->getRepository(Storage::class)->getChildStorage($storageFrom))) {
                $misc['productTransactionService'] = new NewOrderParentStorageTransactionService($em);
                $misc['productTransactionService']->setParentAwareStorageProductService(new ParentStorageAwareProductService($em));
                $misc['productTransactionService']->setProductTransactionService(new NewOrderTransactionService($em));
            } else {
                $misc['productTransactionService'] = new NewOrderTransactionService($em);
            }

            $misc['productTransactionService']->setTransactionEventModel(new ProductTransactionEventModel());
            $misc['productTransactionService']->setProductCollectionModel(new ProductCollectionModel($em->getRepository(Product::class)));

        } else {
            //Ha visszavételezés, selejtezés, áthelyezés stb
            //Attól még, hogy a productokat mozgatjuk, lehet parent a toStorage visszavételezésnél, hiába a
            //productstorage-ben lesz letárolva a helyi készlet standhelyei nőni fognak
            if (!empty($storages = $em->getRepository(Storage::class)->getChildStorage($storageFrom))
                xor (!empty($storages = $em->getRepository(Storage::class)->getChildStorage($storageTo)))
            ) {
                $misc['productTransactionService'] = new OrderItemParentStorageTransactionService($em);
                $misc['productTransactionService']->setParentAwareStorageProductService(new ParentStorageAwareProductService($em));
                $misc['productTransactionService']->setOrderItemTransactionService(new OrderItemTransactionService($em));
            } else {
                $misc['productTransactionService'] = new OrderItemTransactionService($em);
            }

            $misc['productTransactionService']->setTransactionEventModel(new OrderItemTransactionEventModel());
            $misc['productTransactionService']->setOrderItemCollectionModel(new OrderItemCollectionModel($em->getRepository(Product::class)));

        }


        //TODO: #158 ezt át kell nevezni, mert univerzális nem csak ingredient
        $entity = new StockTransaction();
        $misc['fieldset'] = new IngrTransactionFieldset($em, $entity);
        $misc['form'] = new IngrTransactionForm('IngrTransactionForm', $misc['fieldset']);


        if (!class_exists($controllerName))
            throw new ServiceNotFoundException("Requested controller name " . $controllerName . " does not exists.");

        return new $controllerName($serviceManager, $misc);
    }
}