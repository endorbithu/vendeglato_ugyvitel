<?php
namespace User;

use User\Controller\UserController;
use User\Controller\Factory\UserControllerFactory;
use User\Service\Factory\RegisteredService\UserAuthenticationFactory;
use User\Service\RegisteredService\UserAuthentication;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return array(

    'router' => [
        'routes' => [
            'login' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/login',
                    'defaults' => [
                        'controller' => UserController::class,
                        'action' => 'login',
                    ],
                ],
            ],
            'logout' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/logout',
                    'defaults' => [
                        'controller' => UserController::class,
                        'action' => 'logout',
                    ],
                ],
            ],
            'user' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/user[/:action][/:id]',
                    'defaults' => [
                        'controller' => UserController::class,
                        'action' => 'index',
                    ],
                ],
            ],
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


    'controllers' => [
        'factories' => [
            Controller\UserController::class => UserControllerFactory::class,
        ],
    ],


    'service_manager' => [
        'factories' => [
            UserAuthentication::class => UserAuthenticationFactory::class,
        ],


    ],

    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

);