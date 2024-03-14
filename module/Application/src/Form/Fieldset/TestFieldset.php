<?php
namespace Application\Form\Fieldset;

use Zend\Form\Element\Date;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Validator\CreditCard;
use Zend\Validator\Uuid;

class TestFieldset extends Fieldset implements InputFilterProviderInterface
{

    private $inputFilterConfig;

    public function __construct($em)
    {
        parent::__construct('user');
        $this->setInputFilterSpecification();

        $this->add([
            'type' => 'hidden',
            'name' => 'id',
        ]);

        $this->add([
            'name' => 'datet',
            'type' => Date::class,
            'options' => [
                'label' => 'Dátumidő',
            ],
            'attributes' => [
                'class' => 'form-control'
            ],
        ]);


        $this->add([
            'name' => 'username',
            'type' => 'text',
            'options' => [
                'label' => 'Felhasználónév',
            ],
            'attributes' => [
                'class' => 'form-control'
            ],
        ]);


        $this->add(array(
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'commissionPer',
            'options' => array(
                'object_manager' => $em,
                'target_class' => 'VideoStore\Entity\AffiliateCampaignType',
                'property' => 'name',
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array(),
                        'orderBy' => array('id' => 'ASC')
                    )
                ),
                'label' => 'Type:',
            ),
            'attributes' => array(
                'id' => 'commissionPer',
            ),

        ));

        $this->add([
            'name' => 'testmezo',
            'type' => 'text',
            'options' => [
                'label' => 'Tesmező',
            ],
            'attributes' => [
                'class' => 'form-control'
            ],
        ]);

        $this->add([
            'name' => 'password',
            'type' => 'password',
            'options' => [
                'label' => 'Jelszó',
            ],
            'attributes' => [
                'class' => 'form-control'
            ],
        ]);
    }


    public function setInputFilterSpecification(array $inputConfig = array())
    {
        if (empty($inputConfig)) {
            $this->inputFilterConfig =
                array(
                    'id' => array(
                        'required' => false,
                    ),

                    'username' => array(
                        'required' => true,
                    ),

                    'password' => array(
                        'required' => true,
                    ),

                    'testmezo' => array(
                        'required' => true,
                    ),

                    'egyiktestmezo' => array(
                        //'required' => false,
                        'filters' => array(
                            array('name' => \Application\Filter\AccentToAscii::class), //saját filter ez épp ékezetes betűkből csinál asciit
                            //html tageket szűri ki
                            array('name' => 'StripTags', 'options' => array(
                                'allowTags' => array('p', 'a'),
                            ),),
                            //elejéről végéről az összes spacet, de be lehet állítani, hogy mást i
                            array('name' => 'StringTrim'),

                            //Csak a betűket és számoka hagyja meg ékezeteseket is, így kell paraméterezni
                            array('name' => 'Alnum'),
                            //Csak a betűket ékezeteseket is
                            array('name' => 'Alpha', 'options' => array( //Csak a betűket hagyja meg ékezeteseket is
                                'allowWhiteSpace' => true, //így kell paraméterezni
                            )),

                            //Ami nem 0 vagy null vagy 0.0 vagy üres stb az true a többi false, ilyenkor a stringes validátrok nem mennek
                            array('name' => 'Boolean'),


                            //ha csak lazán van megadva az url pl, inde.hu akkor szépen kiegészíti a schémával
                            array('name' => 'UriNormalize', 'options' => array(
                                'enforcedScheme' => 'https'
                            )),

                            //ha nicsenek benne a stringbe, akkor nullal tér vissza
                            array('name' => 'Whitelist', 'options' => array(
                                'list' => array('legyenaszovegbe1', 'legyenaszovegbe2'),
                            )),

                            //ha  benne vannak a stringbe, akkor nullal tér vissza
                            array('name' => 'Blacklist', 'options' => array(
                                'list' => array('ne legyen benne', 'ne legyenbenne2'),
                            )),

                            //minden nem számot töröl
                            array('name' => 'Digits'),

                            //Ha a php empty() fg truval tér vissza az adott stringgel akkor null-ra változtataj
                            array('name' => 'ToNull'),

                            //Bármi forma számból 1.000, vagy 80%, vagy 1,23 csinál intet vagy floatot
                            array('name' => 'NumberParse'),

                            //kisbetű
                            array('name' => 'StringToLower'),

                            //nagybetű
                            array('name' => 'StringToUpper'),

                            // \n \r töri ki
                            array('name' => 'StripNewlines'),


                            //kicseréli a megadott tömbben lévő stringeket a megadott replace-re
                            array('name' => 'PregReplace', 'options' => array(
                                'pattern' => array('hat', '€'),
                                'replacement' => array('6', 'EURO'),
                            ),),
                        ),

                        'validators' => array(

                            //lehet trükközni, hogy beraksz ide egy swl selectet stb doctrinnal kicsit trükkösebb v2-be mehet
                            /*  array('name' => 'RecordExists',
                                'options' => array(
                                     'table'   => 'users',
                                     'field'   => 'emailaddress',
                                     'adapter' => $dbAdapter,
                                 ),
                            ),*/


                            array(
                                'name' => 'Digits',
                            ),
                            array(
                                'name' => 'IsInt',
                            ),
                            array(
                                'name' => 'EmailAddress',
                            ),

                            array('name' => 'Hex'),
                            array('name' => 'Iban'),
                            array('name' => 'Ip'),
                            array('name' => Uuid::class),


                            //ha az egyik mezővel azonosnak kell lennie, pl passwordnél ha
                            array(
                                'name' => 'Identical',
                                'options' => array(
                                    'token' => 'password1',
                                ),
                            ),

                            //ha a többen benne van a megadott érték
                            array(
                                'name' => 'InArray',
                                'options' => array(
                                    'haystack' => ['value1', 'value2', 'valueN']
                                ),
                            ),

                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'min' => 20,
                                    'max' => 120,
                                ),
                            ),
                            //számokat vizsgálja
                            array(
                                'name' => 'Between',
                                'options' => array(
                                    'min' => 20,
                                    'max' => 120,
                                ),
                            ),
                            array(
                                'name' => 'GreaterThan',
                                'options' => array(
                                    'min' => 20,

                                ),
                            ),
                            array(
                                'name' => 'CreditCard',
                                'type' => array(
                                    [CreditCard::AMERICAN_EXPRESS, CreditCard::VISA]
                                ),
                            ),
                            array(
                                'name' => 'Date',
                                'format' => 'Y-m-d H:i:s'
                            ),

                            array(
                                'name' => 'Regex',
                                'break_chain_on_failure' => true,
                                'options' => array(
                                    'pattern' => '/^http.*$/',
                                    'message' => 'Not valid Url! Valid: http://...'
                                )
                            ),

                            //Callbacknél meg azt csinálsz amit akarsz egymással is összehasonlítod a mezőket stb
                            array(
                                'name' => 'Callback',
                                'options' => array(
                                    'messages' => array(
                                        \Zend\Validator\Callback::INVALID_VALUE => 'The end date should be greater than start date',
                                    ),

                                    //$value, $context a fieldek arrayja, $context['fieldnev']-vel hivatkozhatunk rá
                                    'callback' => function ($value, $context = array()) {
                                        if ($context['validFromDateTime'] == null || $value == null) return true;
                                        $validFromDateTime = \DateTime::createFromFormat('Y-m-d', $context['validFromDateTime']);
                                        $validUntilDateTime = \DateTime::createFromFormat('Y-m-d', $value);
                                        return $validUntilDateTime >= $validFromDateTime;
                                    },
                                ),
                            ),


                        ),

                    ),
                );

            return;
        }
        $this->inputFilterConfig = $inputConfig;
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