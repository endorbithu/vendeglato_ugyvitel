<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.16.
 * Time: 15:35
 */

namespace Application\Service\Translator;


use Zend\Validator\AbstractValidator;
use Zend\I18n\Translator\Resources;
use Zend\Mvc\I18n\Translator;

class InitializeTranslator
{
    public function __invoke($e)
    {

        //FONTOS!!! a moduke.condig.phpban van configolva a translate

        $serviceManager = $e->getApplication()->getServiceManager();
        //$locale = PersistentConfigService::getInstance($em)->getValue('system_language');

        //locale
        date_default_timezone_set('Europe/Budapest');
        $translator = $serviceManager->get('translator');

        //$translator->setLocale(\Locale::acceptFromHttp($locale));
        $translator->setFallbackLocale('hu_HU'); //ha nincs fordítás egy adott nyelven, akkor erre a nyelvre fordítja

        $translator->addTranslationFilePattern(
            'gettext',
            dirname(__FILE__) . '/../../../../../languages',
            "%s.mo"
        );

        $translator->addTranslationFile(
            'phparray',
            './vendor/zendframework/zend-i18n-resources/languages/hu/Zend_Validate.php',
            'default',
            'hu_HU'  //  FFIXME: ez biztos jó így?
        );


        $translator->addTranslationFilePattern(
            'phparray',
            Resources::getBasePath(),
            Resources::getPatternForCaptcha()
        );


        AbstractValidator::setDefaultTranslator($translator);


        //FIXME: #131 allítsunk be cahce-t! $translator->setCache()


        // ZfcUser translator
        /*
       $translator->addTranslationFile(
           'gettext',
           dirname(__FILE__) . '/../ZfcUserHungarian/language/hu_HU.mo',
           'default',
           'hu_HU' // FFIXME: ez biztos jó így?
       );
        */


    }


}