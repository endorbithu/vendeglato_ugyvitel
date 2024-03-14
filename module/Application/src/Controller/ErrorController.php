<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;


use Application\Entity\BlogPost;
use Application\Service\Test\EgyServiceAzSajatEventhez;
use Application\Service\TweetService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ErrorController extends AbstractActionController
{

    private $sm;

    public function __construct($sm)
    {
        $this->sm = $sm;
    }


    public function indexAction()
    {

        asd
       return  array();

    }


}
