<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.29.
 * Time: 8:29
 */

namespace Application\Validator;


use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;

class CustomString extends AbstractValidator
{

    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * If $value fails validation, then this method returns false, and
     * getMessages() will return an array of messages that explain why the
     * validation failed.
     *
     * @param  mixed $value
     * @return bool
     * @throws Exception\RuntimeException If validation of $value is impossible
     */
    public function isValid($value)
    {
        // TODO: #132 megcsinálni ezt a validatort az  összes olyan inputra, ahol tetszőleges user inut van, tehát nem lehet korlátozni a bevitt karaktereet mert annyira általános a string (nem username ahol pl alnum-ot tudok eröltetni stb
       //
        return true;
    }

    public function getMessages() {}
}