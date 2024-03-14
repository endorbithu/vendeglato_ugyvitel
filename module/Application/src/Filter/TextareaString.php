<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.28.
 * Time: 10:44
 */

namespace Application\Filter;

use Zend\Filter\FilterInterface;

class TextareaString implements FilterInterface
{
    public function filter($value)
    {
        return $value;
    }
}