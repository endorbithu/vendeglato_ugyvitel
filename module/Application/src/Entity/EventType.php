<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.12.
 * Time: 22:57
 */


namespace Application\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\JoinColumn;



/**
 * @Entity
 * @Table(name="app_event_type")
 */
class EventType
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
    private $name;


    /**
     * @OneToMany(targetEntity="EventLog", mappedBy="eventType")
     **/
    private $eventLog;


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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getEventLog()
    {
        return $this->eventLog;
    }

    /**
     * @param mixed $eventLog
     */
    public function setEventLog($eventLog)
    {
        $this->eventLog = $eventLog;
    }


    public function getArrayCopy() //Doctrinehoz kell
    {
        $arrayCopy = [];
        $arrayCopy['EventType'] = get_object_vars($this);
        if (array_key_exists('sm', $arrayCopy['EventType'])) {
            unset($arrayCopy['EventType']['sm']);
        }
        return $arrayCopy;
    }


}