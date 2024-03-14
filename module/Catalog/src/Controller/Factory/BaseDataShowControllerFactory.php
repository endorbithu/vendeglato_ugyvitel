<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.07.
 * Time: 0:38
 */

namespace Catalog\Controller\Factory;


use Catalog\Entity\Ingredient;
use Catalog\Entity\IngredientGroup;
use Catalog\Entity\IngredientUnit;
use Catalog\Entity\Money;
use Catalog\Entity\MoneyGroup;
use Catalog\Entity\MoneyUnit;
use Catalog\Entity\Product;
use Catalog\Entity\ProductGroup;
use Catalog\Entity\Storage;
use Catalog\Entity\StorageType;
use Catalog\Entity\Supplier;
use Catalog\Entity\Tool;
use Catalog\Entity\ToolGroup;
use Catalog\Entity\ToolUnit;
use Catalog\Entity\VatGroup;
use Catalog\Model\BaseData\BaseDataControllerModel;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class BaseDataShowControllerFactory implements FactoryInterface
{
    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $controllerModel = new BaseDataControllerModel();
        $em = $container->get('Doctrine\ORM\EntityManager');
        $actionName = $container->get('application')->getMvcEvent()->getRouteMatch()->getParam('action'); //pl ingredient van megadva paraméterbe
        $id = empty($container->get('application')->getMvcEvent()->getRouteMatch()->getParam('id')) ? '' : (int)$container->get('application')->getMvcEvent()->getRouteMatch()->getParam('id');

        $controllerModel->setActionName($actionName);
        $controllerModel->setId($id);



        switch ($actionName) {
            //ha pl alapanyagok, akkor ingredient stb, ha esetleg több minden kell az egyes elemekből, akkor lehet arrayba is rendezni őket
            case 'ingredient':
                $controllerModel->setRepository($em->getRepository(Ingredient::class));
                $controllerModel->setTitle('alapanyag');
                break;

            case 'ingredientgroup':
                $controllerModel->setRepository($em->getRepository(IngredientGroup::class));
                $controllerModel->setTitle('alapanyag csoport');
                break;

            case 'ingredientunit':
                $controllerModel->setRepository($em->getRepository(IngredientUnit::class));
                $controllerModel->setTitle('alapanyag mértékegység');
                break;

            case 'tool':
                $controllerModel->setRepository($em->getRepository(Tool::class));
                $controllerModel->setTitle('eszköz');
                break;

            case 'toolgroup':
                $controllerModel->setRepository($em->getRepository(ToolGroup::class));
                $controllerModel->setTitle('eszköz csoport');
                break;

            case 'toolunit':
                $controllerModel->setRepository($em->getRepository(ToolUnit::class));
                $controllerModel->setTitle('eszköz mértékegység');
                break;
            case 'money':
                $controllerModel->setRepository($em->getRepository(Money::class));
                $controllerModel->setTitle('pénzeszköz');
                break;

            case 'moneygroup':
                $controllerModel->setRepository($em->getRepository(MoneyGroup::class));
                $controllerModel->setTitle('pénzeszköz csoport');
                break;

            case 'moneyunit':
                $controllerModel->setRepository($em->getRepository(MoneyUnit::class));
                $controllerModel->setTitle('pénznemek');
                break;

            case 'product':
                $controllerModel->setRepository($em->getRepository(Product::class));
                $controllerModel->setTitle('termék');
                break;

            case 'productgroup':
                $controllerModel->setRepository($em->getRepository(ProductGroup::class));
                $controllerModel->setTitle('termék csoport');
                break;

            case 'storage':
                $controllerModel->setRepository($em->getRepository(Storage::class));
                $controllerModel->setTitle('készlet');
                break;

            case 'storagetype':
                $controllerModel->setRepository($em->getRepository(StorageType::class));
                $controllerModel->setTitle('termék csoport');
                break;

            case 'supplier':
                $controllerModel->setRepository($em->getRepository(Supplier::class));
                $controllerModel->setTitle('beszállító');
                break;

            case 'vatgroup':
                $controllerModel->setRepository($em->getRepository(VatGroup::class));
                $controllerModel->setTitle('ÁFA csoport');
                break;
        }


        if (!class_exists($requestedName))
            throw new ServiceNotFoundException("Requested controller name " . $requestedName . " does not exists.");

        return new $requestedName($container, $controllerModel);
    }
}