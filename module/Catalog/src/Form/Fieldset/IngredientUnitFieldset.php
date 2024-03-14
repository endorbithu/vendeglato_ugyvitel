<?php
namespace Catalog\Form\Fieldset;

use Application\Filter\NumericToInt;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;


class IngredientUnitFieldset extends Fieldset implements InputFilterProviderInterface
{


    private $inputFilterConfig;

    public function __construct($em, $entity)
    {
        $this->setHydrator(new DoctrineObject($em));
        $this->setObject($entity);
        parent::__construct('IngredientUnit');
        $this->setInputFilterSpecification();


        $this->add([
            'type' => 'hidden',
            'name' => 'id',
            'attributes' => [
                'id' => 'ingredient-unit-id',
            ],
        ]);


        $this->add([
            'name' => 'name',
            'type' => 'text',
            'options' => [
                'label' => 'Név',
            ],
            'attributes' => [
                'id' => 'ingredient-unit-name',
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
                'id' => 'ingredient-unit-name',
                'class' => 'form-control',
                'required' => 'required',

            ],
        ]);

        $this->add(array(
            'name' => 'isDecimal',
            'type' => 'checkbox',
            'options' => array(
                'label' => 'Tizedes értékek?',
                'use_hidden_element' => true,
                'checked_value' => 1,
                'unchecked_value' => 0
            ),
            'attributes' => array(
                'value' => 0,
                'id' => 'ingredient-unit-is-decimal'
            )
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
                        array('name' => 'StringTrim'),
                        array('name' => 'StripTags'),
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

                'shortName' => array(
                    'required' => true,
                    'filters' => array(
                        array('name' => 'StringTrim'),
                        array('name' => 'StripTags'),
                    ),
                    'validators' => array(
                        array(
                            'name' => 'StringLength',
                            'options' => array(
                                'max' => 8,
                            ),
                        ),

                    ),
                ),


                'isDecimal' => array(
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