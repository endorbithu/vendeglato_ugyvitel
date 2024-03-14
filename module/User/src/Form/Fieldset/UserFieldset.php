<?php
namespace User\Form\Fieldset;

use Application\Entity\Role;
use Application\Filter\CapitalFirstLetter;
use Application\Filter\NumericToFloat;
use Application\Filter\NumericToInt;
use Application\Filter\SubString;
use Application\Filter\TextareaString;
use Application\Validator\CustomLongString;
use Application\Validator\CustomString;
use Catalog\Entity\IngredientGroup;
use Catalog\Entity\IngredientUnit;
use Catalog\Entity\Storage;
use Zend\Filter\Encrypt;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Zend\Validator\EmailAddress;
use Zend\Validator\Identical;


class UserFieldset extends Fieldset implements InputFilterProviderInterface
{

    private $inputFilterConfig;

    public function __construct($em, $entity)
    {
        $this->setHydrator(new DoctrineObject($em));
        $this->setObject($entity);
        parent::__construct('User');
        $this->setInputFilterSpecification();

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
            'attributes' => [
                'id' => 'user-id',
            ],
        ]);

        $this->add([
            'name' => 'username',
            'type' => 'text',
            'options' => [
                'label' => 'Felhasználónév',
            ],
            'attributes' => [
                'id' => 'user-username',
                'class' => 'form-control',
                'required' => 'required',

            ],
        ]);


        $this->add([
            'name' => 'name',
            'type' => 'text',
            'options' => [
                'label' => 'Teljes név',
            ],
            'attributes' => [
                'id' => 'user-name',
                'class' => 'form-control',
                'required' => 'required',

            ],
        ]);






        $this->add([
            'name' => 'email',
            'type' => 'email',
            'options' => [
                'label' => 'Email',
            ],
            'attributes' => [
                'id' => 'user-email',
                'class' => 'form-control',
            ],
        ]);




        $this->add([
            'name' => 'telephone',
            'type' => 'text',
            'options' => [
                'label' => 'Telefon',
            ],
            'attributes' => [
                'id' => 'user-telephone',
                'class' => 'form-control',
            ],
        ]);


        $this->add(array(
            'name' => 'password',
            'type' => 'Password',
            'options' => array(
                'label' => 'Jelszó'
            ),
            'attributes' => array(
                'id' => 'user-password',
                'size' => 35,
                'class' => 'form-control',
            ),
        ));


        $this->add(array(
            'name' => 'confirmPassword',
            'type' => 'Password',
            'options' => array(
                'label' => 'Jelszó megerősítése'
            ),
            'attributes' => array(
                'size' => 35,
                'class' => 'form-control',
            ),
        ));


        $this->add([
            'name' => 'moreInfo',
            'type' => 'textarea',
            'options' => [
                'label' => 'Egyéb megjegyzés',
            ],
            'attributes' => [
                'id' => 'user-more-info',
                'class' => 'form-control'
            ],
        ]);


         $this->add([
            'name' => 'role',
            'type' => 'textarea',
            'options' => [
                'label' => 'Egyéb megjegyzés',
            ],
            'attributes' => [
                'id' => 'user-role',
                'class' => 'form-control'
            ],
        ]);


        $this->add(array(
            'name' => 'role',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'object_manager' => $em,
                'target_class' => Role::class,
                'property' => 'displayName',
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => [],
                        'orderBy' => ['id' => 'DESC']
                    )
                ),
                'label' => 'Szerepkör',
            ),
            'attributes' => array(
                'id' => 'user-role',
                'class' => 'select2',
            ),
        ));




    }


    public function setInputFilterSpecification(array $inputConfig = array())
    {
        $this->inputFilterConfig = array_merge(
            array(
                'id' => array(
                    'required' => false,
                    'filters' => array(
                        array('name' => 'StripTags'),
                        array('name' => NumericToInt::class),
                    ),
                    'validators' => array(
                        array(
                            'name' => 'Digits',
                        ),
                        array(
                            'name' => 'LessThan',
                            'options' => array(
                                'max' => 100000000000,
                            ),
                        ),
                    ),
                ),

                'name' => array(
                    'required' => true,
                    'filters' => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                        array('name' => SubString::class, 'options' => array('max' => 65)),
                        array('name' => CapitalFirstLetter::class),
                    ),
                    'validators' => array(
                        array(
                            'name' => 'StringLength',
                            'options' => array(

                                'max' => 64,
                            ),
                        ),
                    ),
                ),


                'username' => array(
                    'required' => true,
                    'filters' => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                        array('name' => SubString::class, 'options' => array('max' => 65)),
                    ),
                    'validators' => array(
                        array(
                            'name' => 'StringLength',
                            'options' => array(

                                'max' => 64,
                            ),
                        ),
                    ),
                ),

                'telephone' => array(
                    'required' => true,
                    'filters' => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                        array('name' => SubString::class, 'options' => array('max' => 33)),
                    ),
                    'validators' => array(
                        array(
                            'name' => 'StringLength',
                            'options' => array(

                                'max' => 32,
                            ),
                        ),
                    ),
                ),

                'email' => array(
                    'required' => true,
                    'filters' => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                        array('name' => SubString::class, 'options' => array('max' => 65)),
                    ),
                    'validators' => array(
                        array(
                            'name' => 'StringLength',
                            'options' => array(

                                'max' => 64,
                            ),
                        ),
                        array(
                            'name' => EmailAddress::class,
                        ),
                    ),
                ),


                'password' => array(
                    'required' => false,//TODO: #167 csak akkor leyen kötelető, ha új
                    'validators' => array(
                        array(
                            'name' => 'NotEmpty',
                            'break_chain_on_failure' => true,
                        ),
                        array(
                            'name' => 'StringLength',
                            'break_chain_on_failure' => true,
                            'options' => array(
                                'min' => 4,
                            ),
                        ),
                    ),
                ),

                'confirmPassword' => array(
                    'required' => false,
                    'validators' => array(
                        array(
                            'name' => 'NotEmpty',
                            'break_chain_on_failure' => true,
                        ),
                        array(
                            'name' => Identical::class,
                            'break_chain_on_failure' => true,
                            'options' => array(
                                'token' => 'password',
                            ),
                        ),
                    ),
                ),

                'role' => array(
                    'required' => false,
                    'filters' => array(
                        array('name' => 'StripTags'),
                        array('name' => NumericToInt::class),
                    ),
                    'validators' => array(
                        array(
                            'name' => 'Digits',
                        ),
                        array(
                            'name' => 'LessThan',
                            'options' => array(
                                'max' => 100000000000,
                            ),
                        ),
                    ),
                ),

                'moreInfo' => array(
                    'required' => false,
                    'filters' => array(
                        array('name' => 'StripTags'),
                        array('name' => SubString::class, 'options' => array('max' => 257)),
                        array('name' => TextareaString::class),
                    ),
                    'validators' => array(
                        array(
                            'name' => 'StringLength',
                            'options' => array(
                                'max' => 256,
                            ),
                            array('name' => CustomLongString::class),
                        ),
                    ),
                ),


            ),
            $inputConfig);


    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return $this->inputFilterConfig;
    }
}