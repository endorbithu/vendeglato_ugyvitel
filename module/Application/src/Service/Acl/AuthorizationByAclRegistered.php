<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.14.
 * Time: 9:47
 * * @copyright Copyright (c) 2011, Marco Neumann
 * @license   http://binware.org/license/index/type:new-bsd New BSD License
 */
/**
 * Authentication Event Handler Class
 *
 * This Event Handles Authentication
 *
 * @category  User
 * @package   User_Event
 * @copyright Copyright (c) 2011, Marco Neumann
 * @license   http://binware.org/license/index/type:new-bsd New BSD License
 */
namespace Application\Service\Acl;

/**
 * @uses Zend\Mvc\MvcEvent
 * @uses User\Service\RegisteredService\Authentication
 * @uses Application\Service\Acl\AclService
 */
use Application\Service\Acl\AclService as AclClass;
use Application\Service\Log\EventLogService;
use User\Service\RegisteredService\UserAuthentication as AuthPlugin;
use User\Service\RegisteredService\UserAuthentication;
use Zend\Mvc\MvcEvent as MvcEvent;
use Zend\View\Model\ViewModel;


class AuthorizationByAclRegistered
{
    /**
     * @var AuthPlugin
     */
    protected $_userAuth = null;

    /**
     * @var AclClass
     */
    protected $_aclClass = null;

    /**
     * preDispatch Event Handler
     *
     * @param \Zend\Mvc\MvcEvent $event
     * @throws \Exception
     */
    public function onDispatch(MvcEvent $event)
    {
        $userAuth = $this->getUserAuthenticationPlugin();
        $acl = $this->getAclClass();
        $role = AclClass::DEFAULT_ROLE;

        if ($userAuth->hasIdentity()) {
            $userId = $userAuth->getIdentity();
            $role = $event->getApplication()->getServiceManager()->get('Doctrine\ORM\EntityManager')->getRepository('User\Entity\User')->find($userId)->getRole()->getName();
        }


        $routeMatch = $event->getRouteMatch();
        $controller = $routeMatch->getMatchedRouteName();
        $action = $routeMatch->getParam('action');


        //ha van route match de az ACLben nincsen regisztálva az oldal
        if (!$acl->hasResource($controller)) {
            $event->getApplication()->getServiceManager()->get('ControllerPluginManager')->get('FlashMessenger')->addMessage("Nincs ilyen oldal regisztrálva!", 'error');
            $response = $event->getResponse();
            $response->setStatusCode(302);
            $response->getHeaders()->addHeaderLine('Location', '/error');
            $response->sendHeaders();
            return $response;
        }


        //ha van routematch de nincs jogosultság
        if (!$acl->isAllowed($role, $controller, $action)) {
            $event->setError('ACL_ACCESS_DENIED'); // Pick your own value, would be better to use a const

            $errorMessage = 'Hozzáférés megtagadva: ' . $remoteAddr = $event->getRequest()->getServer('REMOTE_ADDR') . ' ' . $event->getRequest()->getUriString();
            error_log($errorMessage);
            $event->getApplication()->getServiceManager()->get(EventLogService::class)->__invoke($errorMessage, 403);

            $baseModel = new ViewModel();
            $baseModel->setTemplate('layout/layout');

            $model = new ViewModel();
            $model->setTemplate('error/403');

            $baseModel->addChild($model);
            $baseModel->setTerminal(true);

            $event->setViewModel($baseModel);

            $response = $event->getResponse();
            $response->setStatusCode(403);

            $event->setResponse($response);
            $event->setResult($baseModel);
            $event->stopPropagation();

            return;


        }
    }


    /**
     * Sets Authentication Plugin
     *
     * @param \User\Service\RegisteredService\UserAuthentication $userAuthenticationPlugin
     * @return UserAuthentication
     */
    public function setUserAuthenticationPlugin(UserAuthentication $userAuthenticationPlugin)
    {
        $this->_userAuth = $userAuthenticationPlugin;

        return $this;
    }

    /**
     * Gets Authentication Plugin
     *
     * @return \User\Service\RegisteredService\Authentication Authentication
     */
    public function getUserAuthenticationPlugin()
    {
        if ($this->_userAuth === null) {
            $this->_userAuth = new AuthPlugin();
        }

        return $this->_userAuth;
    }

    /**
     * Sets ACL Class
     *
     * @param \Application\Service\Acl\AclService $aclClass
     * @return Authentication
     */
    public function setAclClass(AclClass $aclClass)
    {
        $this->_aclClass = $aclClass;

        return $this;
    }

    /**
     * Gets ACL Class
     *
     * @return \Application\Service\Acl\AclService
     */
    public function getAclClass()
    {
        if ($this->_aclClass === null) {
            $this->_aclClass = new AclClass(array());
        }

        return $this->_aclClass;
    }

}