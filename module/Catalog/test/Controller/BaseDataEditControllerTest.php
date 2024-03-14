<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace CatalogTest\Controller;

use Application\Service\Test\ApplicationTest;
use Catalog\Controller\BaseDataEditController;
use Catalog\Controller\Factory\BaseDataEditControllerFactory;
use Catalog\Entity\Ingredient;
use Catalog\Form\BaseDataEditForm;
use Catalog\Form\Fieldset\IngredientFieldset;
use User\Service\RegisteredService\UserAuthentication;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Zend\Mvc\Plugin\Prg\PostRedirectGet;
use Zend\Router\Http\RouteMatch;
use Zend\Router\Http\TreeRouteStack;
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\ServiceManager\ServiceManager;
use Zend\Session\Container;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use  Zend\Mvc\Controller\PluginManager;

class BaseDataEditControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;
    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;


    public function setUp()
    {
        // The module configuration should still be applicable for tests.
        // You can override configuration here with test case specific values,
        // such as sample view templates, path stacks, module_listener_options,
        // etc.
        $configOverrides = [];


        $this->setTraceError(true);

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));

        parent::setUp();

    }


    public function prepare()
    {
        $userAuth = $this->getApplicationServiceLocator()->get(UserAuthentication::class);
        $authAdapter = $userAuth->getAuthAdapter();
        $authAdapter->setIdentity('admin');
        $authAdapter->setCredential(md5('admin'));
        $userAuth->getAuthService()->authenticate();

        $serviceManager = $this->getApplicationServiceLocator();

        $this->request = new Request();
        $this->routeMatch = new RouteMatch([
            'controller' => 'Catalog\Controller\BaseDataEditController',
            'action' => 'ingredient',
            'id' => 'new']);

        $this->event = new MvcEvent();
        $config = $serviceManager->get('Config');
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = TreeRouteStack::factory($routerConfig);
        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);


        $applicationService = new ApplicationTest($serviceManager = $this->getApplicationServiceLocator()->get('application'));
        $applicationService->setMvcEvent($this->event);

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('application', $applicationService);

        //echo $serviceManager->get('application')->getMvcEvent()->getRouteMatch()->getParam('action');

        $controllerFactory = new BaseDataEditControllerFactory();
        $this->controller = $controllerFactory->__invoke($this->getApplicationServiceLocator(), BaseDataEditController::class);
        $this->controller->setEvent($this->event);


        //$this->controller->getPluginManager()
         //   ->setInvokableClass('prg', PostRedirectGet::class)
         //   ->setInvokableClass('flashmessenger', FlashMessenger::class);

    }

    public function testIndexActionCanBeAccessed()
    {
        $this->prepare();
        Application::init();
        $this->dispatch('/catalog/basedata/ingredient/new/edit');
        $this->assertResponseStatusCode(200);


    }


/*
     public function testIndexActionCanBeAccessed()
     {
         $this->prepare();

         $this->routeMatch->setParam('controller', 'Catalog\Controller\BaseDataEditController');
         $this->routeMatch->setParam('action', 'ingredient');
         $this->routeMatch->setParam('id', 'new');

         $result = $this->dispatch($this->request);
         $response = $this->controller->getResponse();

         $this->assertEquals(200, $response->getStatusCode());


     }



        public function testIngredientActionNew()
        {

            $this->prepare();

            $this->dispatch('/catalog/basedata/ingredient/new/edit', 'GET');

            $this->assertResponseStatusCode(200);
            $this->assertNotQuery('.alert-danger');
            $this->assertQuery('h1');
            $this->assertQuery('.btn .btn-primary .btn-lg');

            $this->assertQueryCountMin('.form-control', 3);
            $this->assertQueryCountMin('#ingredient-group > option', 2);
            $this->assertQueryCountMin('#ingredient-unit > option', 2);

            $this->assertQueryCount('label', 5);
            $this->assertQueryCount('.select2', 2);



            $entity = new Ingredient();
            $em = $this->getApplicationServiceLocator()->get(\Doctrine\ORM\EntityManager::class);
            $repository = $em->getRepository(Ingredient::class);
            $fieldset = new IngredientFieldset($em, $entity);


            $form = new BaseDataEditForm('ingredient', $fieldset, $em);
            $form->prepare();

            $container = new Container('prg_post1');
            $container->post = array(
                'security' => $form->get('security')->getValue(),
                'submit' => 'Feltöltés',
                'Ingredient' =>
                    array(
                        'id' =>  '',
                        'name' => 'teszt_ingredient',
                        'ingredientGroup' => '2',
                        'ingredientUnit' => '3',
                        'minimumAmount' => '20',
                        'moreInfo' => 'Egyéb infós cuccok',
                    )
            );

            $container->setExpirationHops(1, 'post');

           // $this->dispatch('/catalog/basedata/ingredient/new/edit', 'GET');
          //  $this->assertResponseStatusCode(200);

            $result   = $this->controller->dispatch('/catalog/basedata/ingredient/new/edit');
            $response = $this->controller->getResponse();

            $this->assertEquals(200, $response->getStatusCode());





        }
*/


    /*
                public function testIngredientActionEdit()
                {

                }

                public function testIngredientActionDelete()
                {

                }

                ////////

                public function testIngredientgroupActionNew()
                {


                    $this->dispatch('/catalog/basedata/ingredientgroup/new/edit', 'GET');
                    $this->assertResponseStatusCode(200);
                    $this->assertNotQuery('.alert-danger');
                    $this->assertQuery('h1');
                    $this->assertQuery('.btn .btn-primary .btn-lg');

                    $this->assertQueryCountMin('.form-control', 1);

                    $this->assertQueryCount('label', 1);


                }

                public function testIngredientgroupActionEdit()
                {

                }

                public function testIngredientgroupActionDelete()
                {

                }

                ////

                public function testIngredientunitActionNew()
                {


                    $this->dispatch('/catalog/basedata/ingredientunit/new/edit', 'GET');
                    $this->assertResponseStatusCode(200);
                    $this->assertNotQuery('.alert-danger');
                    $this->assertQuery('h1');
                    $this->assertQuery('.btn .btn-primary .btn-lg');

                    $this->assertQueryCountMin('.form-control', 2);

                    $this->assertQueryCount('label', 2);


                }

                public function testIngredientunitActionEdit()
                {

                }

                public function testIngredientunitActionDelete()
                {

                }

                ////

                public function testProductActionNew()
                {


                    $this->dispatch('/catalog/basedata/product/new/edit', 'GET');
                    $this->assertResponseStatusCode(200);
                    $this->assertNotQuery('.alert-danger');
                    $this->assertQuery('h1');
                    $this->assertQuery('.btn .btn-primary .btn-lg');

                    $this->assertQueryCount('.form-control', 5);
                    $this->assertQueryCount('.dataTable', 1);

                    $this->assertQueryCountMin('#product-product-group > option', 2);
                    $this->assertQueryCountMin('#product-vat-group > option', 2);
                    $this->assertQueryCountMin('.productContainIngredient-select-row > option', 2);

                    $this->assertQueryCount('label', 9);
                    $this->assertQueryCount('.select2', 3);


                }

                public function testProductActionEdit()
                {

                }

                public function testProductActionDelete()
                {

                }

                /////////
                public function testProductgroupActionNew()
                {


                    $this->dispatch('/catalog/basedata/productgroup/new/edit', 'GET');
                    $this->assertResponseStatusCode(200);
                    $this->assertNotQuery('.alert-danger');
                    $this->assertQuery('h1');
                    $this->assertQuery('.btn .btn-primary .btn-lg');

                    $this->assertQueryCountMin('.form-control', 1);

                    $this->assertQueryCount('label', 1);

                }

                public function testProductgroupActionEdit()
                {

                }

                public function testProductgroupActionDelete()
                {

                }

                /////////////
                public function testStorageActionNew()
                {


                    $this->dispatch('/catalog/basedata/storage/new/edit', 'GET');
                    $this->assertResponseStatusCode(200);
                    $this->assertNotQuery('.alert-danger');
                    $this->assertQuery('h1');
                    $this->assertQuery('.btn .btn-primary .btn-lg');

                    $this->assertQueryCountMin('.form-control', 1);


                    $this->assertQueryCountMin('#storage-parent-storage-parent > option', 2);
                    $this->assertQueryCountMin('#storage-storage-type > option', 2);
                    $this->assertQueryCountMin('#storage-supplier > option', 2);

                    $this->assertQueryCount('label', 4);
                    $this->assertQueryCount('.select2', 3);

                }

                public function testStorageActionEdit()
                {

                }

                public function testStorageActionDelete()
                {

                }

                ////////

                public function testStoragetypeActionNew()
                {


                    $this->dispatch('/catalog/basedata/storagetype/new/edit', 'GET');
                    $this->assertResponseStatusCode(200);
                    $this->assertNotQuery('.alert-danger');
                    $this->assertQuery('h1');
                    $this->assertQuery('.btn .btn-primary .btn-lg');

                    $this->assertQueryCountMin('.form-control', 1);

                    $this->assertQueryCount('label', 1);
                }

                public function testStoragetypeActionEdit()
                {

                }

                public function testStoragetypeActionDelete()
                {

                }

                ////////

                public function testSupplierActionNew()
                {



                    $this->dispatch('/catalog/basedata/supplier/new/edit', 'GET');
                    $this->assertResponseStatusCode(200);
                    $this->assertNotQuery('.alert-danger');
                    $this->assertQuery('h1');
                    $this->assertQuery('.btn .btn-primary .btn-lg');

                    $this->assertQueryCountMin('.form-control', 8);

                    $this->assertQueryCount('label', 8);

                }

                public function testSupplierActionEdit()
                {

                }

                public function testSupplierActionDelete()
                {

                }

                //////////////
                public function testVatgroupActionNew()
                {

                    $this->dispatch('/catalog/basedata/vatgroup/new/edit', 'GET');
                    $this->assertResponseStatusCode(200);
                    $this->assertNotQuery('.alert-danger');
                    $this->assertQuery('h1');
                    $this->assertQuery('.btn .btn-primary .btn-lg');

                    $this->assertQueryCountMin('.form-control', 2);

                    $this->assertQueryCount('label', 2);
                }

                public function testVatgroupActionEdit()
                {

                }

                public function testVatgroupActionDelete()
                {

                }

                ////////////////
                public function testDeleteContentNew()
                {

                }

                public function testDeleteContentEdit()
                {

                }

                public function testDeleteContentDelete()
                {

                }
            */
}
