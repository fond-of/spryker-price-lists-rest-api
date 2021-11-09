<?php

namespace FondOfSpryker\Glue\PriceListsRestApi;

use FondOfSpryker\Glue\PriceListsRestApi\Dependency\Client\PriceListsRestApiToPriceListClientInterface;
use FondOfSpryker\Glue\PriceListsRestApi\Processor\PriceList\PriceListMapper;
use FondOfSpryker\Glue\PriceListsRestApi\Processor\PriceList\PriceListMapperInterface;
use FondOfSpryker\Glue\PriceListsRestApi\Processor\PriceList\PriceListReader;
use FondOfSpryker\Glue\PriceListsRestApi\Processor\PriceList\PriceListReaderInterface;
use FondOfSpryker\Glue\PriceListsRestApi\Processor\Validation\RestApiError;
use FondOfSpryker\Glue\PriceListsRestApi\Processor\Validation\RestApiErrorInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class PriceListsRestApiFactory extends AbstractFactory
{
    /**
     * @return \FondOfSpryker\Glue\PriceListsRestApi\Processor\PriceList\PriceListReaderInterface
     */
    public function createPriceListReader(): PriceListReaderInterface
    {
        return new PriceListReader(
            $this->getResourceBuilder(),
            $this->createRestApiError(),
            $this->getPriceListClient(),
            $this->createPriceListMapper(),
            $this->getFilterFieldsExpanderPlugins()
        );
    }

    /**
     * @return \FondOfSpryker\Glue\PriceListsRestApi\Processor\PriceList\PriceListMapperInterface
     */
    protected function createPriceListMapper(): PriceListMapperInterface
    {
        return new PriceListMapper();
    }

    /**
     * @return \FondOfSpryker\Glue\PriceListsRestApi\Processor\Validation\RestApiErrorInterface
     */
    protected function createRestApiError(): RestApiErrorInterface
    {
        return new RestApiError();
    }

    /**
     * @return \FondOfSpryker\Glue\PriceListsRestApi\Dependency\Client\PriceListsRestApiToPriceListClientInterface
     */
    protected function getPriceListClient(): PriceListsRestApiToPriceListClientInterface
    {
        return $this->getProvidedDependency(PriceListsRestApiDependencyProvider::CLIENT_PRICE_LIST);
    }

    /**
     * @return array<\FondOfOryx\Glue\PriceListsRestApiExtension\Dependency\Plugin\FilterFieldsExpanderPluginInterface>
     */
    protected function getFilterFieldsExpanderPlugins(): array
    {
        return $this->getProvidedDependency(PriceListsRestApiDependencyProvider::PLUGINS_FILTER_FIELDS_EXPANDER);
    }
}
