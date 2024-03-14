<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.07.
 * Time: 12:46
 */

namespace Catalog\Repository;


use Catalog\Entity\Ingredient;
use Catalog\Entity\IngredientInProduct;
use Catalog\Entity\Product;
use Transaction\Entity\Stock;
use Catalog\Entity\Storage;
use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{


    public function getIngredientsOfProduct($productId)
    {
        $result = [];
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('i.id')
            ->from(IngredientInProduct::class, 'ip')
            ->leftJoin("ip.product", "p")
            ->leftJoin("ip.ingredient", "i")
            ->where('p.id = :productId')->setParameter("productId", $productId);

        foreach ($qb->getQuery()->getResult() as $productIds) {
            $result[] = $productIds['id'];
        }
        return $result;
    }

    public function getIngredientsAmountOfProduct($productAndAmount)
    {
        //SELECT ingredient_id, amount FROM `catalog_ingredient_product` WHERE product_id = 8
        foreach ($productAndAmount as $key => $value) {
            $productId = $key;
            $amount = $value;
        }

        $result = [];
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('i.id, ip.amount')
            ->from(IngredientInProduct::class, 'ip')
            ->leftJoin("ip.product", "p")
            ->leftJoin("ip.ingredient", "i")
            ->where('p.id = :productId')->setParameter("productId", $productId);

        foreach ($qb->getQuery()->getResult() as $ipRecord) {
            $result[$ipRecord['id']] = ($amount * $ipRecord['amount']);
        }

        return $result;
    }


    public function getIngredientsOfProductForBaseData($productId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('i.id as ingredientId,i.name as ingredientName,u.shortName as ingredientUnitShortName, ip.amount as ingredientProductAmount')
            ->from(Ingredient::class, 'i')
            ->leftJoin("i.ingredientInProduct", "ip")
            ->leftJoin("i.ingredientUnit", "u")
            ->leftJoin("ip.product", "p")
            ->where('p.id = :productId')->setParameter("productId", $productId);

        return $qb->getQuery()->getResult();
    }


    //TODO: #148 sebesség szempontjából megvizsgálni!!
    // az alapanya szempontjából vizsgálja meg az elérhető termékeket, tehát, hogy az adott storage-ban mely termékekre elegendő ingr van
    public function getAvailableProducts($storageId)
    {

        //ha vannak gyermek storage-i, akkor összesíti őket, ha nincs akkor meg a sajátját
        if (empty($this->getEntityManager()->getRepository(Storage::class)->getChildStorage($storageId))) {

            $qb = $this->getEntityManager()->createQueryBuilder();
            $qb->select('p')
                ->from(Product::class, 'p')
                ->where('p.isActive = :productAvailable')->setParameter("productAvailable", true);

            $products = $qb->getQuery()->getResult();

            //TODO: #149 lehetne optimalizálni talán
            $availableProducts = [];
            foreach ($products as $key => $product) {
                foreach ($product->getIngredientInProduct() as $ingProd) {
                    if (!((empty($this->getEntityManager()->getRepository(Stock::class)->findBy(['storage' => (int)$storageId, 'item' => $ingProd->getIngredient()->getId()])
                        ))
                        || !($this->getEntityManager()->getRepository(Stock::class)->findBy(['storage' => (int)$storageId, 'item' => $ingProd->getIngredient()->getId()])[0]->getAmount() >= $ingProd->getAmount())
                    )
                    ) {

                        $availableProducts[$key] = $products[$key];
                    } else {

                        unset($availableProducts[$key]);
                        continue 2;
                    }
                    //TODO: #150 ötlet, ki lehet számolni, hogy kb mennyi adag fér még ki és a rendelésnél a inputnak maxba beadni
                }

            }
            return $availableProducts;

        } else {
            $avalableProducts = [];
            foreach ($this->getEntityManager()->getRepository(Storage::class)->getChildStorage($storageId) as $storage) {
                $avalableProducts = array_merge($avalableProducts, $this->getAvailableProducts($storage->getId()));
            }

            return $avalableProducts;

        }
    }


    //TODO: #151 valahogy összevonni a a fenti fg-nyel
    //ha visszavételezünk pl, akkor nem baj ha nincsen készleten a lényeg, hogy melyik storagehez tartozik
    public function getProductsOfStorage($storageId)
    {

        //ha vannak gyermek storage-i, akkor összesíti őket, ha nincs akkor meg a sajátját
        if (empty($this->getEntityManager()->getRepository(Storage::class)->getChildStorage($storageId))) {
            $qb = $this->getEntityManager()->createQueryBuilder();
            $qb->select('p')
                ->from(Product::class, 'p')
                ->where('p.isActive = :productAvailable')->setParameter("productAvailable", true);

            $products = $qb->getQuery()->getResult();

            //TODO: #152 lehetne optimalizálni talán
            $productsOfStorage = [];
            foreach ($products as $key => $product) {
                foreach ($product->getIngredientInProduct() as $ingProd) {
                    if (!((empty($this->getEntityManager()->getRepository(Stock::class)->findBy(['storage' => (int)$storageId, 'item' => $ingProd->getIngredient()->getId()])
                    ))
                    )
                    ) {
                        $productsOfStorage[$key] = $products[$key];
                    } else {
                        unset($productsOfStorage[$key]);
                        continue 2;
                    }
                    //TODO: #150 ötlet, ki lehet számolni, hogy kb mennyi adag fér még ki és a rendelésnél a inputnak maxba beadni
                }

            }
            return $productsOfStorage;

        } else {
            $productsOfStorage = [];
            foreach ($this->getEntityManager()->getRepository(Storage::class)->getChildStorage($storageId) as $storage) {
                $productsOfStorage = array_merge($productsOfStorage, $this->getProductsOfStorage($storage->getId()));
            }

            return $productsOfStorage;

        }
    }

    public function getProductsAmountOfOrderItem($orderItemId)
    {

    }


}