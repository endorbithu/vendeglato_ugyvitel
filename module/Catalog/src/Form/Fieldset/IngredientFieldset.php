<?php
namespace Catalog\Form\Fieldset;

use Application\Filter\CapitalFirstLetter;
use Application\Filter\NumericToFloat;
use Application\Filter\NumericToInt;
use Application\Filter\SubString;
use Application\Filter\TextareaString;
use Application\Validator\CustomLongString;
use Catalog\Entity\IngredientGroup;
use Catalog\Entity\IngredientUnit;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;


class IngredientFieldset extends Fieldset implements InputFilterProviderInterface
{


    private $inputFilterConfig;

    public function __construct($em, $entity)
    {
        $this->setHydrator(new DoctrineObject($em));
        $this->setObject($entity);
        parent::__construct('Ingredient');
        $this->setInputFilterSpecification();


        $this->add([
            'type' => 'hidden',
            'name' => 'id',
            'attributes' => [
                'id' => 'ingredient-id',
            ],
        ]);


        $this->add([
            'name' => 'name',
            'type' => 'text',
            'options' => [
                'label' => 'Név',
            ],
            'attributes' => [
                'id' => 'ingredient-name',
                'class' => 'form-control',
                'required' => 'required',

            ],
        ]);


        $this->add(array(
            'name' => 'ingredientGroup',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'object_manager' => $em,
                'target_class' => IngredientGroup::class,
                'property' => 'name',
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array(),
                        'orderBy' => array('name' => 'ASC')
                    )
                ),
                'label' => 'Alapanyag csoport',
            ),
            'attributes' => array(
                'id' => 'ingredient-group',
                'class' => 'select2',
                'required' => 'required',
            ),

        ));

        $this->add(array(
            'name' => 'ingredientUnit',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'object_manager' => $em,
                'target_class' => IngredientUnit::class,
                'property' => 'name',
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array(),
                        'orderBy' => array('name' => 'ASC')
                    )
                ),
                'label' => 'Alapanyag mértékegység',
            ),
            'attributes' => array(
                'id' => 'ingredient-unit',
                'class' => 'select2',
                'required' => 'required',
                'placeholder' => ' ',

            ),

        ));


        $this->add([
            'name' => 'minimumAmount',
            'type' => 'number',
            'options' => [
                'label' => 'Minimum mennyiség',
            ],
            'attributes' => [
                'id' => 'ingredient-minimum-amount',
                'class' => 'form-control',
                'required' => 'required',
                'step' => 'any',
            ],
        ]);




        $this->add([
            'name' => 'moreInfo',
            'type' => 'textarea',
            'options' => [
                'label' => 'Egyéb információ',
            ],
            'attributes' => [
                'id' => 'ingredient-more-info',
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

                    )
                ),


                'ingredientGroup' => array(
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


                'ingredientUnit' => array(
                    'required' => true,
                    'filters' => array(
                        array('name' => 'StripTags'),
                        array('name' => NumericToInt::class)),

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

                'minimumAmount' => array(
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
                            )
                        ),
                        array('name' => CustomLongString::class),
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