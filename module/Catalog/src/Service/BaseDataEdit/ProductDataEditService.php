<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.10.04.
 * Time: 9:57
 */

namespace Catalog\Service\BaseDataEdit;

use Application\Filter\NumericToFloat;
use Catalog\Entity\Ingredient;
use Catalog\Entity\IngredientInProduct;
use Zend\Filter\FilterChain;
use Zend\Filter\StripTags;

/**
 * A Product táblához a műveletet megöröklöm így nem kell vele bíbelődni
 * Class ProductDataEditService
 * @package Catalog\Service\BaseDataEdit
 */
class ProductDataEditService extends BaseDataEditService
{

    private $unusualRows = []; //azok az alapanyagok amiket kivettek a user inputban, de a db-ben még szerepel
    private $noIngredient;
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
        if ((!array_key_exists('ingredientAmount', $this->postData))
            || (!array_key_exists('productContainIngredient', $this->postData))
        ) $this->noIngredient = true;
    }

    /**
     * @return array
     */
    public function getAllIngredient()
    {
        $amountOfIngredients = [];

        //ha nem 0 akkor meglévő adat van, ha 0 akkor új elemet töltünk fel
        if ($this->contentId != 0) $amountOfIngredients = $this->getAmountOfIngredient();

        $allIingredients = $this->em->getRepository(Ingredient::class)->findAll();
        $ingredientIdNameAmounts = [];
        //datatable számára elkészítjük a  oszlopokat
        foreach ($allIingredients as $ingredient) {
            //ha van ilyen ingr akkor a db adat a mennyiség, ha nincs akkor 0
            $amount = (array_key_exists($ingredient->getId(), $amountOfIngredients)) ? $amountOfIngredients[$ingredient->getId()] : '0';
            $ingredientIdNameAmounts[] = [$ingredient->getId(), $ingredient->getName(), $amount, $ingredient->getIngredientUnit()->getName()];
        }

        return $ingredientIdNameAmounts;

    }

    /**
     * Ez egy controllművelethez kell a JS miatt: két helyen is megnézzük, hogy melyik id-jű ingredientek az érintettek, itt csak az Id-t
     * @return mixed
     */
    public function getContainingIngredientId()
    {
        $selectedIngredients = [];
        $ingredientsOfProduct = $this->em->getRepository(IngredientInProduct::class)->findBy(array('product' => $this->contentId));
        foreach ($ingredientsOfProduct as $ingredientOfProduct) {
            $selectedIngredients[] = $ingredientOfProduct->getIngredient()->getId();
        }

        return $selectedIngredients;
    }


    /**
     * @return array
     */
    protected function getAmountOfIngredient()
    {
        $ingredientsOfProductAmounts = [];
        //0 az azt jelenti, hogy új elem
        if ($this->contentId == 0) return $ingredientsOfProductAmounts;

        $ingredientsOfProduct = $this->em->getRepository(IngredientInProduct::class)->findBy(array('product' => $this->contentId));
        foreach ($ingredientsOfProduct as $ingredientOfProduct) {
            $ingredientsOfProductAmounts[$ingredientOfProduct->getIngredient()->getId()] = $ingredientOfProduct->getAmount();
        }
        return $ingredientsOfProductAmounts;
    }


    public function isValidIngredient()
    {
        if ($this->noIngredient) return true;

        //Átszűrjük a két tömböt, (kulcscsal együtt) azért van kettő, hogy js hiba itt kiderüljön, egyszerre két helyen kisebb eséllyel baxa el
        $filtersForFloat = new FilterChain();
        $filtersForFloat
            ->attach(new StripTags())
            ->attach(new NumericToFloat());


        foreach ($this->postData['ingredientAmount'] as $key => $val) {
            $key = (int)$key;
            $val = $filtersForFloat->filter($val);
            $this->postData['ingredientAmount'][$key] = $val;
            if ($val == 0) unset($this->postData['ingredientAmount'][$key]);

        }

        foreach ($this->postData['productContainIngredient'] as $key => $val) {
            $key = (int)$key;
            $val = (int)$val;
            $this->postData['productContainIngredient'][$key] = $val;

        }
        //szűrés vége
        foreach ($this->postData['ingredientAmount'] as $key => $val) {
            if (!in_array($key, $this->postData['productContainIngredient'])) throw new \Exception('Hiba a bevitt adatokban!');

        }

        foreach ($this->postData['productContainIngredient'] as $key => $val) {
            //ha nem stimmel az alapanyagok id-je a két tömbnél az már baj
            if (!array_key_exists($val, $this->postData['ingredientAmount'])) throw new \Exception('Hiba a bevitt adatokban!');

        }

        return true;
    }


    public function insertIngredientProduct()
    {
        if (empty($this->isValid)) return false;

        $ingreProdRepo = $this->em->getRepository(IngredientInProduct::class);

        //amelyik sort nem érintette a dolog kitöröljük később a foreachban ki tudjuk venni a tömbből
        foreach ($ingreProdRepo->findBy(['product' => $this->contentId]) as $val) {
            $this->unusualRows[$val->getId()] = $val;
        }


        //feldolgozzuk a userinputot

        if (array_key_exists('ingredientAmount', $this->postData)) {
            foreach ($this->postData['ingredientAmount'] as $ingredient => $amount) {
                if ($amount == 0) continue;

                //ha volt már ilyen sor a db-ben
                if (!empty($ingreProdRepo->findBy(['product' => $this->contentId, 'ingredient' => $ingredient]))) {
                    $ingredientProductEntity = $ingreProdRepo->findBy(['product' => $this->contentId, 'ingredient' => $ingredient])[0];
                    unset($this->unusualRows[$ingredientProductEntity->getId()]);

                } else {
                    $ingredientProductEntity = new IngredientInProduct();
                    $ingredientProductEntity->setServiceManager($this->sm);
                    $ingredientProductEntity->setProduct($this->contentId);
                    $ingredientProductEntity->setIngredient($ingredient);
                }

                try {
                    $ingredientProductEntity->setAmount($amount);
                    $this->em->persist($ingredientProductEntity);
                    $this->em->flush();
                } catch (\Exception $e) {
                    throw new \Exception('Hiba a feldolgozás során!');
                }

            }
        }
        $this->deleteUnusualRows();

    }

    protected
    function deleteUnusualRows()
    {
        //amelyik id nem volt érintve azt töröljük
        foreach ($this->unusualRows as $val) {
            $this->em->remove($val);
            $this->em->flush();
        }
        return true;

    }


}