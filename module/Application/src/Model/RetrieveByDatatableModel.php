<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.09.
 * Time: 6:47
 */

namespace Application\Model;


class RetrieveByDatatableModel
{
    protected $name;
    protected $description;
    protected $header;
    protected $data;
    protected $input;
    protected $action;
    protected $orderColumn;
    protected $orderColumnDir;
    protected $selectedRow;
    protected $selectable;
    protected $naked;

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
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param mixed $header
     */
    public function setHeader($header)
    {
        $this->header = $header;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @param mixed $input
     */
    public function setInput($input)
    {
        $this->input = $input;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param mixed $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @return mixed
     */
    public function getOrderColumn()
    {
        return $this->orderColumn;
    }

    /**
     * @param mixed $orderColumn
     */
    public function setOrderColumn($orderColumn)
    {
        $this->orderColumn = $orderColumn;
    }

    /**
     * @return mixed
     */
    public function getOrderColumnDir()
    {
        return $this->orderColumnDir;
    }

    /**
     * @param mixed $orderColumnDir
     */
    public function setOrderColumnDir($orderColumnDir)
    {
        $this->orderColumnDir = $orderColumnDir;
    }

    /**
     * @return mixed
     */
    public function getSelectedRow()
    {
        return $this->selectedRow;
    }

    /**
     * @param mixed $selectedRow
     */
    public function setSelectedRow($selectedRow)
    {
        $this->selectedRow = $selectedRow;
    }

    /**
     * @return mixed
     */
    public function getSelectable()
    {
        return $this->selectable;
    }

    /**
     * @param mixed $selectable
     */
    public function setSelectable($selectable)
    {
        $this->selectable = $selectable;
    }

    /**
     * @return mixed
     */
    public function getNaked()
    {
        return $this->naked;
    }

    /**
     * @param mixed $naked
     */
    public function setNaked($naked)
    {
        $this->naked = $naked;
    }


}