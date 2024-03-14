<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.18.
 * Time: 9:29
 */

namespace Application\Service;

use Zend\Mvc\Console\View\ViewModel;
use Zend\Mvc\MvcEvent;

class RedirectToCustom403404
{
    public function __invoke(MvcEvent $event)
    {

        $error = $event->getError();

        if (empty($error) || $error != "404" || $error !="403") {
            return;
        }

        $result = $event->getResult();

        if ($result instanceof StdResponse) {
            return;
        }

        $baseModel = new ViewModel();
        $baseModel->setTemplate('layout/layout');

        $model = new ViewModel();
        $model->setTemplate('error/'.$error);

        $baseModel->addChild($model);
        $baseModel->setTerminal(false);

        $event->setViewModel($baseModel);

        $response = $event->getResponse();
        $response->setStatusCode((int)$error);

        $event->setResponse($response);
        $event->setResult($baseModel);

        return $event;
    }

}