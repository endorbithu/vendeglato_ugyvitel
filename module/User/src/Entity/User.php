<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.12.
 * Time: 22:57
 */


namespace User\Entity;

use Application\Entity\InjectionOfEntity\ServiceMangerAwareEntity;
use Application\Entity\Role;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;


/**
 * @Entity
 * @Table(name="app_user")
 */
class User extends ServiceMangerAwareEntity
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
    private $username;


    /**
     * @Column(type="string")
     */
    private $name;

    /**
     * @Column(type="string", nullable=true)
     */
    private $email;

    /**
     * @Column(type="string", nullable=true)
     */
    private $telephone;


    /**
     * @Column(type="string")
     */
    private $password;


    /**
     * @ManyToOne(targetEntity="Application\Entity\Role", inversedBy="user", fetch="EAGER")
     * @JoinColumn( name="role_id", referencedColumnName="id")
     **/
    private $role;

    /**
     * @Column(name="more_info", type="text")
     */
    private $moreInfo;


    /**
     * @OneToMany(targetEntity="Transaction\Entity\StockTransaction", mappedBy="user")
     **/
    private $stockTransaction;

    /**
     * @OneToMany(targetEntity="Application\Entity\EventLog", mappedBy="user")
     **/
    private $eventLog;

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
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = md5($password);
    }

    /**
     * @return mixed
     */
    public function getStockTransaction()
    {
        return $this->stockTransaction;
    }

    /**
     * @param mixed $stockTransaction
     */
    public function setStockTransaction($stockTransaction)
    {
        $this->stockTransaction = $stockTransaction;
    }



    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param mixed $telephone
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    }

    /**
     * @return mixed
     */
    public function getMoreInfo()
    {
        return $this->moreInfo;
    }

    /**
     * @param mixed $moreInfo
     */
    public function setMoreInfo($moreInfo)
    {
        $this->moreInfo = $moreInfo;
    }


    public function setConfirmPassword($pw)
    {
        return;
    }


    public function getConfirmPassword()
    {
        return;
    }


    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        if (is_object($role)) {
            $this->role = $role;
        } else {
            $roleFromEm = $this->sm->get('Doctrine\ORM\EntityManager')->getRepository(Role::class)->find($role);
            $this->role = $roleFromEm;
        }
    }


    public function getArrayCopy() //Doctrinehoz kell
    {
        $arrayCopy = [];
        $arrayCopy['User'] = get_object_vars($this);
        if (array_key_exists('sm', $arrayCopy['User'])) {
            unset($arrayCopy['User']['sm']);
        }
        return $arrayCopy;
    }


}