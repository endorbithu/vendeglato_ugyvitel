<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.16.
 * Time: 3:03
 */

namespace Application\Service\Test;

use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;

class TweetService implements EventManagerAwareInterface
{
    /**
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * @param string $content
     */
    public function sendTweet($content)
    {
        // Send the tweet with Twitter's API...

        // Trigger an event
        $this->getEventManager()->trigger('sendTweet', null, array('content' => $content));
    }

    /**
     * @param  EventManagerInterface $eventManager
     * @return void
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $this->eventManager = $eventManager;
    }

    /**
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (null === $this->eventManager) {
            $this->setEventManager(new EventManager());
        }

        return $this->eventManager;
    }
}