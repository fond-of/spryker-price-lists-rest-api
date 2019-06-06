<?php

namespace FondOfSpryker\Glue\PriceListsRestApi;

use FondOfSpryker\Glue\PriceListsRestApi\Processor\PriceLists\PriceListsMapper;
use FondOfSpryker\Glue\PriceListsRestApi\Processor\PriceLists\PriceListsMapperInterface;
use FondOfSpryker\Glue\PriceListsRestApi\Processor\PriceLists\PriceListsResourceRelationshipExpander;
use FondOfSpryker\Glue\PriceListsRestApi\Processor\PriceLists\PriceListsResourceRelationshipExpanderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class PriceListsRestApiFactory extends AbstractFactory
{
    /**
     * @return \FondOfSpryker\Glue\PriceListsRestApi\Processor\PriceLists\PriceListsResourceRelationshipExpanderInterface
     */
    public function createPriceListsResourceRelationshipExpander(): PriceListsResourceRelationshipExpanderInterface
    {
        return new PriceListsResourceRelationshipExpander(
            $this->getResourceBuilder(),
            $this->createPriceListsMapper()
        );
    }

    /**
     * @return \FondOfSpryker\Glue\PriceListsRestApi\Processor\PriceLists\PriceListsMapperInterface
     */
    protected function createPriceListsMapper(): PriceListsMapperInterface
    {
        return new PriceListsMapper();
    }
}
