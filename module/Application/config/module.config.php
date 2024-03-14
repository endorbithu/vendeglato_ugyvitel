<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Controller\Factory\GenericControllerFactory;
use Application\Filter\SubString;
use Application\Service\Factory\Acl\AuthorizationByAclRegisteredFactory;
use Application\Service\Acl\AuthorizationByAclRegistered;
use Application\Service\Factory\Log\EventLogServiceFactory;
use Application\Service\Log\EventLogService;
use Application\Service\NumberFormat\NumberFormatService;
use Application\View\Helper\DatatableHelper;
use Application\View\Helper\ModalPopupHelper;
use Zend\Authentication\AuthenticationService;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    // This lines opens the configuration for the RouteManager
    'router' => [
        'routes' => [
            'index' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],

            'settings' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/settings[/:action][/:id]',
                    'constraints' => array( //megkötések az action-re és az id-ra
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-z0-9]+',
                    ),
                    'defaults' => [
                        'controller' => Controller\SettingsController::class,
                        'action' => 'index',
                    ],
                ],
            ],


            'error' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/error',
                    'defaults' => [
                        'controller' => Controller\ErrorController::class,
                        'action' => 'index',
                    ],
                ],
            ],


            'test' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/test[/:action]',
                    'constraints' => array( //megkötések az action-re és az id-ra
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => [
                        'controller' => Controller\TestController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],


    'controllers' => [
        'abstract_factories' => [
            GenericControllerFactory::class,
        ],
        'factories' => [
            Controller\IndexController::class => GenericControllerFactory::class,
            Controller\ErrorController::class => GenericControllerFactory::class,
            Controller\TestController::class => GenericControllerFactory::class,
            Controller\SettingsController::class => GenericControllerFactory::class,
        ],
    ],


    'service_manager' => [
        'factories' => [
            AuthorizationByAclRegistered::class => AuthorizationByAclRegisteredFactory::class,
            EventLogService::class => EventLogServiceFactory::class,
            NumberFormatService::class => InvokableFactory::class,

            AuthenticationService::class => function ($serviceManager) {
                // If you are using DoctrineORMModule for Zend\Authentication\AuthenticationService
                return $serviceManager->get('doctrine.authenticationservice.orm_default');
            }
        ],

        'aliases' => [
            'translator' => 'MvcTranslator',
            'nf' => NumberFormatService::class,
        ],

    ],

    'filters' => [
        'aliases' => [
            'SubString' => SubString::class,
        ],
        'factories' => [
            SubString::class => InvokableFactory::class,
        ],
    ],

    'view_helpers' => [
        'aliases' => [
            'datatable' => DatatableHelper::class,
            'modalPopup' => ModalPopupHelper::class,
            'nf' => NumberFormatService::class,
        ],
        'factories' => [
            DatatableHelper::class => InvokableFactory::class,
            ModalPopupHelper::class => InvokableFactory::class,
            NumberFormatService::class => InvokableFactory::class,
        ]
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
        'display_not_found_reason' => false,
        'display_exceptions' => false, //a autoload.global.developement configban true
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => [
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],


];
