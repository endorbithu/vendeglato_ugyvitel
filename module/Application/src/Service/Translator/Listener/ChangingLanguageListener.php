<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.16.
 * Time: 15:14
 */

namespace Application\Service\Translator\Listener;
use Zend\Http\Header\SetCookie;

/**
// LANGUAGE VÁLTOZTATÁS
// ha valaki nyelvet vált, azt lekezeljük: route eseménynél megvizsgáljuk a GET-et és ha ki van töltve akkor módosul
//ezeket a fg-ket ki lehet szervezni majd
 *
 * Class ChangingLanguageListener
 * @package Application\Listener
 */

class ChangingLanguageListener
{
    public function __invoke($e)
    {

        ///////////////////////////////////////////
        //ha a GETben megvan adva a ?language=...
        ////////////////////////////////////////////
        if (!empty($e->getRequest()->getQuery('language'))) {
            $languageFromGet = substr($e->getRequest()->getQuery('language'),0,5);

            //A hu_HU szĂ¶vegre belĹ‘jĂĽk a validĂˇtort, regexszel nĂ©zzĂĽk az a tuti

            $RegexValidator = $e->getApplication()->getServiceManager()->get('ValidatorManager')->get('Regex', array('pattern' => '/^[a-z][a-z]_[A-Z][A-Z]$/'));
            $RegexValidator->setPattern('/^[a-z][a-z]_[A-Z][A-Z]$/');


            //ha nem okĂ© a validĂˇlĂˇs akkor false Ă©s statusba kiĂ­rji mi a helyzet
            if (!$RegexValidator->isValid($languageFromGet)) {
                $e->getViewModel()->getVariable('statusMessages')->addMessage('Érvénytelen nyelv lett megadva a címsorban!(ez helyes pl: /?language=hu_HU','error');
                return;
            }

            //ha helyes a megadott nyelv:
            $languageCookie = new SetCookie(
                'language',
                $languageFromGet,
                time() + (365 * 60 * 60 * 24), '/'
            );

            //Cookie beĂˇllĂ­tĂˇs
            $e->getResponse()->getHeaders()->addHeader($languageCookie);


            //language (locale) beĂˇllĂ­tĂˇsa a transletban
            $e->getApplication()->getServiceManager()->get('translator')->setLocale($languageFromGet);



            return;
        }



        ///////////////////////////////////////////
        // ha a Cookieban tárolva van a nyelv
        /////////////////////////////////////////
        if (!empty($e->getRequest()->getHeaders()->get('Cookie')->language)) {
            $languageFromCookie = substr($e->getRequest()->getHeaders()->get('Cookie')->language,0,5);

            //A hu_HU szĂ¶vegre belĹ‘jĂĽk a validĂˇtort, regexszel nĂ©zzĂĽk az a tuti
            $RegexValidator = $e->getApplication()->getServiceManager()->get('ValidatorManager')->get('Regex', array('pattern' => '/^[a-z][a-z]_[A-Z][A-Z]$/'));
            $RegexValidator->setPattern('/^[a-z][a-z]_[A-Z][A-Z]$/');
            $RegexValidator->isValid($languageFromCookie);


            $languageCookie = $languageFromCookie;
            $e->getApplication()->getServiceManager()->get('translator')->setLocale($languageCookie);


            //meghosszabbítjuk a Cookie idejét:
            //ha helyes a megadott nyelv:
            $languageCookie = new SetCookie(
                'language',
                $languageCookie,
                time() + (365 * 60 * 60 * 24), '/'
            );

            //Cookie beĂˇllĂ­tĂˇs
            $e->getResponse()->getHeaders()->addHeader($languageCookie);
            return;
        }




    }

}