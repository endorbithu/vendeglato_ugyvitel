<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.10.
 * Time: 23:03
 */

namespace Catalog\Service\Currency;


use Interop\Container\ContainerInterface;
use Zend\Http\Header\SetCookie;

class CurrencyConverterRegistered
{
    private $sm;
    private $rateBasedOnSystemCurrency;
    private $selectedCurrency;

    public function  __construct(ContainerInterface $serviceManager)
    {
        $this->sm = $serviceManager;
        $this->systemCurrency = $this->sm->get('config')['currency']['systemCurrency'];
        $this->setSelectedCurrency($this->systemCurrency);
    }

    /**
     * A rendszer pénzneméből kiindulva a rattel számolva kijön a álasztottcurrency értéke
     * @param $amount
     * @return float
     */
    public function getConvertedPrice($amount)
    {
        return (float)($amount * $this->rateBasedOnSystemCurrency);
    }


    /**
     * A rendszer pénzneméből kiindulva a rattel számolva kijön a álasztottcurrency értéke
     * @param $amount
     * @return float
     */
    public function setSystemPriceFromForeign($amount)
    {
        return (float)($amount / $this->rateBasedOnSystemCurrency);
    }


    //route eseménynél ezt hívjuk meg, hogy átírjuk a
    public function changeCurrency($e)
    {

        //ha a GETben megvan adva a ?curency=...
        if (!empty($e->getRequest()->getQuery('currency'))) {
            $currencyFromGet = substr($e->getRequest()->getQuery('currency'),0,3);

            //A hu_HU szĂ¶vegre belĹ‘jĂĽk a validĂˇtort, regexszel nĂ©zzĂĽk az a tuti
            $RegexValidator = $e->getApplication()->getServiceManager()->get('ValidatorManager')->get('Regex', array('pattern' => '/^[A-Z][A-Z][A-Z]$/'));
            $RegexValidator->setPattern('/^[A-Z][A-Z][A-Z]$/');

            //ha nem okĂ© a validĂˇlĂˇs akkor false Ă©s statusba kiĂ­rji mi a helyzet
            if (!$RegexValidator->isValid($currencyFromGet)) {
                $e->getViewModel()->getVariable('statusMessages')->addMessage('Érvénytelen pénznem lett megadva a címsorban!(ez helyes pl: /?currency=HUF','error');
                return;
            }


            //ha helyes a megadott nyelv:
            $currencyCookie = new SetCookie(
                'currency',
                $currencyFromGet,
                time() + (30 * 60 * 60 * 24), '/'
            );

            //Cookie beĂˇllĂ­tĂˇs
            $e->getResponse()->getHeaders()->addHeader($currencyCookie);

            //a változóban is átírjuk az
            $this->setSelectedCurrency($currencyFromGet);

            return;
        }


        // ha a Cookieban tárolva van a currency
        if (!empty($e->getRequest()->getHeaders()->get('Cookie')->currency)) {
            $currencyFromCookie = substr($e->getRequest()->getHeaders()->get('Cookie')->currency,0,3);

            //A HUF szĂ¶vegre belĹ‘jĂĽk a validĂˇtort, regexszel nĂ©zzĂĽk az a tuti
            $RegexValidator = $e->getApplication()->getServiceManager()->get('ValidatorManager')->get('Regex', array('pattern' => '/^[A-Z][A-Z][A-Z]$/'));
            $RegexValidator->setPattern('/^[A-Z][A-Z][A-Z]$/');
            $RegexValidator->isValid($currencyFromCookie);

            $currencyInCookie = $currencyFromCookie;

            //meghosszabbítjuk a Cookie idejét:
            //ha helyes a megadott currency:
            $currencyCookie = new SetCookie(
                'currency',
                $currencyInCookie,
                time() + (365 * 60 * 60 * 24), '/'
            );

            //Cookie beĂˇllĂ­tĂˇs
            $e->getResponse()->getHeaders()->addHeader($currencyCookie);

            //a confgba is átírjuk a sm-en keresztül
            $this->setSelectedCurrency($currencyInCookie);

        }


    }

    /**
     * @return mixed
     */
    public function getSelectedCurrency()
    {
        return $this->selectedCurrency;
    }

    /**
     * @param mixed $selectedCurrency
     */
    public function setSelectedCurrency($selectedCurrency)
    {
        //csak arra engedi változtatni, ami benne van a rendszerben
        if (array_key_exists($selectedCurrency, $this->sm->get('config')['currency']['currency'])) {
            $this->selectedCurrency = $selectedCurrency;
            $this->rateBasedOnSystemCurrency = $this->sm->get('config')['currency']['currency'][$this->selectedCurrency]['rateFromSystemCurrency'];
        }
    }


}