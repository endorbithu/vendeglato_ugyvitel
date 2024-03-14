<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Entity\InjectionOfEntity\InjectListener;
use Application\Model\StatusMessages;
use Application\Service\Acl\AuthorizationByAclRegistered;
use Application\Service\Log\EventLogService;
use Application\Service\Translator\InitializeTranslator;
use Application\Service\Translator\Listener\ChangingLanguageListener;
use User\Service\RegisteredService\UserAuthentication;
use Zend\Mvc\MvcEvent;

class Module
{
    const VERSION = '3.0.0dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }


    /**
     * Listen to the bootstrap event
     *
     * @param MvcEvent $e
     * @return array
     */
    function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();

        //TESZTHEZ LOGIN
        
        $uAuth = $e->getApplication()->getServiceManager()->get(UserAuthentication::class);
        $authAdapter = $uAuth->getAuthAdapter();
        $authAdapter->setIdentity('admin');
        $authAdapter->setCredential(md5('admin'));
        $result = $uAuth->getAuthService()->authenticate();
        $result->isValid(); 
        //TESZTHEZ LOGIN VÉGE

        //** TRANSLATOR
        //FONTOS!!! a moduke.condig.phpban van configolva a translate
        $eventManager->attach(
            'route',
            new InitializeTranslator($e), 100
        );

        $eventManager->attach(
            'route',
            new ChangingLanguageListener($e), 99
        );


        // ** DOCTRINE ENTITYKBE SERVICE_MANAGER INJEKTÁLÁS - A doctrine egy kicsit kivétel, mert saját event managere van
        $serviceManager = $e->getApplication()->getServiceManager();
        $doctrineEventManager = $serviceManager
            ->get('Doctrine\ORM\EntityManager')
            ->getEventManager();
        $doctrineEventManager->addEventListener(
            array(\Doctrine\ORM\Events::postLoad),
            new InjectListener($serviceManager)
        );


        // **  ACL autherization ha kisebb a priorítása mint a controlleres onDispatchnak akkor még
        // az Action is le fog futni, tehát ez bajos => ennek kell a legmagasabb priorítsúnak lennie
        $eventManager->attach('dispatch', function ($e) {
            return $e->getApplication()->getServiceManager()->get(AuthorizationByAclRegistered::class)->onDispatch($e);
        }, 1000);


        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, function ($e) {
            if ($e->getResult()->exception) {
                $errorMessage = 'Feldolgozási hiba: ' . $remoteAddr = $e->getRequest()->getServer('REMOTE_ADDR') . ' '
                        . $e->getResult()->exception->getFile() . $e->getResult()->exception->getLine() . ' '
                        . $e->getResult()->exception->getMessage();
                $e->getApplication()->getServiceManager()->get(EventLogService::class)->__invoke($errorMessage, 500);
                if ($e->getParam('exception')) {
                    error_log($e->getParam('exception'));
                }
            }
        }, -1000);


        // ** STATUS MESSAGE :A statusz üzenet modelljének a viewmodellbe való beszúrása => az összes viewba beeteszi
        //A gyári flashMessage-vel redirect előtt is meghatározhatjuk, hogy mi legyen az üzenet
        // és ez a kettő fajta üzit mergelem!
        $e->getViewModel()->setVariable('statusMessages', new StatusMessages());


    }
}



/*
       $eventManager->attach('dispatch.error', function ($e) {
           //ez a sima 404 oldalnál csatlakozik, tehát elsül, én manuálisan nem tudom elsütni

           $exception = $e->getResult()->exception;
           if ($exception) {
               $sm = $e->getApplication()->getServiceManager();
               $service = $sm->get('ApplicationServiceErrorHandling');
               $service->logException($exception);
           }

       });
*/


/*

        // ** User authentication UserController/login ban van
        //amúgy beépített AuthenticationPluginnal megy a doctrinon keresztül


        // ** SAJÁT EVENT ÉS FELIRATKOZÁS:
        //http://www.michaelgallego.fr/blog/2013/05/12/understanding-the-zend-framework-2-event-manager/

        //a sharedManager kell a saját magunk által létrehozűsa eveteknek, mert az nem közös mint a route dispatch stb
        $sharedEventManager = $eventManager->getSharedManager();

        //ez egy olyan service ami fel van iratkozva az eseményre és várja paraméternek az eventet benne az általunk megadott paraméterrel
        $listeneresObjektum = new EgyikListeneresOsztalyService();

        //feliratkozás
        $sharedEventManager->attach('*', 'sendTweet',
            array($listeneresObjektum, 'az_attacban_megadott_callback_fg'), 100); //de lehet __invoke class is





        //lehet azt, hogy attach('Application\Controller\IndexController', *,..... -> összes esemĂ©nye a IndexControllernek
        //lehet azt, hogy attach('*', sendTweet -> összes identifier sendTweet esemĂ©nyĂ©re iratkozok fel
        //lehet aggregĂˇlni is egy esemĂ©nynek a feliratkozĂłit, doksiban benne van

        // SAJÁT EVENT ÉS FELIRATKOZÁS RÁ OFF


    }


}


//SEGÉDANYAG:
/*

        //A ZF3 ez 3+bootsrtap beépített események vannak itt kell a bootstrapba beregistrálni őket, bár mindn modul
        //a saját Module.php-ban regisztráljon az a tuti, itt csak a rendszerhez szükséges (translator stb)
        //van beregisztrálva, de innen lehet
        //copypastezni a többi Module.php onBootstrap() funkciójába a beregisztrálást

        //Az eventek által paraméterezett $e  tartalmazza:
          //   var_dump($e->getApplication()		);
          //   var_dump($e->getRequest()			);
          //   var_dump($e->getResponse()			);
          //   var_dump($e->getRouter()				);
          //   var_dump($e->getRouteMatch()			);
          //   var_dump($e->getResult()				);
          //   var_dump($e->getViewModel()			);
          //   var_dump($e->getError()				);
          //   var_dump($e->getController()			);
          //   var_dump($e->getControllerClass()	);




    //route eventnél itt még leht pl a https-re kényszerítenu
    //itt még át lehet irányĂ­tani másik controller/actionre, vagy átírni a layoutot
       $eventManager->attach(
            'route',
            function ($e) {
            }, 1
        );



        //itt meg az Action előtt lehet trükközni (ogosultság stb  elĹ‘tt bĹ±vĂ©szledni, onDispatch-csal
        // el lehet Ă©rni controllerbĹ‘l is
        $eventManager->attach(
            'dispatch',
            function ($e) {
            }, 1000
        );


        //A view render esemény
        $eventManager->attach(
            'render',
            function ($e) {
            }, 1
        );

        //a végső response küldés előtt még mahinálhatunk az $e -vel
        $eventManager->attach(
            'finish',
            function ($e) {
            },1
        );




        //Ezeken kívüli események, akár a saját magunk által készített események, de mint a példa mutatja a
        //Zend\MVC\SendResponseEvent is csak a SharedEventmanageren lehet elérni
        //A getSharedManager()->attach( -nál más a paraméterezés: azonosító, event, listener
        $eventManager->getSharedManager()->attach(
            'Zend\Mvc\SendResponseListener',
            SendResponseEvent::EVENT_SEND_RESPONSE,
            function ($e) {
            }
        );


// SAJÁT EVENT ÉS FELIRATKOZÁS:



//a sharedManager kell a saját magunk által létrehozűsa eveteknek, mert az nem közös mint a route dispatch stb
$sharedEventManager = $eventManager->getSharedManager();

//ez egy olyan service ami fel van iratkozva az eseményre és várja paraméternek az eventet benne az általunk megadott paraméterrel
$listeneresObjektum = new EgyikListeneresOsztalyService();

//feliratkozás
$sharedEventManager->attach('Application\Controller\IndexController', 'sendTweet',
    array($listeneresObjektum, 'az_attacban_megadott_callback_fg'), 100); //de lehet __invoke class is

//lehet azt, hogy attach('Application\Controller\IndexController', *,..... -> összes esemĂ©nye a IndexControllernek
//lehet azt, hogy attach('*', sendTweet -> összes identifier sendTweet esemĂ©nyĂ©re iratkozok fel
//lehet aggregĂˇlni is egy esemĂ©nynek a feliratkozĂłit, doksiban benne van

// SAJÁT EVENT ÉS FELIRATKOZÁS RÁ OFF

*/


