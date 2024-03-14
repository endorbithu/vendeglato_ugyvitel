<?php
namespace Catalog\Form\Fieldset;

use Application\Filter\CapitalFirstLetter;
use Application\Filter\NumericToFloat;
use Application\Filter\NumericToInt;
use Application\Filter\SubString;
use Application\Filter\TextareaString;
use Application\Validator\CustomLongString;
use Catalog\Entity\ProductGroup;
use Catalog\Entity\VatGroup;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;


class ProductFieldset extends Fieldset implements InputFilterProviderInterface
{

    private $inputFilterConfig;

    public function __construct($em, $entity)
    {
        $this->setHydrator(new DoctrineObject($em));
        $this->setObject($entity);
        parent::__construct('Product');
        $this->setInputFilterSpecification();


        $this->add([
            'type' => 'hidden',
            'name' => 'id',
            'attributes' => [
                'id' => 'product-id',
            ],
        ]);


        $this->add([
            'name' => 'name',
            'type' => 'text',
            'options' => [
                'label' => 'Név',
            ],
            'attributes' => [
                'id' => 'product-name',
                'class' => 'form-control',
                'required' => 'required',

            ],
        ]);

        $this->add([
            'name' => 'shortName',
            'type' => 'text',
            'options' => [
                'label' => 'Rövid név',
            ],
            'attributes' => [
                'id' => 'product-short-name',
                'class' => 'form-control',
            ],
        ]);

        $this->add([
            'name' => 'price',
            'type' => 'number',
            'options' => [
                'label' => 'Ár',
            ],
            'attributes' => [
                'id' => 'product-price',
                'class' => 'form-control',
                'step' => 'any',
                'required' => 'required',

            ],
        ]);

        /*
        $this->add(array(
            'name' => 'isNegativePrice',
            'type' => 'checkbox',
            'options' => array(
                'label' => 'Negatív érték?',
                'use_hidden_element' => true,
                'checked_value' => 1,
                'unchecked_value' => 0
            ),
            'attributes' => array(
                'value' => 0,
                'id' => 'product-is-negative-price'
            )
        ));

*/

        $this->add(array(
            'name' => 'isActive',
            'type' => 'checkbox',
            'options' => array(
                'label' => 'Aktív?',
                'use_hidden_element' => true,
                'checked_value' => 1,
                'unchecked_value' => 0
            ),
            'attributes' => array(
                'value' => 0,
                'id' => 'product-is-available'
            )
        ));


        $this->add(array(
            'name' => 'productGroup',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'object_manager' => $em,
                'target_class' => ProductGroup::class,
                'property' => 'name',
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array(),
                        'orderBy' => array('id' => 'ASC')
                    )
                ),
                'label' => 'Termék csoport',
            ),
            'attributes' => array(
                'id' => 'product-product-group',
                'class' => 'select2',
                'required' => 'required',
            ),

        ));


        $this->add(array(
            'name' => 'vatGroup',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'object_manager' => $em,
                'target_class' => VatGroup::class,
                'property' => 'name',
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array(),
                        'orderBy' => array('id' => 'ASC')
                    )
                ),
                'label' => 'ÁFA kategória',
            ),
            'attributes' => array(
                'id' => 'product-vat-group',
                'class' => 'select2',
                'required' => 'required',
            ),

        ));


        /*
                $this->add(array(
                    'name' => 'canHalf',
                    'type' => 'checkbox',
                    'options' => array(
                        'label' => 'Féladag?:',
                        'use_hidden_element' => true,
                        'checked_value' => 1,
                        'unchecked_value' => 0
                    ),
                    'attributes' => array(
                        'value' => 0,
                        'id' => 'product-can-half',
                    )
                ));


                $this->add([
                    'name' => 'halfPortion',
                    'type' => 'number',
                    'options' => [
                        'label' => 'Fél adag (% / 0.xx)',
                    ],
                    'attributes' => [
                        'id' => 'product-half-portion',
                        'class' => 'form-control',
                        'step' => 'any',
                        'max' => '100.0',
                    ],
                ]);

        */
        $this->add([
            'name' => 'prescription',
            'type' => 'textarea',
            'options' => [
                'label' => 'Recept',
            ],
            'attributes' => [
                'id' => 'product-prescription',
                'class' => 'form-control'
            ],
        ]);


        $this->add([
            'name' => 'moreInfo',
            'type' => 'textarea',
            'options' => [
                'label' => 'Egyéb információ',
            ],
            'attributes' => [
                'id' => 'product-more-info',
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
                        array('name' => SubString::class, 'options' => array('max' => 65)),
                        array('name' => CapitalFirstLetter::class),
                    ),
                    'validators' => array(
                        array(
                            'name' => 'StringLength',
                            'options' => array(
                                'min' => 1,
                                'max' => 64,
                            ),
                        ),
                    ),
                ),

                'shortName' => array(
                    'required' => false,
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


                'price' => array(
                    'required' => false,
                    'filters' => array(
                        array('name' => 'StripTags'),
                        array('name' => NumericToFloat::class),
                    ),
                    'validators' => array(
                        array('name' => 'IsFloat'),
                        array(
                            'name' => 'LessThan',
                            'options' => array(
                                'max' => 100000000,
                            ),
                        ),
                    ),
                ),

                /*
                                'isNegativePrice' => array(
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
                                                'max' => 2,
                                            ),
                                        ),
                                    ),
                                ),*/

                'isActive' => array(
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
                                'max' => 2,
                            ),
                        ),
                    ),
                ),


                'productGroup' => array(
                    'required' => true,
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


                'vatGroup' => array(
                    'required' => true,
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

                /*
                                'canHalf' => array(
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
                                                'max' => 2,
                                            ),
                                        ),

                                    ),
                                ),

                                'halfPortion' => array(
                                    'required' => false,
                                    'filters' => array(
                                        array('name' => 'StripTags'),
                                        array('name' => NumericToFloat::class),
                                        array('name' => PercentToDecimal::class),
                                    ),
                                    'validators' => array(
                                        array('name' => 'IsFloat'),
                                        array(
                                            'name' => 'LessThan',
                                            'options' => array(
                                                'max' => 100000000.00,
                                            ),
                                        ),
                                    ),
                                ),
                */
                'prescription' => array(
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