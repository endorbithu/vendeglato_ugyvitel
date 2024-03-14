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

class CustomLongString extends AbstractValidator
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
        // TODO: #132 megcsinálni ezt a validatort az  összes textareara, ahol tetszőleges user
        // input van, tehát meglévő validatorokkal nem lehet korlátozni a bevitt karaktereet
        //
        return true;
    }
    public function getMessages() {}


}