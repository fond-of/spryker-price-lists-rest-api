<?php

namespace FondOfSpryker\Glue\PriceListsRestApi\Processor\PriceLists;

use FondOfSpryker\Glue\PriceListsRestApi\PriceListsRestApiConfig;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class PriceListsResourceRelationshipExpander implements PriceListsResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \FondOfSpryker\Glue\PriceListsRestApi\Processor\PriceLists\PriceListsMapperInterface
     */
    protected $priceListsMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \FondOfSpryker\Glue\PriceListsRestApi\Processor\PriceLists\PriceListsMapperInterface $priceListsMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        PriceListsMapperInterface $priceListsMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->priceListsMapper = $priceListsMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): array
    {
        foreach ($resources as $resource) {
            /**
             * @var \Generated\Shared\Transfer\CustomerTransfer|null $payload
             */
            $payload = $resource->getPayload();

            if ($payload === null || !($payload instanceof CustomerTransfer)) {
                continue;
            }

            $priceListCollectionTransfer = $payload->getPriceListCollection();

            if ($priceListCollectionTransfer === null) {
                continue;
            }

            foreach ($priceListCollectionTransfer->getPriceLists() as $priceListTransfer) {
                $restPriceListsAttributesTransfer = $this->priceListsMapper->mapRestPriceListsAttributesTransfer(
                    $priceListTransfer
                );

                $customerGroupsResource = $this->restResourceBuilder->createRestResource(
                    PriceListsRestApiConfig::RESOURCE_PRICE_LIST,
                    $priceListTransfer->getUuid(),
                    $restPriceListsAttributesTransfer
                );

                $resource->addRelationship($customerGroupsResource);
            }

            return $resources;
        }
    }
}
