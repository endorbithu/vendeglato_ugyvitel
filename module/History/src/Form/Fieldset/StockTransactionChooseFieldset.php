<?php
namespace History\Form\Fieldset;

use Application\Filter\NumericToInt;
use Transaction\Entity\StockTransactionType;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;


class StockTransactionChooseFieldset extends Fieldset implements InputFilterProviderInterface
{


    private $inputFilterConfig;

    public function __construct($em)
    {
        //$this->setHydrator(new DoctrineObject($em));
        parent::__construct('StockTransactionChoose');
        $this->setInputFilterSpecification();


        $dateTime = new \DateTime();


        $this->add(array(
            'name' => 'yearFrom',
            'type' => 'Number',
            'options' => array('label' => 'Időintervallum'),
            'attributes' => array(
                'id' => 'date-time-dt',
                'class' => 'form-control year-input',
                'value' => $dateTime->format('Y'),
            ),

        ));

        $this->add(array(
            'name' => 'monthFrom',
            'type' => 'Number',
            'options' => array(),
            'attributes' => array(
                'id' => 'date-time-dt',
                'class' => 'form-control month-input',
                'value' => $dateTime->format('m'),
            ),

        ));


        $this->add(array(
            'name' => 'dayFrom',
            'type' => 'Number',
            'options' => array(),
            'attributes' => array(
                'id' => 'date-time-dt',
                'class' => 'form-control day-from',
                'value' => $dateTime->format('d'),
            ),

        ));


        $this->add(array(
            'name' => 'yearTo',
            'type' => 'Number',
            'options' => array(),
            'attributes' => array(
                'id' => 'date-time-dt',
                'class' => 'year-input form-control',
                'value' => $dateTime->format('Y'),
            ),

        ));

        $this->add(array(
            'name' => 'monthTo',
            'type' => 'Number',
            'options' => array(),
            'attributes' => array(
                'id' => 'date-time-dt',
                'class' => 'form-control month-input',
                'value' => $dateTime->format('m'),
            ),

        ));

        $this->add(array(
            'name' => 'dayTo',
            'type' => 'Number',
            'options' => array(),
            'attributes' => array(
                'id' => 'date-time-dt',
                'class' => 'form-control day-input',
                'value' => $dateTime->format('d'),
            ),

        ));

        $this->add(array(
            'name' => 'stockTransactionType',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'object_manager' => $em,
                'target_class' => StockTransactionType::class,
                'property' => 'name',
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array(),
                        'orderBy' => array('name' => 'ASC')
                    )
                ),
                'label' => 'Művelet',
            ),
            'attributes' => array(
                'id' => 'stocktransaction-type',
                'class' => 'select2',
            ),

        ));


    }


    public function setInputFilterSpecification(array $inputConfig = array())
    {
        $this->inputFilterConfig = array_merge(
            array(

                'yearFrom' => array(
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
                                'max' => 2099,
                            ),
                        ),
                    ),
                ),

                'monthFrom' => array(
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
                                'max' => 13,
                            ),
                        ),
                    ),
                ),

                'dayFrom' => array(
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
                                'max' => 32,
                            ),
                        ),
                    ),
                ),
                'yearTo' => array(
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
                                'max' => 2099,
                            ),
                        ),
                    ),
                ),

                'monthTo' => array(
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
                                'max' => 13,
                            ),
                        ),
                    ),
                ),

                'dayTo' => array(
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
                                'max' => 32,
                            ),
                        ),
                    ),
                ),

                'stockTransactionType' => array(
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