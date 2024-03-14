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

use Zend\Captcha\AdapterInterface as CaptchaAdapter;
use Zend\Form\Element;
use Zend\Form\Form;

use User\Form\Fieldset\UserFieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\InputFilter\InputFilter;

class LoginForm extends Form
{
    protected $captcha;

    // Constructor.
    public function __construct(CaptchaAdapter $captcha, $em, $entity)
    {
        // Define form name
        parent::__construct();
        $this->captcha = $captcha;

        $this->em = $em;
        $this->entity = $entity;

        $this->setInputFilter(new InputFilter);
        $this->setName('login');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        // Add form elements
        $this->addElements();


    }

    // This method adds elements to form (input fields and
    // submit button).
    private function addElements()
    {
        $userField = new UserFieldset($this->em, $this->entity);
        $userField->setInputFilterSpecification(/* saját validásvió megadása, ha szükséges */);

        $this->add($userField->get('username'));

        $this->add($userField->get('password'));

        $this->add(new Element\Csrf('security'));

        $this->add([
            'type' => Element\Captcha::class,
            'name' => 'captcha',
            'options' => [
                'label' => 'Please verify you are human.',
                'captcha' => $this->captcha,
            ],
        ]); 


        // Add the submit button
        $this->add([
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Belépés',
                'class' => 'btn btn-primary btn-lg'
            ],
        ]);
    }
}