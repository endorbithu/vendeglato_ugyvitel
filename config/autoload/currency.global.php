<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.10.
 * Time: 15:50
 */


return
    [

        //todo #129 db-ből nyerni az infókat, az MNB váltással együtt
        'currency' => [
            'systemCurrency' => 'HUF',
            'selectedCurrency' => 'HUF',

            'currency' => [ //ez a db-ből dinamikusan fog változni a választott currencyra, a HUF meg 1.00 lesz
                'HUF' => [
                    'name' => 'HUF',
                    'longName' => 'Forint',
                    'sign' => ' Ft',
                    'prefix' => 0,
                    'decimal' => 0,
                    'thousand' => ' ',
                    'rateFromSystemCurrency' => 1.00,
                ],
                'EUR' => [
                    'name' => 'EUR',
                    'longName' => 'Euro',
                    'sign' => '€',
                    'prefix' => 1,
                    'decimal' => 1,
                    'thousand' => ',',
                    'rateFromSystemCurrency' => 0.003030303,
                ],
            ],
        ]
    ];