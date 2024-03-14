<?php
/**
 * Created by PhpStorm.
 * User: stellar
 * Date: 2016.09.19.
 * Time: 12:41
 */

namespace Catalog\View\Helper;


use Zend\View\Helper\AbstractHelper;

class CurrencyHelper extends AbstractHelper
{
    private $selectedCurrency;

    public function __construct($serviceManager)
    {
        $selected = $serviceManager->get('currencyConverter')->getSelectedCurrency();
        $this->selectedCurrency = $serviceManager->get('config')['currency']['currency'][$selected];
    }

    public function __invoke($amount)
    {
        $decimals = !empty($this->selectedCurrency['decimal']) ? 2 : 0;
        $amount = number_format($amount, $decimals, ".", $this->selectedCurrency['thousand']);
        return $this->selectedCurrency['prefix'] ? $this->selectedCurrency['sign'] . $amount : $amount . $this->selectedCurrency['sign'];
    }

}