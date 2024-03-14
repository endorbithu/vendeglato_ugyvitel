<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.07.
 * Time: 18:58
 */

namespace Application\Model;


class StatusMessages
{
    private $statusMessages;


    public function __construct()
    {
        $this->statusMessages =  array(
            'info' => array(),
            'success' => array(),
            'warning' => array(),
            'error' => array(),
            );
    }

    public function addMessage($message, $type = "success")
    {
        $this->statusMessages[$type][] = $message;
    }

    /**
     * @return mixed
     */
    public function getSuccessMessages()
    {
        return $this->statusMessages['success'];
    }

    /**
     * @return array
     */
    public function getWarningMessages()
    {
        return $this->statusMessages['warning'];
    }

    /**
     * @return array
     */
    public function getErrorMessages()
    {
        return $this->statusMessages['error'];
    }



    /**
     * @return array
     */
    public function getInfoMessages()
    {
        return $this->statusMessages['info'];
    }




}