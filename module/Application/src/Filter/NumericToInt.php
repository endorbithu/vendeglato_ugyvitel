<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.28.
 * Time: 10:44
 */

namespace Application\Filter;

use Zend\Filter\FilterInterface;

class NumericToInt implements FilterInterface
{
    public function filter($value)
    {
        $value = preg_replace('/[\s\t\n\r]+/', '', $value);

        //ha csak white spacek votak benne null van
        if($value === '') return $value;
        $value = substr($value, 0, 19);

        if (!is_numeric($value)) {
            return '--nem-numerikus--';
        } else {
            return (int)$value;
        }

    }
}