<?php
namespace Catalog\View\Factory\Helper;

use Catalog\Service\Currency\CurrencyConverterRegistered;
use Catalog\View\Helper\CurrencyHelper;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class CurrencyHelperFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return CurrencyConverterRegistered
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new CurrencyHelper($container);
    }
}