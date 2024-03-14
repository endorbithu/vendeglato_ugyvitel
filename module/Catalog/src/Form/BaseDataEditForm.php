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

namespace Catalog\Form;

use Doctrine\ORM\EntityManager;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class BaseDataEditForm extends Form
{
    protected $fieldset = [];


    // Constructor.
    public function __construct($name, $fieldset, EntityManager $em)
    {
        // Define form name
        parent::__construct();
        if (is_array($fieldset)) {
            foreach ($fieldset as $aFieldset) {
                $this->fieldset[] = $aFieldset;
            }

        } else {
            $this->fieldset[] = $fieldset;
        }

        $this->setInputFilter(new InputFilter);
        $this->setName($name);

        // Set POST method for this form
        $this->setAttribute('method', 'post');


        // Add form elements
        $this->addElements();


    }

    // This method adds elements to form (input fields and
    // submit button).
    private function addElements()
    {

        $this->add(new Element\Csrf('security'));

        //$fieldset = $this->fieldset;
        //$fieldset->setInputFilterSpecification(/* saját validásvió megadása, ha szükséges ezzel felü lehet írni, mert mergel */);

        foreach ($this->fieldset as $aFieldset) {
            $this->add($aFieldset);
        }

        // Add the submit button
        $this->add([
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Ok',
                'class' => 'btn btn-primary btn-lg'
            ],
        ]);
    }

    public function setButtonLabel($label)
    {

    }


}