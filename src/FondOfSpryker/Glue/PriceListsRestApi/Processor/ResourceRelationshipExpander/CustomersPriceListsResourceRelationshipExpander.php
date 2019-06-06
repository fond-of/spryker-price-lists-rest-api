<?php

namespace FondOfSpryker\Glue\PriceListsRestApi\Processor\ResourceRelationshipExpander;

use FondOfSpryker\Glue\PriceListsRestApi\PriceListsRestApiConfig;
use FondOfSpryker\Glue\PriceListsRestApi\Processor\Mapper\PriceListMapperInterface;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestPriceListAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CustomersPriceListsResourceRelationshipExpander implements CustomersPriceListsResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \FondOfSpryker\Glue\PriceListsRestApi\Processor\Mapper\PriceListMapperInterface
     */
    protected $priceListsMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \FondOfSpryker\Glue\PriceListsRestApi\Processor\Mapper\PriceListMapperInterface $priceListsMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        PriceListMapperInterface $priceListsMapper
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
                $restPriceListAttributesTransfer = new RestPriceListAttributesTransfer();

                $restPriceListsAttributesTransfer = $this->priceListsMapper->mapPriceListTransferToRestPriceListAttributesTransfer(
                    $priceListTransfer,
                    $restPriceListAttributesTransfer
                );

                $customerGroupsResource = $this->restResourceBuilder->createRestResource(
                    PriceListsRestApiConfig::RESOURCE_PRICE_LISTS,
                    $priceListTransfer->getUuid(),
                    $restPriceListsAttributesTransfer
                );

                $resource->addRelationship($customerGroupsResource);
            }
        }

        return $resources;
    }
}
