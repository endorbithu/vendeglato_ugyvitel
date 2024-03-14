<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.04.
 * Time: 9:57
 */

namespace Application\Service\Crud;

/**
 * Class DeleteEntityItems
 * @package Catalog\Service\BaseDataEdit
 */
class DeleteEntityItems
{
    protected $repository;
    protected $entityName;
    protected $postData;
    protected $em;
    protected $deleteElements;
    protected $cantDeleteElements = [];
    protected $successDeleteElements = [];
    protected $actualUserId;


    public function allElementsExist()
    {
        $this->deleteElements = [];
        foreach ($this->postData[$this->entityName] as $key => $selectedContent) {
            if (strlen($selectedContent) > 16) return;
            $id = (int)(strip_tags($selectedContent));
            if (empty($this->deleteElements[$key] = $this->repository->find($id))) return false;
        }
        return true;
    }


    public function deleteFromDb()
    {
        foreach ($this->deleteElements as $deleteElement) {
            try {
                $deleteElement = $this->em->merge($deleteElement);
                $deleteElementId = $deleteElement->getId();
                $deleteElementName = $deleteElement->getName();
                $this->em->remove($deleteElement);
                $this->em->flush();
                $this->successDeleteElements[] = $deleteElementName . '(id:' . $deleteElementId . ') ';
            } catch (\Exception $e) {
                $this->cantDeleteElements[] = $deleteElement->getName() . '(id:' . $deleteElement->getId() . ') ';
                if (!$this->em->isOpen()) {
                    $this->em = $this->em->create(
                        $this->em->getConnection(),
                        $this->em->getConfiguration()
                    );
                }
            }
        }
        return true;
    }





    /**
     * @param mixed $actualUserId
     */
    public function setActualUserId($actualUserId)
    {
        $this->actualUserId = $actualUserId;
    }


    /**
     * @return array
     */
    public function getCantDeleteElements()
    {
        return $this->cantDeleteElements;
    }

    /**
     * @return array
     */
    public function getSuccessDeleteElements()
    {
        return $this->successDeleteElements;
    }

    /**
     * @param mixed $em
     */
    public function setEm($em)
    {
        $this->em = $em;
    }

    /**
     * @param mixed $postData
     */
    public function setPostData($postData)
    {
        $this->postData = $postData;
    }


    /**
     * @param mixed $repository
     */
    public function setRepository($repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param mixed $entityName
     */
    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;
    }
}