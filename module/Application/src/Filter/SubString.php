<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.28.
 * Time: 10:44
 */

namespace Application\Filter;

use Zend\Filter\FilterInterface;

class SubString implements FilterInterface
{
    private $max;


    public function filter($value)
    {
        return mb_substr($value, 0, $this->max);
    }


    function __construct($options = ['max' => 512])
    {
        if ($options['max'] === 'int') {
            $this->max = 19;
        } elseif ($options['max'] === 'float') {
            $this->max = 14;
        } else {
            $this->max = $options['max'];
        }
    }


}