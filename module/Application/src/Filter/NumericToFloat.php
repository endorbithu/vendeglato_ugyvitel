<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.28.
 * Time: 10:44
 */

namespace Application\Filter;

use Zend\Filter\FilterInterface;

class NumericToFloat implements FilterInterface
{
    public function filter($value)
    {
        $value = preg_replace('/[\s\t\n\r]+/', '', $value);

        //ha csak white spacek voltak benne null van
        if($value === '') return $value;
        $value = substr($value, 0, 14);
        $value = str_replace(',' , '.', $value);

        if (!is_numeric($value)) {
            return '--nem-numerikus--';
        } else {
            return (float)$value;
        }


    }
}