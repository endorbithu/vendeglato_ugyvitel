<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.14.
 * Time: 9:36
 *
 * @copyright  Copyright (c) 2011, Marco Neumann
 * @license    http://binware.org/license/index/type:new-bsd New BSD License
 */


namespace User\Service\RegisteredService;
    /**
     * @uses Zend\Mvc\Controller\Plugin\AbstractPlugin
     * @uses Zend\Authentication\AuthenticationService
     * @uses Zend\Authentication\Adapter\DbTable
     */
/**
 * Class for User Authentication
 *
 * Handles Auth Adapter and Auth Service to check Identity
 *
 * @category   User
 * @package    User_Controller
 * @subpackage User_Controller_Plugin
 * @copyright  Copyright (c) 2011, Marco Neumann
 * @license    http://binware.org/license/index/type:new-bsd New BSD License
 */

use Zend\Mvc\Controller\Plugin\AbstractPlugin,
    Zend\Authentication\AuthenticationService,
    Zend\Authentication\AuthenticationService as AuthService
    ;

class UserAuthentication extends AbstractPlugin
{
    /**
     * @var AuthAdapter
     */
    protected $_authAdapter = null;

    /**
     * @var AuthenticationService
     */
    protected $_authService = null;

    /**
     * Check if Identity is present
     *
     * @return bool
     */
    public function hasIdentity()
    {
        return $this->getAuthService()->hasIdentity();
    }

    /**
     * Return current Identity
     *
     * @return mixed|null
     */
    public function getIdentity()
    {
        return $this->getAuthService()->getIdentity();
    }

    /**
     * Sets Auth Adapter
     *
     * @param \Zend\Authentication\Adapter\DbTable $authService
     * @return Authentication
     */
    public function setAuthAdapter()
    {
        $this->_authAdapter = $this->_authService->getAdapter();

        return $this;
    }

    /**
     * Returns Auth Adapter
     *
     * @return \Zend\Authentication\Adapter\DbTable
     */
    public function getAuthAdapter()
    {
        if ($this->_authAdapter === null) {
            $this->setAuthAdapter($this->_authService->getAdapter());
        }

        return $this->_authAdapter;
    }

    /**
     * Sets Auth Service
     *
     * @param \Zend\Authentication\AuthenticationService $authService
     * @return Authentication
     */
    public function setAuthService(AuthenticationService $authService)
    {
        $this->_authService = $authService;

        return $this;
    }

    /**
     * Gets Auth Service
     *
     * @return \Zend\Authentication\AuthenticationService
     */
    public function getAuthService()
    {
        if ($this->_authService === null) {
            $this->setAuthService(new AuthenticationService());
        }

        return $this->_authService;
    }

}


