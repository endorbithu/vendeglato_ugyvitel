<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Transaction;

use Transaction\Controller\Factory\IngrTransactionChooseControllerFactory;
use Transaction\Controller\Factory\IngrTransactionControllerFactory;
use Transaction\Controller\Factory\MoneyStockShowControllerFactory;
use Transaction\Controller\Factory\MoneyTransactionChooseControllerFactory;
use Transaction\Controller\Factory\MoneyTransactionControllerFactory;
use Transaction\Controller\Factory\StockShowControllerFactory;
use Transaction\Controller\Factory\ToolStockShowControllerFactory;
use Transaction\Controller\Factory\ToolTransactionChooseControllerFactory;
use Transaction\Controller\Factory\ToolTransactionControllerFactory;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [

            'ingrtransaction' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/transaction/ingrtransaction[/:action/:from/:to]',
                    'constraints' => array( //megkötések az action-re és az id-ra
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'from' => '[0-9]+',
                        'to' => '[0-9]+',
                    ),
                    'defaults' => [
                        'controller' => Controller\IngredientTransactionController::class,
                        'action' => 'index',
                    ],
                ],
            ],


            'ingrtransactionchoose' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/transaction/ingrtransaction[/:action]',
                    'constraints' => array( //megkötések az action-re és az id-ra
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => [
                        'controller' => Controller\IngredientTransactionChooseController::class,
                        'action' => 'index',
                    ],
                ],
            ],


            'stock' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/transaction/stock[/:id]',
                    'constraints' => array( //megkötések az action-re és az id-ra
                        'id' => '[0-9]+',
                    ),

                    'defaults' => [
                        'controller' => Controller\StockShowController::class,
                        'action' => 'index',
                    ],
                ],
            ],

            'tooltransaction' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/transaction/tooltransaction[/:action/:from/:to]',
                    'constraints' => array( //megkötések az action-re és az id-ra
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'from' => '[0-9]+',
                        'to' => '[0-9]+',
                    ),
                    'defaults' => [
                        'controller' => Controller\ToolTransactionController::class,
                        'action' => 'index',
                    ],
                ],
            ],

            'tooltransactionchoose' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/transaction/tooltransaction[/:action]',
                    'constraints' => array( //megkötések az action-re és az id-ra
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => [
                        'controller' => Controller\ToolTransactionChooseController::class,
                        'action' => 'index',
                    ],
                ],
            ],

            'toolstock' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/transaction/toolstock[/:id]',
                    'constraints' => array( //megkötések az action-re és az id-ra
                        'id' => '[0-9]+',
                    ),

                    'defaults' => [
                        'controller' => Controller\ToolStockShowController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'moneytransaction' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/transaction/moneytransaction[/:action/:from/:to]',
                    'constraints' => array( //megkötések az action-re és az id-ra
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'from' => '[0-9]+',
                        'to' => '[0-9]+',
                    ),
                    'defaults' => [
                        'controller' => Controller\MoneyTransactionController::class,
                        'action' => 'index',
                    ],
                ],
            ],

            'moneytransactionchoose' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/transaction/moneytransaction[/:action]',
                    'constraints' => array( //megkötések az action-re és az id-ra
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => [
                        'controller' => Controller\MoneyTransactionChooseController::class,
                        'action' => 'index',
                    ],
                ],
            ],

            'moneystock' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/transaction/moneystock[/:id]',
                    'constraints' => array( //megkötések az action-re és az id-ra
                        'id' => '[0-9]+',
                    ),

                    'defaults' => [
                        'controller' => Controller\MoneyStockShowController::class,
                        'action' => 'index',
                    ],
                ],
            ],

        ],
    ],


    'controllers' => [
        'factories' => [

            Controller\StockShowController::class => StockShowControllerFactory::class,
            Controller\IngredientTransactionChooseController::class => IngrTransactionChooseControllerFactory::class,
            Controller\IngredientTransactionController::class => IngrTransactionControllerFactory::class,

            Controller\ToolStockShowController::class => ToolStockShowControllerFactory::class,
            Controller\ToolTransactionChooseController::class => ToolTransactionChooseControllerFactory::class,
            Controller\ToolTransactionController::class => ToolTransactionControllerFactory::class,
            
            Controller\MoneyStockShowController::class => MoneyStockShowControllerFactory::class,
            Controller\MoneyTransactionChooseController::class => MoneyTransactionChooseControllerFactory::class,
            Controller\MoneyTransactionController::class => MoneyTransactionControllerFactory::class,
        ],
    ],


    //register service
    'service_manager' => [
        'aliases' => [],
        'factories' => [],
    ],

    'view_helpers' => [
        'aliases' => [],
        'factories' => []
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
