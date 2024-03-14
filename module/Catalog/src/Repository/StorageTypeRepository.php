<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.07.
 * Time: 12:46
 */

namespace Catalog\Repository;


use Catalog\Entity\Storage;
use Catalog\Entity\Stuff;
use Doctrine\ORM\EntityRepository;

class StorageTypeRepository extends EntityRepository
{

    public function getStuffesOfStorageTypeForBaseData($storageTypeId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('stuff')
            ->from(Stuff::class, 'stuff')
            ->leftJoin("stuff.stuffInStorageType", "ss")
            ->leftJoin("ss.storageType", "s")
            ->where('s.id = :storageTypeId')->setParameter("storageTypeId", $storageTypeId);

        return $qb->getQuery()->getResult();
    }





}