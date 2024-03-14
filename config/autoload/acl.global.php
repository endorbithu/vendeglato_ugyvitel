<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.10.
 * Time: 15:50
 */


return
    array(
        //a deny-t sztem akkor lehet haszn�lni, ha alapb�l meg�r�k�ln� de nem szeretn�d, ha l�tn�!
        'acl' => array(
            'roles' => array(
                'guest' => null,
                'staff' => 'guest',
                'manager' => 'staff',
                'owner' => 'manager',
                'admin' => 'owner',
            ),
            'resources' => array(
                'allow' => array(
                    'login' => array(
                        'login' => 'guest',
                    ),
                    'logout' => array(
                        'logout' => 'staff',
                    ),
                    'error' => array(
                        'index' => 'guest',
                    ),
                    'index' => array(
                        'index' => 'guest',
                    ),
                    'settings' => array(
                        'index' => 'owner',
                    ),
                    'test' => array(
                        'index' => 'owner',
                        'datatable' => 'owner',
                        'select2datatable' => 'owner',
                        'prg' => 'owner',
                        'sajatevent' => 'owner',
                        'currency' => 'owner',
                        'translate' => 'owner',
                        'form' => 'owner',
                        'unittest' => 'owner',
                    ),


                    'user' => array(
                        'edit' => 'manager',
                        'show' => 'manager',
                        'delete' => 'manager',
                        'index' => 'manager',
                    ),
                    'basedataedit' => array(
                        'ingredient' => 'manager',
                        'ingredientgroup' => 'manager',
                        'ingredientunit' => 'manager',
                        'tool' => 'manager',
                        'toolgroup' => 'manager',
                        'toolunit' => 'manager',
                        'money' => 'manager',
                        'moneygroup' => 'manager',
                        'moneyunit' => 'manager',
                        'product' => 'manager',
                        'productgroup' => 'manager',
                        'storage' => 'manager',
                        'storagetype' => 'manager',
                        'supplier' => 'manager',
                        'vatgroup' => 'manager',
                    ),

                    'basedata' => array(
                        'index' => 'manager',
                        'ingredient' => 'manager',
                        'ingredientgroup' => 'manager',
                        'ingredientunit' => 'manager',
                        'tool' => 'manager',
                        'toolgroup' => 'manager',
                        'toolunit' => 'manager',
                        'money' => 'manager',
                        'moneygroup' => 'manager',
                        'moneyunit' => 'manager',
                        'product' => 'manager',
                        'productgroup' => 'manager',
                        'storage' => 'manager',
                        'storagetype' => 'manager',
                        'supplier' => 'manager',
                        'vatgroup' => 'manager',
                    ),

                    'basedatacollection' => array(
                        'index' => 'manager',
                        'ingredient' => 'manager',
                        'ingredientgroup' => 'manager',
                        'ingredientunit' => 'manager',
                        'tool' => 'manager',
                        'toolgroup' => 'manager',
                        'toolunit' => 'manager',
                        'money' => 'manager',
                        'moneygroup' => 'manager',
                        'moneyunit' => 'manager',
                        'product' => 'manager',
                        'productgroup' => 'manager',
                        'storage' => 'manager',
                        'storagetype' => 'manager',
                        'supplier' => 'manager',
                        'vatgroup' => 'manager',
                    ),

                    'ingrtransaction' => array(
                        'receive' => 'manager',
                        'return' => 'manager',
                        'move' => 'manager',
                        'discard' => 'manager',
                        'discardproduct' => 'manager',
                        'stockcorrection' => 'manager',
                        'universal' => 'manager',
                        'index' => 'manager',
                    ),

                    'ingrtransactionchoose' => array(
                        'receive' => 'manager',
                        'return' => 'manager',
                        'move' => 'manager',
                        'discard' => 'manager',
                        'discardproduct' => 'manager',
                        'stockcorrection' => 'manager',
                        'universal' => 'manager',
                        'index' => 'manager',
                    ),

                    'stock' => array(
                        'index' => 'manager',

                    ),

                    'toolstock' => array(
                        'index' => 'manager',

                    ),

                    'tooltransaction' => array(
                        'toolreceive' => 'manager',
                        'toolreturn' => 'manager',
                        'toolmove' => 'manager',
                        'tooldiscard' => 'manager',
                        'toolstockcorrection' => 'manager',
                        'tooluniversal' => 'manager',
                        'index' => 'manager',
                    ),

                    'tooltransactionchoose' => array(
                        'toolreceive' => 'manager',
                        'toolreturn' => 'manager',
                        'toolmove' => 'manager',
                        'tooldiscard' => 'manager',
                        'toolstockcorrection' => 'manager',
                        'tooluniversal' => 'manager',
                        'toolindex' => 'manager',
                    ),

                    'moneystock' => array(
                        'index' => 'manager',

                    ),

                    'moneytransaction' => array(
                        'moneyin' => 'manager',
                        'moneyout' => 'manager',
                        'moneymove' => 'manager',
                        'moneystockcorrection' => 'manager',
                        'moneyuniversal' => 'manager',
                        'index' => 'manager',
                    ),

                    'moneytransactionchoose' => array(
                        'moneyin' => 'manager',
                        'moneyout' => 'manager',
                        'moneymove' => 'manager',
                        'moneystockcorrection' => 'manager',
                        'moneyuniversal' => 'manager',
                        'index' => 'manager',
                    ),

                    'order' => array(
                        'index' => 'staff',
                    ),

                    'producttransaction' => array(
                        'order' => 'staff',
                        'destination' => 'staff',
                        'paying' => 'staff',
                        'moveorderitem' => 'staff',
                        'productback' => 'staff',
                        'productbacktotrash' => 'staff',

                    ),


                    'history' => array(
                        'index' => 'manager',
                    ),

                    'stocktransactionhistory' => array(
                        'income' => 'manager',
                        'stocktransaction' => 'manager',
                        'stocktransactionchoose' => 'manager',
                    ),

                    'stocktransactionhistorylist' => array(
                        'stocktransactionlist' => 'manager',
                    ),


                    'doctrine_orm_module_yuml' => array(
                        'index' => 'owner',
                    )
                )
            )
        ));