<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.04.
 * Time: 9:57
 */

namespace Application\Service\Crud;


use Zend\Form\Form;

class ManipulateEntityByForm implements IManipulateEntityByForm
{

    protected $em;
    protected $postData;
    protected $contentId;
    protected $form;
    protected $entity;
    protected $repository;
    protected $isValid = false;
    protected $entityName;

    public function __construct($em)
    {
        $this->em = $em;
    }

    public function setContentId($contentId)
    {
        $this->contentId = $contentId;
    }

    public function setForm(Form $form)
    {
        $this->form = $form;
    }

    public function getForm()
    {
        return $this->form;
    }

    public function setPostData(array $postData)
    {
        $this->postData = $postData;
    }


    public function setEntity($enity)
    {
        $this->entity = $enity;
        $this->entityName = substr(strrchr(get_class($enity), "\\"), 1);
    }

    public function setRepository($repository)
    {
        $this->repository = $repository;
    }


    public function isValid()
    {
        $this->form->setData($this->postData);
        if ($this->form->isValid()) $this->isValid = true;

        if ($this->isValid === false) {
            throw new \Exception('Hiba a bevitt adatokban!');
        }
        return true;
    }

    public function manipulateDb()
    {
        if ($this->isValid() === false) throw new \Exception('Hiba a feldolgozás során!');
        $modfiedId = false;

        $id = $this->contentId;
        if (!empty($this->repository->find($id))) {
            $this->entity = $this->repository->find($id);
        }

        foreach ($this->form->getData()[$this->entityName] as $key => $postEntity) {
            $setFunct = 'set' . ucfirst($key);
            $this->entity->$setFunct($postEntity);
        }
        try {
            $this->em->persist($this->entity);
            $this->em->flush();
            $modfiedId = $this->entity->getId();

        } catch (\Exception $e) {
            throw new \Exception('Hiba a feldolgozás során!');
        }


        return $modfiedId;
    }

}