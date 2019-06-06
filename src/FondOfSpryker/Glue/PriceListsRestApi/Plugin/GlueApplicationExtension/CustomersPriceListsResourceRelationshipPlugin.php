<?php

namespace FondOfSpryker\Glue\PriceListsRestApi\Plugin\GlueApplicationExtension;

use FondOfSpryker\Glue\PriceListsRestApi\PriceListsRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Glue\PriceListsRestApi\PriceListsRestApiFactory getFactory()
 */
class CustomersPriceListsResourceRelationshipPlugin extends AbstractPlugin implements ResourceRelationshipPluginInterface
{
    /**
     * @api
     *
     * {@inheritdoc}
     *
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        $this->getFactory()
            ->createPriceListsResourceRelationshipExpander()
            ->addResourceRelationships($resources, $restRequest);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     *
     * @return string
     */
    public function getRelationshipResourceType(): string
    {
        return PriceListsRestApiConfig::RESOURCE_PRICE_LISTS;
    }
}
