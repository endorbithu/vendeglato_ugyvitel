<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.04.
 * Time: 9:57
 */

namespace Catalog\Service\BaseDataEdit;

use Catalog\Entity\Stuff;
use Catalog\Entity\StuffInStorageType;

/**
 * A Product táblához a műveletet megöröklöm így nem kell vele bíbelődni
 * Class ProductDataEditService
 * @package Catalog\Service\BaseDataEdit
 */
class StorageTypeDataEditService extends BaseDataEditService
{

    private $unusualRows = []; //azok az stuffok amiket kivettek a user inputban, de a db-ben még szerepel
    private $noStuff;
    protected $sm;

    public function __construct($em)
    {
        parent::__construct($em);

    }

    public function setSm($sm)
    {
        $this->sm = $sm;
    }

    public function setPostData(array $postData)
    {
        $this->postData = $postData;
        if (!array_key_exists('stuffInStorageType', $this->postData)) $this->noStuff = true;
    }

    /**
     * @return array
     */
    public function getAllStuff()
    {
        $allStuff = $this->em->getRepository(Stuff::class)->findAll();
        $staffIdName = [];
        //datatable számára elkészítjük a  oszlopokat
        foreach ($allStuff as $stuff) {
            $staffIdName[] = [$stuff->getId(), $stuff->getName()];
        }

        return $staffIdName;

    }

    /**
     * @return mixed
     */
    public function getContainingStuffId()
    {
        $selectedStuff = [];
        $stuffOfStorageType = $this->em->getRepository(StuffInStorageType::class)->findBy(array('storageType' => $this->contentId));
        foreach ($stuffOfStorageType as $stuff) {
            $selectedStuff[] = $stuff->getStuff()->getId();
        }

        return $selectedStuff;
    }


    public function isValidStuff()
    {
        if ($this->noStuff) return true;

        foreach ($this->postData['stuffInStorageType'] as $val) {
            $val = (int)$val;
            if (!($val > 0)) throw new \Exception('Hiba a bevitt adatokban!');

            $this->postData['stuffInStorageType'][] = $val;
        }


        return true;
    }


    public function insertStuffInStorageType()
    {

        if ($this->isValid !== true) return false;

        $stuffInStorageTypeRepo = $this->em->getRepository(StuffInStorageType::class);

        //a végén, amelyik sort nem érintette a dolog kitöröljük
        foreach ($stuffInStorageTypeRepo->findBy(['storageType' => $this->contentId]) as $val) {
            $this->unusualRows[$val->getId()] = $val;
        }

        if (array_key_exists('stuffInStorageType', $this->postData)) {
            //feldolgozzuk a userinputot
            foreach ($this->postData['stuffInStorageType'] as $stuffId) {

                //ha volt már ilyen sor a db-ben
                if (!empty($stuffInStorageTypeRepo->findBy(['storageType' => $this->contentId, 'stuff' => $stuffId]))) {
                    $stuffInStorageTypeEntity = $stuffInStorageTypeRepo->findBy(['storageType' => $this->contentId, 'stuff' => $stuffId])[0];
                    unset($this->unusualRows[$stuffInStorageTypeEntity->getId()]);

                } else {
                    $stuffInStorageTypeEntity = new StuffInStorageType();
                    $stuffInStorageTypeEntity->setServiceManager($this->sm);
                    $stuffInStorageTypeEntity->setStorageType($this->contentId);
                    $stuffInStorageTypeEntity->setStuff($stuffId);
                }

                try {
                    $this->em->persist($stuffInStorageTypeEntity);
                    $this->em->flush();
                } catch (\Exception $e) {
                    throw new \Exception('Hiba a feldolgozás során!');
                }
            }
        }
        $this->deleteUnusualRows();


    }

    protected function deleteUnusualRows()
    {
        //amelyik id nem volt érintve azt töröljük
        foreach ($this->unusualRows as $val) {
            $this->em->remove($val);
            $this->em->flush();
        }
        return true;

    }


}