<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.28.
 * Time: 10:44
 */

namespace Application\Filter;

use Zend\Filter\FilterInterface;

class PercentToDecimal implements FilterInterface
{
    public function filter($value)
    {
        if($value <= 1) return round($value, 2);

        $floatValue = (float)$value;
        $decimalValue = $floatValue / 100;
        $roundedValue = round($decimalValue, 2);
        return $roundedValue;
    }
}