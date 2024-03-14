<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.07.
 * Time: 0:38
 */

namespace Catalog\Controller\Factory;


use Application\Service\Crud\ManipulateEntityByForm;
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
use Catalog\Form\BaseDataEditForm;
use Catalog\Form\Fieldset\IngredientFieldset;
use Catalog\Form\Fieldset\IngredientGroupFieldset;
use Catalog\Form\Fieldset\IngredientUnitFieldset;
use Catalog\Form\Fieldset\MoneyFieldset;
use Catalog\Form\Fieldset\MoneyGroupFieldset;
use Catalog\Form\Fieldset\MoneyUnitFieldset;
use Catalog\Form\Fieldset\ProductFieldset;
use Catalog\Form\Fieldset\ProductGroupFieldset;
use Catalog\Form\Fieldset\StorageFieldset;
use Catalog\Form\Fieldset\StorageTypeFieldset;
use Catalog\Form\Fieldset\SupplierFieldset;
use Catalog\Form\Fieldset\ToolFieldset;
use Catalog\Form\Fieldset\ToolGroupFieldset;
use Catalog\Form\Fieldset\ToolUnitFieldset;
use Catalog\Form\Fieldset\VatGroupFieldset;
use Catalog\Model\BaseData\BaseDataEditControllerModel;
use Application\Service\Crud\DeleteEntityItems;
use Catalog\Service\BaseDataEdit\ProductDataEditService;
use Catalog\Service\BaseDataEdit\StorageTypeDataEditService;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class BaseDataEditControllerFactory implements FactoryInterface
{


    /**
     * Create an object Itt állítjuk össze az egyes törzsadatok módosításához szükséges objektumokat => Form (filedset),
     * Entity, Repository stb és CRUD műveletes osztály (BaseDataEditService) az
     * ManipulateEntityByForm (Application)-ből örökölve ami végzi az effektív művleteket
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
        $controllerModel = new BaseDataEditControllerModel();
        $em = $container->get('Doctrine\ORM\EntityManager');

        $actionName = $container->get('application')->getMvcEvent()->getRouteMatch()->getParam('action');
        $id = \Zend\Filter\StaticFilter::execute($container->get('application')->getMvcEvent()->getRouteMatch()->getParam('id'), 'Alnum');

        $controllerModel->setId($id);
        $controllerModel->setActionName($actionName);

        if ($id === 'delete') $controllerModel->setDeleteFromDbService(new DeleteEntityItems());


        //megvizsgáljuk optionbe mi van megadva ezt a routeból nyerjük
        switch ($actionName) {
            //ha pl alapanyagok, akkor ingredient stb, ha esetleg több minden kell az egyes elemekből, akkor lehet arrayba is rendezni őket
            case 'ingredient':
                $entity = new Ingredient();
                $entity->setServiceManager($container); //a doctrine csak repositoryzás közben injectálja be
                $fieldset = new IngredientFieldset($em, $entity);
                $form = new BaseDataEditForm($actionName, $fieldset, $em);
                $controllerModel->setForm($form);
                $controllerModel->setEntity($entity);
                $controllerModel->setRepository($em->getRepository(Ingredient::class));
                $controllerModel->setModifyDbByFormService(new ManipulateEntityByForm($em));
                break;

            case 'ingredientgroup':
                $entity = new IngredientGroup();
                $fieldset = new IngredientGroupFieldset($em, $entity);
                $form = new BaseDataEditForm($actionName, $fieldset, $em);
                $controllerModel->setForm($form);
                $controllerModel->setEntity($entity);
                $controllerModel->setRepository($em->getRepository(IngredientGroup::class));
                $controllerModel->setModifyDbByFormService(new ManipulateEntityByForm($em));
                break;

            case 'ingredientunit':
                $entity = new IngredientUnit();
                $fieldset = new IngredientUnitFieldset($em, $entity);
                $form = new BaseDataEditForm($actionName, $fieldset, $em);
                $controllerModel->setForm($form);
                $controllerModel->setEntity($entity);
                $controllerModel->setRepository($em->getRepository(IngredientUnit::class));
                $controllerModel->setModifyDbByFormService(new ManipulateEntityByForm($em));
                break;

            case 'tool':
                $entity = new Tool();
                $entity->setServiceManager($container); //a doctrine csak repositoryzás közben injectálja be
                $fieldset = new ToolFieldset($em, $entity);
                $form = new BaseDataEditForm($actionName, $fieldset, $em);
                $controllerModel->setForm($form);
                $controllerModel->setEntity($entity);
                $controllerModel->setRepository($em->getRepository(Tool::class));
                $controllerModel->setModifyDbByFormService(new ManipulateEntityByForm($em));
                break;

            case 'toolgroup':
                $entity = new ToolGroup();
                $fieldset = new ToolGroupFieldset($em, $entity);
                $form = new BaseDataEditForm($actionName, $fieldset, $em);
                $controllerModel->setForm($form);
                $controllerModel->setEntity($entity);
                $controllerModel->setRepository($em->getRepository(ToolGroup::class));
                $controllerModel->setModifyDbByFormService(new ManipulateEntityByForm($em));
                break;

            case 'toolunit':
                $entity = new ToolUnit();
                $fieldset = new ToolUnitFieldset($em, $entity);
                $form = new BaseDataEditForm($actionName, $fieldset, $em);
                $controllerModel->setForm($form);
                $controllerModel->setEntity($entity);
                $controllerModel->setRepository($em->getRepository(ToolUnit::class));
                $controllerModel->setModifyDbByFormService(new ManipulateEntityByForm($em));
                break;

            case 'money':
                $entity = new Money();
                $entity->setServiceManager($container); //a doctrine csak repositoryzás közben injectálja be
                $fieldset = new MoneyFieldset($em, $entity);
                $form = new BaseDataEditForm($actionName, $fieldset, $em);
                $controllerModel->setForm($form);
                $controllerModel->setEntity($entity);
                $controllerModel->setRepository($em->getRepository(Money::class));
                $controllerModel->setModifyDbByFormService(new ManipulateEntityByForm($em));
                break;

            case 'moneygroup':
                $entity = new MoneyGroup();
                $fieldset = new MoneyGroupFieldset($em, $entity);
                $form = new BaseDataEditForm($actionName, $fieldset, $em);
                $controllerModel->setForm($form);
                $controllerModel->setEntity($entity);
                $controllerModel->setRepository($em->getRepository(MoneyGroup::class));
                $controllerModel->setModifyDbByFormService(new ManipulateEntityByForm($em));
                break;

            case 'moneyunit':
                $entity = new MoneyUnit();
                $fieldset = new MoneyUnitFieldset($em, $entity);
                $form = new BaseDataEditForm($actionName, $fieldset, $em);
                $controllerModel->setForm($form);
                $controllerModel->setEntity($entity);
                $controllerModel->setRepository($em->getRepository(MoneyUnit::class));
                $controllerModel->setModifyDbByFormService(new ManipulateEntityByForm($em));
                break;


            case 'product':
                $entity = new Product();
                $entity->setServiceManager($container); //a doctrine csak repositoryzás közben injectálja be
                $fieldset = new ProductFieldset($em, $entity);
                $form = new BaseDataEditForm($actionName, $fieldset, $em);
                $controllerModel->setForm($form);
                $controllerModel->setEntity($entity);
                $controllerModel->setRepository($em->getRepository(Product::class));
                $controllerModel->setModifyDbByFormService(new ProductDataEditService($em));
                break;

            case 'productgroup':
                $entity = new ProductGroup();
                $fieldset = new ProductGroupFieldset($em, $entity);
                $form = new BaseDataEditForm($actionName, $fieldset, $em);
                $controllerModel->setForm($form);
                $controllerModel->setEntity($entity);
                $controllerModel->setRepository($em->getRepository(ProductGroup::class));
                $controllerModel->setModifyDbByFormService(new ManipulateEntityByForm($em));
                break;

            case 'storage':
                $entity = new Storage();
                $entity->setServiceManager($container); //a doctrine csak repositoryzás közben injectálja be
                $fieldset = new StorageFieldset($em, $entity);
                $form = new BaseDataEditForm($actionName, $fieldset, $em);
                $controllerModel->setForm($form);
                $controllerModel->setEntity($entity);
                $controllerModel->setRepository($em->getRepository(Storage::class));
                $controllerModel->setModifyDbByFormService(new ManipulateEntityByForm($em));
                break;

            case 'storagetype':
                $entity = new StorageType();
                $fieldset = new StorageTypeFieldset($em, $entity);
                $form = new BaseDataEditForm($actionName, $fieldset, $em);
                $controllerModel->setForm($form);
                $controllerModel->setEntity($entity);
                $controllerModel->setRepository($em->getRepository(StorageType::class));
                $controllerModel->setModifyDbByFormService(new StorageTypeDataEditService($em));
                break;

            case 'supplier':
                $entity = new Supplier();
                $fieldset = new SupplierFieldset($em, $entity);
                $form = new BaseDataEditForm($actionName, $fieldset, $em);
                $controllerModel->setForm($form);
                $controllerModel->setEntity($entity);
                $controllerModel->setRepository($em->getRepository(Supplier::class));
                $controllerModel->setModifyDbByFormService(new ManipulateEntityByForm($em));
                break;

            case 'vatgroup':
                $entity = new VatGroup();
                $fieldset = new VatGroupFieldset($em, $entity);
                $form = new BaseDataEditForm($actionName, $fieldset, $em);
                $controllerModel->setForm($form);
                $controllerModel->setEntity($entity);
                $controllerModel->setRepository($em->getRepository(VatGroup::class));
                $controllerModel->setModifyDbByFormService(new ManipulateEntityByForm($em));
                break;
        }


        if (!class_exists($requestedName))
            throw new ServiceNotFoundException("Requested controller name " . $requestedName . " does not exists.");

        return new $requestedName($container, $controllerModel);
    }
}