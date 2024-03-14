<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2017.03.18.
 * Time: 10:53
 */

namespace Application\Service\NumberFormat;


class NumberFormatService
{


    public function __invoke($num, $intIfNullAfterPoint = false, $forceFloat = false)
    {
        return $this->nf($num, $intIfNullAfterPoint, $forceFloat);
    }


    public function nf($num, $intIfNullAfterPoint = false, $forceFloat = false)
    {

        $num = str_replace(',', '.', $num); //tizedesvessző pontra állítás
        $num = str_replace(' ', '', $num); //ezres szóközt eltörölni
        $num = $num + (is_float($num) || $forceFloat ? 0.0 : 0); //számmá alakítjuk

        if ($intIfNullAfterPoint === true) {
            $num = $num + 0;
            return number_format($num, (floor($num) != $num ? 2 : 0), ',', ' ');
        }

        return number_format($num, (is_float($num) || $forceFloat ? 2 : 0), ',', ' ');

    }


}