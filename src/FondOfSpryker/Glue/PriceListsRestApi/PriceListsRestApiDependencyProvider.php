<?php

namespace FondOfSpryker\Glue\PriceListsRestApi;

use FondOfSpryker\Glue\PriceListsRestApi\Dependency\Client\PriceListsRestApiToCustomerPriceListClientBridge;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

class PriceListsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CUSTOMER_PRICE_LIST = 'CLIENT_PRICE_LIST';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addCustomerPriceListClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCustomerPriceListClient(Container $container): Container
    {
        $container[static::CLIENT_CUSTOMER_PRICE_LIST] = static function (Container $container) {
            return new PriceListsRestApiToCustomerPriceListClientBridge(
                $container->getLocator()->customerPriceList()->client()
            );
        };

        return $container;
    }
}
