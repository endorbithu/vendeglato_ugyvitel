<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ApplicationTest\Controller;

use Application\Controller\TestController;
use User\Service\RegisteredService\UserAuthentication;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class TestControllerTest extends AbstractHttpControllerTestCase
{
    public function setUp()
    {
        // The module configuration should still be applicable for tests.
        // You can override configuration here with test case specific values,
        // such as sample view templates, path stacks, module_listener_options,
        // etc.
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));

        parent::setUp();
    }


    protected function mockLogin()
    {
        $userAuth = $this->getApplicationServiceLocator()->get(UserAuthentication::class);
        $authAdapter = $userAuth->getAuthAdapter();
        $authAdapter->setIdentity('admin');
        $authAdapter->setCredential(md5('admin'));
        $userAuth->getAuthService()->authenticate();


    }

    protected function mockDatabase()
    {
        //nem jön össze, mert a progi a defaultot használja így is jó
        //$serviceManager = $this->getApplicationServiceLocator();
        //$serviceManager->setAllowOverride(true);
        //$serviceManager->setService(EntityManager::class,$this->getApplicationServiceLocator()->get('doctrine.entitymanager.orm_test') );
        //$serviceManager->setAllowOverride(false);
    }


    ///////////////////////////////////////////

    public function testUnittestActionCanBeAccessed()
    {
        $this->mockLogin();
        //$this->mockDatabase();

        $this->dispatch('/test/unittest', 'GET');
        $this->assertResponseStatusCode(200);

        //Request Assertions
        //$this->assertModulesLoaded([array $modules); //A megadott modul(oka)t betölti-e az applicarion
        $this->assertModuleName('application'); //A megadorr modul hasznákva volt-e au utolsó dispatched action-nál.
        $this->assertControllerName(TestController::class); //A megadott controller identifier volt-e kiválasztva az utolsó dispatched action-nél.
        $this->assertControllerClass("TestController"); //A megadott controller osztály volt-e kiválasztva az utolsó dispatched action-nél.
        $this->assertActionName('unittest'); // A megadott Action volt-e kiválasuzva az utolsó dispatched-nál Action szó nélkül
        $this->assertMatchedRouteName('test'); // A megadott route volt-e kiváasztva (routematch) a router által.
        //Each also has a 'Not' variant for negative assertions. assertNotModuleName() stb


        //Response Header Assertions
        $this->assertResponseStatusCode(200); // assert that the response resulted in the given HTTP response code.
        //$this->assertResponseHeader($header); // assert that the response contains the given header.
        //$this->assertResponseHeaderContains($header, $match); // assert that the response contains the given header and that its content contains the given string.
        //$this->assertResponseHeaderRegex($header, $pattern); // assert that the response contains the given header and that its content matches the given regex.
        //Additionally, each of the above assertions have a 'Not' variant for negative assertions.


        //CSS Selector Assertions
        $this->assertQuery('.unitestcssclass'); // assert that one or more DOM elements matching the given CSS selector are present.

        $this->assertQueryContentContains('.unitestcssclass', 'Szoveg2'); // assert that one or more DOM elements matching the given CSS selector are present, and that at least one contains the content provided in $match.

        return;

        $this->assertQueryContentRegex($path, $pattern); // assert that one or more DOM elements matching the given CSS selector are present, and that at least one matches the regular expression provided in $pattern.
        $this->assertQueryCount($path, $count); // assert that there are exactly $count DOM elements matching the given CSS selector present.
        $this->assertQueryCountMin($path, $count); // assert that there are at least $count DOM elements matching the given CSS selector present.
        $this->assertQueryCountMax($path, $count); // assert that there are no more than $count DOM elements matching the given CSS selector present.
        //Additionally, each of the above has a 'Not' variant that provides a negative assertion:
        //assertNotQuery(), assertNotQueryContentContains(), assertNotQueryContentRegex(), and assertNotQueryCount().
        //(Note that the min and max counts do not have these variants, for what should be obvious reasons.)

        //Redirect Assertions
        $this->assertRedirect(); // assert simply that a redirect has occurred.
        $this->assertRedirectTo($url); // assert that a redirect has occurred, and that the value of the Location header is the $url provided.
        $this->assertRedirectRegex($pattern); // assert that a redirect has occurred, and that the value of the Location header matches the regular expression provided by $pattern.
        //Each also has a 'Not' variant for negative assertions.


    }

    public function testTranslateAction()
    {
        $this->mockLogin();

        $this->dispatch('/test/translate?language=en_US', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertQueryContentContains('.language', 'en_US');
        $this->assertNotQuery('.alert-danger');

        $this->dispatch('/test/translate?language=de_DE', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertQueryContentContains('.language', 'de_DE');
        $this->assertNotQuery('.alert-danger');

        $this->dispatch('/test/translate?language=hu_HU', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertQueryContentContains('.language', 'hu_HU');
        $this->assertNotQuery('.alert-danger');

    }

    public function testCurrencyAction()
    {
        $this->mockLogin();

        $this->dispatch('/test/currency?currency=HUF', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertQueryContentContains('.amount', 300);
        $this->assertNotQuery('.alert-danger');
        /*
        $this->dispatch('/test/currency?currency=EUR', 'GET');
        $szorzo = (float)$this->getApplicationServiceLocator()->get('config')['currency']['currency']['EUR']['rateFromSystemCurrency'];
        $this->assertResponseStatusCode(200);
        $this->assertQueryContentContains('.amount', round((float)(300*$szorzo),2));
        $this->assertNotQuery('.alert-danger'); */


    }






    public function testIndexActionViewModelTemplateRenderedWithinLayout()
    {
        $this->mockLogin();
        $this->dispatch('/test/unittest', 'GET');
        $this->assertResponseStatusCode(200);

    }

    //#181 megvan az oka miért száll el
    //public function testInvalidRouteDoesNotCrash()
    //{

    //   $this->dispatch('/invalid/route', 'GET');
    //   $this->assertResponseStatusCode(404);
    //}


}
