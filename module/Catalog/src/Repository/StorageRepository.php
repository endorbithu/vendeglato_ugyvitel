<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.07.
 * Time: 12:46
 */

namespace Catalog\Repository;


use Catalog\Entity\Storage;
use Doctrine\ORM\EntityRepository;

class StorageRepository extends EntityRepository
{

    //a stuff azt jelzi, hogy melyik stuffhoz tartozó stockcorrection típusok storagait tegye bealapból ingredientképes storage
    public function getStockCorrectionStorage($stuffStringId, $positiveOrNegative)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('s')
            ->from(Storage::class, 's')
            ->leftJoin("s.storageType", "st")
            ->where('st.stringId = :stringId')->setParameter("stringId", $stuffStringId . $positiveOrNegative);

        return $qb->getQuery()->getResult();
    }



    public function getRealStorage($stuff = [])
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('s')
            ->from(Storage::class, 's')
            ->leftJoin("s.storageType", "st")
            ->join('st.stuffInStorageType', 'sst')
            ->join('sst.stuff', 'stuff')
            ->where('st.stringId != :destination')->setParameter("destination", "destination")//destinationre ott van az order műveletek
            ->andWhere('st.isRealStorageType = :isRealStorageType')->setParameter("isRealStorageType", true);

        if (!empty($stuff)) {
            $qb->select('s')->andWhere('stuff.stringId in (:stuff)')->setParameter("stuff", $stuff);
        }

        return $qb->getQuery()->getResult();
    }

    public function getIsStuffInStorage($storageId, $stuffStringId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('stor.id,stuff.stringId')
            ->from(Storage::class, 'stor')
            ->join("stor.storageType", "st")
            ->join("st.stuffInStorageType", "sst")
            ->join("sst.stuff", "stuff")
            ->where('stor.id = :storageId')->setParameter("storageId", $storageId)
            ->andWhere('stuff.stringId = :stuffStringId')->setParameter("stuffStringId", $stuffStringId);

        if(empty($qb->getQuery()->getResult())) return false;

        return true;
    }



    public function getChildStorage($storageId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('s')
            ->from(Storage::class, 's')
            ->leftJoin("s.parentStorage", "ps")
            ->where('ps.id = :storageId')->setParameter("storageId", $storageId);

        return $qb->getQuery()->getResult();
    }

    public function getDestination()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('s')
            ->from(Storage::class, 's')
            ->leftJoin("s.storageType", "st")
            ->where('st.stringId = :destination')->setParameter("destination", "destination");

        return $qb->getQuery()->getResult();
    }

    public function getDestinationType($storeId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('s')
            ->from(Storage::class, 's')
            ->leftJoin("s.storageType", "st")
            ->where('s.id = :storeId')->setParameter("storeId", $storeId)
            ->andWhere('st.stringId = :destination')->setParameter("destination", "destination");

        return $qb->getQuery()->getResult();
    }

    public function getActualLocalStorage($userId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('s.id')
            ->from(Storage::class, 's')
            ->leftJoin("s.storageType", "st")
            ->where('st.stringId = :localstorage')->setParameter("localstorage", "localstorage");
        // ->andWhere(userhez kapcsoljuk a local storaget, pl a settingsben stb...
        return $qb->getQuery()->getResult();
    }


}