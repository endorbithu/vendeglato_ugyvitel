<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.12.
 * Time: 22:57
 */


namespace Catalog\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;


/**
 * @Entity
 * @Table(name="cat_tool_group")
 */
class ToolGroup
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
     * @OneToMany(targetEntity="Tool", mappedBy="toolGroup")
     **/
    private $tool;


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
    public function getTool()
    {
        return $this->tool;
    }

    /**
     * @param mixed $tool
     */
    public function setTool($tool)
    {
        $this->tool = $tool;
    }

   


    public function getArrayCopy() //Doctrinehoz kell
    {
        $arrayCopy = [];
        $arrayCopy['ToolGroup'] = get_object_vars($this);
        if (array_key_exists('sm', $arrayCopy['ToolGroup'])) {
            unset($arrayCopy['ToolGroup']['sm']);
        }
        return $arrayCopy;
    }


}