<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.12.
 * Time: 22:57
 */


namespace Application\Entity;

use Application\Entity\InjectionOfEntity\ServiceMangerAwareEntity;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\JoinColumn;
use Interop\Container\ContainerInterface;
use User\Entity\User;


/**
 * @Entity
 * @Table(name="app_event_log")
 */
class EventLog extends ServiceMangerAwareEntity
{

    /**
     * @Id
     * @GeneratedValue(strategy="AUTO")
     * @Column(type="integer")
     */
    private $id;

    /**
     * @Column(type="string")
     */
    private $message;


    /**
     * @ManyToOne(targetEntity="User\Entity\User", inversedBy="eventLog", fetch="EAGER" )
     * @JoinColumn(name="user_id", referencedColumnName="id")
     **/
    private $user;


    /**
     * @ManyToOne(targetEntity="EventType", inversedBy="eventLog", fetch="EAGER")
     * @JoinColumn(name="event_type_id", referencedColumnName="id")
     **/
    private $eventType;


    /**
     * @Column(name="date_time", type="datetime")
     */
    private $dateTime;

/**
* @Column(name="resource_id", type="integer", nullable=true)
*/
    private $resourceId;

    /**
     * @return mixed
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * @param mixed $dateTime
     */
    public function setDateTime($dateTime)
    {
        if (empty($dateTime)) {
            $this->dateTime = new \DateTime();
        } elseif (is_string($dateTime)) {
            $this->dateTime = new \DateTime($dateTime);
        } else {
            $this->dateTime = $dateTime;
        }

    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        if (is_object($user)) {
            $this->user = $user;
        } else {

            $userFromEm = $this->sm->get('Doctrine\ORM\EntityManager')->getRepository(User::class)->find($user);
            $this->user = $userFromEm;
        }
    }

    /**
     * @return mixed
     */
    public function getEventType()
    {
        return $this->eventType;
    }

    /**
     * @param mixed $eventType
     */
    public function setEventType($eventType)
    {
        if (is_object($eventType)) {
            $this->eventType = $eventType;
        } else {
            $eventTypeFromEm = $this->sm->get('Doctrine\ORM\EntityManager')->getRepository(EventType::class)->find($eventType);
            $this->eventType = $eventTypeFromEm;
        }
    }

    /**
     * @return mixed
     */
    public function getResourceId()
    {
        return $this->resourceId;
    }

    /**
     * @param mixed $resourceId
     */
    public function setResourceId($resourceId)
    {
        $this->resourceId = $resourceId;
    }




    public function getArrayCopy() //Doctrinehoz kell
    {
        $arrayCopy = [];
        $arrayCopy['EventLog'] = get_object_vars($this);
        if (array_key_exists('sm', $arrayCopy['EventLog'])) {
            unset($arrayCopy['EventLog']['sm']);
        }
        return $arrayCopy;
    }


}