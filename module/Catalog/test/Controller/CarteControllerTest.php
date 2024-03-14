<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace CatalogTest\Controller;

use Application\Controller\IndexController;
use DoctrineORMModule\Options\EntityManager;
use DoctrineORMModule\Service\EntityManagerAliasCompatFactory;
use Zend\Db\TableGateway\Exception\RuntimeException;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class CarteControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;

    public function setUp()
    {
        // The module configuration should still be applicable for tests.
        // You can override configuration here with test case specific values,
        // such as sample view templates, path stacks, module_listener_options,
        // etc.
        $configOverrides = array(
            'doctrine' => array(
                'connection' => array(
                    'orm_default' => array(
                        'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                        'params' => array(
                            'host' => '127.0.0.1',
                            'port' => '3306', //'mysql',
                            'user' => 'root',
                            'password' => 'swingminoa',
                            'dbname' => 'vendeglato_test',

                            //Ez fontos az utf-8 miatt!
                            'driverOptions' => array(
                                1002 => 'SET NAMES utf8')
                        )
                    )
                )
            ));

        $this->setApplicationConfig(ArrayUtils::merge(
        //behúzzuk az application config fájlt
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));






        $services   =  $this->getApplicationServiceLocator(); // see comment below


        $testDoctrine = new EntityManagerAliasCompatFactory($services,'Doctrine\ORM\EntityManager');
        
        $services->setAllowOverride(true);
        $services->setService('config', $config);
        $services->setService('Doctrine\ORM\EntityManager', $testDoctrine);
        $services->setAllowOverride(false);




        echo $this->getApplicationServiceLocator()->get('Doctrine\ORM\EntityManager')->getConnection()->getParams()['dbname'];

        parent::setUp();

        /*
        $services = $this->getApplicationServiceLocator();
        $config = $services->get('config');
       // unset($config['doctrine']);
        $services->setAllowOverride(true);
        $services->setService('config', $config);
        $services->setAllowOverride(false);
        */
    }


    public function testIndexActionCanBeAccessed()
    {
        $this->dispatch('/', 'GET');


        //ezeket várjuk el:
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('application');
        $this->assertControllerName(IndexController::class); // as specified in router's controller name alias
        $this->assertControllerClass('IndexController');
        $this->assertMatchedRouteName('index');
    }

    public function testIndexActionViewModelTemplateRenderedWithinLayout()
    {
        $this->dispatch('', 'GET');
        $this->assertQuery('.container .jumbotron');
    }

    public function testInvalidRouteDoesNotCrash()
    {
        $this->dispatch('/invalid/route', 'GET');
        $this->assertResponseStatusCode(404);
    }
}
