<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.14.
 * Time: 11:54
 * * @copyright Copyright (c) 2011, Marco Neumann
 * @license   http://binware.org/license/index/type:new-bsd New BSD License
 */
/**
 * Login Form Class
 *
 * Login Form
 *
 * @category  User
 * @package   User_Form
 * @copyright Copyright (c) 2011, Marco Neumann
 * @license   http://binware.org/license/index/type:new-bsd New BSD License
 */

namespace User\Form;


use Zend\Form\Element\Csrf;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;


class UserRegisterForm extends Form
{
    // Constructor.
    public function __construct($userFieldset)
    {
        // Define form name
        parent::__construct('register');
        $this->userFieldset = $userFieldset;

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        // Add form elements
        $this->addElements();
    }

    // This method adds elements to form (input fields and
    // submit button).
    private function addElements()
    {
        $this->add($this->userFieldset);

        $this->add(new Csrf('security'));

        // Add the submit button
        $this->add([
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Feltöltés',
                'class' => 'btn btn-primary btn-lg'

            ],
        ]);
    }
}