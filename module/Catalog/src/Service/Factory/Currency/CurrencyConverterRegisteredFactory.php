<?php
namespace Catalog\Service\Factory\Currency;

use Catalog\Service\Currency\CurrencyConverterRegistered;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class CurrencyConverterRegisteredFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return CurrencyConverterRegistered
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new CurrencyConverterRegistered($container);
    }
}