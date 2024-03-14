<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.06.
 * Time: 13:43
 */

namespace Application\Service\Test;


class EgyikListeneresOsztalyService
{

    public function az_attacban_megadott_callback_fg($az_event_altal_atadott_obj)
    {

        //Az sendTweet event által átadott objektumot tudjuk módosítani
        $az_event_altal_atadott_obj->getParams()['content']->setValtozo('trigger után callback fgből módosítva');
        return true;
    }
}