<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Catalog;

use Catalog\Controller\Factory\BaseDataEditControllerFactory;
use Catalog\Controller\Factory\BaseDataShowControllerFactory;
use Catalog\Service\Currency\CurrencyConverterRegistered;
use Catalog\Service\Factory\Currency\CurrencyConverterRegisteredFactory;
use Catalog\View\Factory\Helper\CurrencyHelperFactory;
use Catalog\View\Helper\CurrencyHelper;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [

            'basedataedit' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/catalog/basedata[/:action/:id/edit]',
                    'constraints' => array( //megkötések az action-re és az id-ra
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-z0-9]+',
                    ),
                    'defaults' => [
                        'controller' => Controller\BaseDataEditController::class,
                    ],
                ],
            ],

            'basedata' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/catalog/basedata[/:action/:id]',
                    'constraints' => array( //megkötések az action-re és az id-ra
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => [
                        'controller' => Controller\BaseDataShowController::class,
                    ],
                ],
            ],

            'basedatacollection' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/catalog/basedata[/:action]',
                    'constraints' => array( //megkötések az action-re és az id-ra
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => [
                        'controller' => Controller\BaseDataShowTableController::class,
                        'action' => 'index',
                    ],
                ],
            ],

        ],
    ],


    'controllers' => [
        'factories' => [
            Controller\BaseDataShowController::class => BaseDataShowControllerFactory::class,
            Controller\BaseDataShowTableController::class => BaseDataShowControllerFactory::class,
            Controller\BaseDataEditController::class => BaseDataEditControllerFactory::class,

        ],
    ],


    //register service
    'service_manager' => [
        'aliases' => [
            'currencyConverter' => CurrencyConverterRegistered::class,
        ],
        'factories' => [
            CurrencyConverterRegistered::class => CurrencyConverterRegisteredFactory::class,
        ],
    ],

    'view_helpers' => [
        'aliases' => [
            'money' => CurrencyHelper::class
        ],
        'factories' => [
            CurrencyHelper::class => CurrencyHelperFactory::class,
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
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],


];
