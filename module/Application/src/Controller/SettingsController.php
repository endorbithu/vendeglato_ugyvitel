<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use User\Entity\User;
use User\Service\RegisteredService\UserAuthentication;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SettingsController extends AbstractActionController
{


    private $sm;
    private $actualUserId;


    public function __construct($sm, $misc = null)
    {
        $this->sm = $sm;
        $this->actualUserId = $this->sm->get(UserAuthentication::class)->getIdentity()->getId();

    }


    public function indexAction()
    {


        return [];

    }


}
