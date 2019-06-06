<?php

namespace FondOfSpryker\Glue\PriceListsRestApi;

use FondOfSpryker\Glue\PriceListsRestApi\Processor\Mapper\PriceListMapper;
use FondOfSpryker\Glue\PriceListsRestApi\Processor\Mapper\PriceListMapperInterface;
use FondOfSpryker\Glue\PriceListsRestApi\Processor\ResourceRelationshipExpander\CustomersPriceListsResourceRelationshipExpander;
use FondOfSpryker\Glue\PriceListsRestApi\Processor\ResourceRelationshipExpander\CustomersPriceListsResourceRelationshipExpanderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class PriceListsRestApiFactory extends AbstractFactory
{
    /**
     * @return \FondOfSpryker\Glue\PriceListsRestApi\Processor\ResourceRelationshipExpander\CustomersPriceListsResourceRelationshipExpanderInterface
     */
    public function createPriceListsResourceRelationshipExpander(): CustomersPriceListsResourceRelationshipExpanderInterface
    {
        return new CustomersPriceListsResourceRelationshipExpander(
            $this->getResourceBuilder(),
            $this->createPriceListMapper()
        );
    }

    /**
     * @return \FondOfSpryker\Glue\PriceListsRestApi\Processor\Mapper\PriceListMapperInterface
     */
    protected function createPriceListMapper(): PriceListMapperInterface
    {
        return new PriceListMapper();
    }
}
