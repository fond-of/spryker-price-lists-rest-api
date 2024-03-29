<?php

namespace FondOfSpryker\Glue\PriceListsRestApi;

use FondOfSpryker\Glue\PriceListsRestApi\Dependency\Client\PriceListsRestApiToPriceListClientBridge;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

class PriceListsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_PRICE_LIST = 'CLIENT_PRICE_LIST';

    /**
     * @var string
     */
    public const PLUGINS_FILTER_FIELDS_EXPANDER = 'PLUGINS_FILTER_FIELDS_EXPANDER';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addPriceListClient($container);

        return $this->addFilterFieldsExpanderPlugins($container);
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addPriceListClient(Container $container): Container
    {
        $container[static::CLIENT_PRICE_LIST] = static function (Container $container) {
            return new PriceListsRestApiToPriceListClientBridge(
                $container->getLocator()->priceList()->client(),
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addFilterFieldsExpanderPlugins(Container $container): Container
    {
        $self = $this;

        $container[static::PLUGINS_FILTER_FIELDS_EXPANDER] = static function () use ($self) {
            return $self->getFilterFieldsExpanderPlugins();
        };

        return $container;
    }

    /**
     * @return array<\FondOfOryx\Glue\PriceListsRestApiExtension\Dependency\Plugin\FilterFieldsExpanderPluginInterface>
     */
    protected function getFilterFieldsExpanderPlugins(): array
    {
        return [];
    }
}
