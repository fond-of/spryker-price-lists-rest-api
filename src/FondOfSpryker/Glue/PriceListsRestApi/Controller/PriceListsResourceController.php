<?php

namespace FondOfSpryker\Glue\PriceListsRestApi\Controller;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \FondOfSpryker\Glue\PriceListsRestApi\PriceListsRestApiFactory getFactory()
 */
class PriceListResourceController extends AbstractController
{
    /***
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getAction(RestRequestInterface $restRequest): RestResponseInterface
    {
        if ($restRequest->getResource()->getId()) {
            return $this->getFactory()
                ->createPriceListReader()
                ->getPriceListByUuid($restRequest);
        }

        return $this->getFactory()
            ->createPriceListReader()
            ->getAllPriceLists($restRequest);
    }
}
