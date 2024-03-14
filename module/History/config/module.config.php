<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace History;


use History\Controller\Factory\StockTransactionHistoryControllerFactory;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'router' => [

        'routes' => [

            'history' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/history',
                    'defaults' => [
                        'controller' => Controller\HistoryController::class,
                        'action' => 'index',
                    ],
                ],
            ],

            'stocktransactionhistorylist' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/history/stocktransactionlist[/:type][/:yf/:mf/:df][/:yt/:mt/:dt]',
                    'constraints' => array(
                        'action' => '[a-z]*',
                        'type' => '[a-z]*',
                        'yf' => '[0-9]+',
                        'mf' => '[0-9]+',
                        'df' => '[0-9]+',
                        'yt' => '[0-9]+',
                        'mt' => '[0-9]+',
                        'dt' => '[0-9]+',
                    ),
                    'defaults' => [
                        'controller' => Controller\StockTransactionHistoryController::class,
                        'action' => 'stocktransactionlist',
                    ],
                ],
            ],



            'stocktransactionhistory' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/history/:action[/:id]',
                    'constraints' => array(
                        'action' => '[a-z]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => [
                        'controller' => Controller\StockTransactionHistoryController::class,
                        'action' => 'stocktransaction',
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\StockTransactionHistoryController::class => StockTransactionHistoryControllerFactory::class,
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
