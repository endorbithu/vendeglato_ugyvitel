<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Order;


use Order\Controller\Factory\ProductTransactionControllerFactory;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [

            'order' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/order',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],

            'producttransaction' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/order/:action[/:from][/:to]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'from' => '[0-9]+',
                        'to' => '[0-9]+',
                    ),
                    'defaults' => [
                        'controller' => Controller\ProductTransactionController::class,
                        'action' => 'index',
                    ],
                ],
            ],




        ],
    ],


    //TODO: #157 IndexControllernek az alapértelmezett factorít beírni
    'controllers' => [
        'factories' => [
            Controller\ProductTransactionController::class => ProductTransactionControllerFactory::class,
        ],
    ],


    // Doctrine config itt hagyjuk. az a Ă©l, hogy minden modul a sajĂˇt repositorĂˇt tartalmazza
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../../' . __NAMESPACE__ . '/src/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    ),


    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],


];
