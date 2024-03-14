<?php
namespace Catalog\Form\Fieldset;

use Application\Filter\CapitalFirstLetter;
use Application\Filter\NumericToInt;
use Application\Filter\SubString;
use Application\Filter\TextareaString;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;


class SupplierFieldset extends Fieldset implements InputFilterProviderInterface
{


    private $inputFilterConfig;

    public function __construct($em, $entity)
    {
        $this->setHydrator(new DoctrineObject($em));
        $this->setObject($entity);
        parent::__construct('Supplier');
        $this->setInputFilterSpecification();


        $this->add([
            'type' => 'hidden',
            'name' => 'id',
            'attributes' => [
                'id' => 'supplier-id',
            ],
        ]);


        $this->add([
            'name' => 'name',
            'type' => 'text',
            'options' => [
                'label' => 'Cégnév',
            ],
            'attributes' => [
                'id' => 'supplier-name',
                'class' => 'form-control',
                'required' => 'required',

            ],
        ]);


        $this->add([
            'name' => 'taxNumber',
            'type' => 'text',
            'options' => [
                'label' => 'Adószám',
            ],
            'attributes' => [
                'id' => 'supplier-tax-number',
                'class' => 'form-control',
                'required' => 'required',

            ],
        ]);


        $this->add([
            'name' => 'contactPerson',
            'type' => 'text',
            'options' => [
                'label' => 'Referens',
            ],
            'attributes' => [
                'id' => 'supplier-contact-person',
                'class' => 'form-control',
                'required' => 'required',
            ],
        ]);


        $this->add([
            'name' => 'telNumber',
            'type' => 'text',
            'options' => [
                'label' => 'Telefon',
            ],
            'attributes' => [
                'id' => 'supplier-telefon',
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
                'id' => 'supplier-email',
                'class' => 'form-control',

            ],
        ]);


        $this->add([
            'name' => 'seat',
            'type' => 'text',
            'options' => [
                'label' => 'Székhely',
            ],
            'attributes' => [
                'id' => 'supplier-seat',
                'class' => 'form-control',
                'required' => 'required',
            ],
        ]);


        $this->add([
            'name' => 'site',
            'type' => 'text',
            'options' => [
                'label' => 'Telephely',
            ],
            'attributes' => [
                'id' => 'supplier-site',
                'class' => 'form-control',

            ],
        ]);


        $this->add([
            'name' => 'moreInfo',
            'type' => 'textarea',
            'options' => [
                'label' => 'Egyéb információ',
            ],
            'attributes' => [
                'id' => 'supplier-moreInfo',
                'class' => 'form-control'
            ],
        ]);


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
                        array('name' => SubString::class, 'options' => array('max' => 129)),
                        array('name' => CapitalFirstLetter::class),
                    ),
                    'validators' => array(
                        array(
                            'name' => 'StringLength',
                            'options' => array(
                                'max' => 128,
                            ),
                        ),

                    )
                ),

                'taxNumber' => array(
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

                    )
                ),

                'contactPerson' => array(
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

                    )
                ),
                'telNumber' => array(
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

                    )
                ),

                'email' => array(
                    'required' => false,
                    'filters' => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                        array('name' => SubString::class, 'options' => array('max' => 129)),
                    ),
                    'validators' => array(
                        array(
                            'name' => 'StringLength',
                            'options' => array(
                                'max' => 128,
                            ),
                        ),

                    )
                ),


                'seat' => array(
                    'required' => true,
                    'filters' => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                        array('name' => SubString::class, 'options' => array('max' => 257)),
                    ),
                    'validators' => array(
                        array(
                            'name' => 'StringLength',
                            'options' => array(
                                'max' => 256,
                            ),
                        ),

                    )
                ),
                'site' => array(
                    'required' => false,
                    'filters' => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                        array('name' => SubString::class, 'options' => array('max' => 257)),
                    ),
                    'validators' => array(
                        array(
                            'name' => 'StringLength',
                            'options' => array(
                                'max' => 256,
                            ),
                        ),

                    )
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