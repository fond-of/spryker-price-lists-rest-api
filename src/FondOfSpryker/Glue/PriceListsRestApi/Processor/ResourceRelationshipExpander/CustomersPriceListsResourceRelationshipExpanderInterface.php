<?php

namespace FondOfSpryker\Glue\PriceListsRestApi\Processor\ResourceRelationshipExpander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface CustomersPriceListsResourceRelationshipExpanderInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): array;
}
