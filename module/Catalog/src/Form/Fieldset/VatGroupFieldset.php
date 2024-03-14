<?php
namespace Catalog\Form\Fieldset;

use Application\Filter\CapitalFirstLetter;
use Application\Filter\NumericToFloat;
use Application\Filter\NumericToInt;
use Application\Filter\PercentToDecimal;
use Application\Filter\SubString;
use Application\Validator\CustomString;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;


class VatGroupFieldset extends Fieldset implements InputFilterProviderInterface
{


    private $inputFilterConfig;

    public function __construct($em, $entity)
    {
        $this->setHydrator(new DoctrineObject($em));
        $this->setObject($entity);
        parent::__construct('VatGroup');
        $this->setInputFilterSpecification();


        $this->add([
            'type' => 'hidden',
            'name' => 'id',
            'attributes' => [
                'id' => 'vat-group-id',
            ]
        ]);


        $this->add([
            'name' => 'name',
            'type' => 'text',
            'options' => [
                'label' => 'Név',
            ],
            'attributes' => [
                'id' => 'vat-group-name',
                'class' => 'form-control',
                'required' => 'required',

            ],
        ]);

        $this->add([
            'name' => 'vatValue',
            'type' => 'number',
            'options' => [
                'label' => 'Áfa érték (% / 0.xx)',
            ],
            'attributes' => [
                'id' => 'vat-group-vat-value',
                'class' => 'form-control',
                'required' => 'required',
                'step' => 'any',
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

                                'max' => 64,
                            ),
                        ),
                        array('name' => CustomString::class),

                    ),
                ),

                'vatValue' => array(
                    'required' => true,
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
                                'max' => 1,
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