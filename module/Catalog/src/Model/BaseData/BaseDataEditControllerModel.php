<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.13.
 * Time: 9:07
 */

namespace Catalog\Model\BaseData;


class BaseDataEditControllerModel
{

    protected $misc = [];
    protected $actionName;
    protected $entity;
    protected $repository;
    protected $title;
    protected $id;
    protected $modifyDbByFormService;
    protected $form;
    protected $deleteFromDbService;


    /**
     * @return mixed
     */
    public function getDeleteFromDbService()
    {
        return $this->deleteFromDbService;
    }

    /**
     * @param mixed $deleteFromDbService
     */
    public function setDeleteFromDbService($deleteFromDbService)
    {
        $this->deleteFromDbService = $deleteFromDbService;
    }


    /**
     * @return mixed
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param mixed $form
     */
    public function setForm($form)
    {
        $this->form = $form;
    }


    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param mixed $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }


    /**
     * @return mixed
     */
    public function getModifyDbByFormService()
    {
        return $this->modifyDbByFormService;
    }

    /**
     * @param mixed $modifyDbByFormService
     */
    public function setModifyDbByFormService($modifyDbByFormService)
    {
        $this->modifyDbByFormService = $modifyDbByFormService;
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
    public function getMisc()
    {
        return $this->misc;
    }

    /**
     * @param mixed $misc
     */
    public function setMisc($misc)
    {
        $this->misc = $misc;
    }

    /**
     * @param array $newMisc
     */
    public function addMisc(array $newMisc)
    {
        foreach ($newMisc as $key => $misc) {
            $this->misc[$key] = $misc;
        }


    }


    /**
     * @return mixed
     */
    public function getActionName()
    {
        return $this->actionName;
    }

    /**
     * @param mixed $actionName
     */
    public function setActionName($actionName)
    {
        $this->actionName = $actionName;
    }

    /**
     * @return mixed
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param mixed $repository
     */
    public function setRepository($repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }


}