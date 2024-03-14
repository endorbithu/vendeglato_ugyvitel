<?php
namespace Catalog\Form\Fieldset;

use Application\Filter\CapitalFirstLetter;
use Application\Filter\NumericToFloat;
use Application\Filter\NumericToInt;
use Application\Filter\PercentToDecimal;
use Application\Filter\SubString;
use Application\Filter\TextareaString;
use Application\Validator\CustomLongString;
use Application\Validator\CustomString;
use Catalog\Entity\IngredientGroup;
use Catalog\Entity\IngredientUnit;
use Catalog\Entity\Storage;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;


class IngrTransactionFieldset extends Fieldset implements InputFilterProviderInterface
{
    private $inputFilterConfig;

    public function __construct($em, $entity)
    {
        $this->setHydrator(new DoctrineObject($em));
        $this->setObject($entity);
        parent::__construct('StockTransaction');
        $this->setInputFilterSpecification();


        $this->add([
            'name' => 'id',
            'type' => 'hidden',
            'attributes' => [
                'id' => 'ingr-transaction-id',
            ]
        ]);


        $this->add([
            'name' => 'dateTime',
            'type' => 'hidden',

            'attributes' => [
                'id' => 'ingr-transaction-datetime',

            ],
        ]);

        $this->add([
            'name' => 'stockTransactionType',
            'type' => 'hidden',
            'attributes' => [
                'id' => 'ingr-transaction-ingr-transaction-type',

            ],
        ]);

        $this->add([
            'name' => 'fromStorage',
            'type' => 'hidden',
            'attributes' => [
                'id' => 'ingr-transaction-from-storage',

            ],
        ]);

        $this->add([
            'name' => 'toStorage',
            'type' => 'hidden',
            'attributes' => [
                'id' => 'ingr-transaction-to-storage',

            ],
        ]);

        $this->add([
            'name' => 'user',
            'type' => 'hidden',
            'attributes' => [
                'id' => 'ingr-transaction-user',

            ],
        ]);


        $this->add([
            'name' => 'moreInfo',
            'type' => 'textarea',
            'options' => [
                'label' => 'Egyéb megjegyzés',
            ],
            'attributes' => [
                'id' => 'ingr-transaction-more-info',
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

                'fromStorage' => array(
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

                'toStorage' => array(
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

                'user' => array(
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


                'stockTransactionType' => array(
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

                'dateTime' => array(
                    'required' => false,
                    'filters' => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                        array('name' => SubString::class, 'options' => array('max' => 17)),
                    ),
                    'validators' => array(
                        array(
                            'name' => 'StringLength',
                            'options' => array(
                                'max' => 16,
                            ),
                        ),
                    ),
                ),


                'moreInfo' => array(
                    'required' => false,
                    'filters' => array(
                        array('name' => 'StripTags'),
                        array('name' => SubString::class, 'options' => array('max' => 513)),
                        array('name' => TextareaString::class),
                    ),
                    'validators' => array(
                        array(
                            'name' => 'StringLength',
                            'options' => array(
                                'max' => 512,
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