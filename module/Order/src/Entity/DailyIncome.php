<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.12.
 * Time: 22:57
 */


namespace Order\Entity;

use Application\Entity\InjectionOfEntity\ServiceMangerAwareEntity;
use Transaction\Entity\StockTransaction;
use Catalog\Entity\Product;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;


/**
 * @Entity(repositoryClass="Order\Repository\DailyIncomeRepository")
 * @Table(name="ord_daily_income")
 */
class DailyIncome
{

    /**
     * @Id
     * @GeneratedValue(strategy="AUTO")
     * @Column(type="integer")
     */
    private $id;


    /**
     * @Column(name="date_time", type="datetime")
     **/
    private $dateTime;


    /**
     * @Column(name="net_income", type="decimal", precision=8, scale=2)
     **/
    private $netIncome;


    /**
     * @Column(name="vat_amount", type="decimal", precision=8, scale=2)
     */
    private $vatAmount;


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
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * @param mixed $dateTime
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;
    }

    /**
     * @return mixed
     */
    public function getNetIncome()
    {
        return $this->netIncome;
    }

    /**
     * @param mixed $netIncome
     */
    public function setNetIncome($netIncome)
    {
        $this->netIncome = $netIncome;
    }

    /**
     * @return mixed
     */
    public function getVatAmount()
    {
        return $this->vatAmount;
    }

    /**
     * @param mixed $vatAmount
     */
    public function setVatAmount($vatAmount)
    {
        $this->vatAmount = $vatAmount;
    }


    public function getArrayCopy() //Doctrinehoz kell
    {
        $arrayCopy = [];
        $arrayCopy['IngredientMoving'] = get_object_vars($this);
        if (array_key_exists('sm', $arrayCopy['IngredientMoving'])) {
            unset($arrayCopy['IngredientMoving']['sm']);
        }
        return $arrayCopy;
    }


}