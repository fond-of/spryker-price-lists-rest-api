<?php

namespace FondOfSpryker\Glue\PriceListsRestApi\Processor\PriceList;

use ArrayObject;
use FondOfSpryker\Glue\PriceListsRestApi\Dependency\Client\PriceListsRestApiToPriceListClient;
use FondOfSpryker\Glue\PriceListsRestApi\PriceListsRestApiConfig;
use FondOfSpryker\Glue\PriceListsRestApi\Processor\Validation\RestApiErrorInterface;
use Generated\Shared\Transfer\PriceListCollectionTransfer;
use Generated\Shared\Transfer\PriceListListTransfer;
use Generated\Shared\Transfer\PriceListTransfer;
use Generated\Shared\Transfer\RestPriceListAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class PriceListReader implements PriceListReaderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \FondOfSpryker\Glue\PriceListsRestApi\Processor\Validation\RestApiErrorInterface
     */
    protected $restApiError;

    /**
     * @var \FondOfSpryker\Glue\PriceListsRestApi\Processor\PriceList\PriceListMapperInterface
     */
    protected $priceListMapper;

    /**
     * @var \FondOfSpryker\Glue\PriceListsRestApi\Dependency\Client\PriceListsRestApiToPriceListClient
     */
    protected $priceListClient;

    /**
     * @var array<\FondOfOryx\Glue\PriceListsRestApiExtension\Dependency\Plugin\FilterFieldsExpanderPluginInterface>
     */
    protected $filterFieldsExpanderPlugins;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \FondOfSpryker\Glue\PriceListsRestApi\Processor\Validation\RestApiErrorInterface $restApiError
     * @param \FondOfSpryker\Glue\PriceListsRestApi\Dependency\Client\PriceListsRestApiToPriceListClient $priceListClient
     * @param \FondOfSpryker\Glue\PriceListsRestApi\Processor\PriceList\PriceListMapperInterface $priceListMapper
     * @param array<\FondOfOryx\Glue\PriceListsRestApiExtension\Dependency\Plugin\FilterFieldsExpanderPluginInterface> $filterFieldsExpanderPlugins
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        RestApiErrorInterface $restApiError,
        PriceListsRestApiToPriceListClient $priceListClient,
        PriceListMapperInterface $priceListMapper,
        array $filterFieldsExpanderPlugins
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->restApiError = $restApiError;
        $this->priceListClient = $priceListClient;
        $this->priceListMapper = $priceListMapper;
        $this->filterFieldsExpanderPlugins = $filterFieldsExpanderPlugins;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getAllPriceLists(RestRequestInterface $restRequest): RestResponseInterface
    {
        $filterFieldTransfers = new ArrayObject();

        foreach ($this->filterFieldsExpanderPlugins as $filterFieldsExpanderPlugin) {
            $filterFieldTransfers = $filterFieldsExpanderPlugin->expand($restRequest, $filterFieldTransfers);
        }

        $priceListListTransfer = (new PriceListListTransfer())
            ->setFilterFields($filterFieldTransfers);

        $priceListListTransfer = $this->priceListClient->findPriceLists($priceListListTransfer);

        $priceListCollectionTransfer = (new PriceListCollectionTransfer())
            ->setPriceLists($priceListListTransfer->getPriceLists());

        return $this->addPriceListCollectionTransferToResponse(
            $priceListCollectionTransfer,
            $this->restResourceBuilder->createRestResponse()
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getPriceListByUuid(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        if ($restRequest->getResource()->getId() === null) {
            return $this->restApiError->addPriceListIdMissingError($restResponse);
        }

        $filterFieldTransfers = new ArrayObject();

        foreach ($this->filterFieldsExpanderPlugins as $filterFieldsExpanderPlugin) {
            $filterFieldTransfers = $filterFieldsExpanderPlugin->expand($restRequest, $filterFieldTransfers);
        }

        $priceListListTransfer = (new PriceListListTransfer())
            ->setFilterFields($filterFieldTransfers);

        $priceListListTransfer = $this->priceListClient->findPriceLists($priceListListTransfer);

        if ($priceListListTransfer->getPriceLists()->count() !== 1) {
            return $this->restApiError->addPriceListNotFoundError($restResponse);
        }

        return $this->addPriceListTransferToResponse(
            $priceListListTransfer->getPriceLists()->offsetGet(0),
            $restResponse
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PriceListCollectionTransfer $priceListCollectionTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function addPriceListCollectionTransferToResponse(
        PriceListCollectionTransfer $priceListCollectionTransfer,
        RestResponseInterface $restResponse
    ): RestResponseInterface {
        foreach ($priceListCollectionTransfer->getPriceLists() as $priceListTransfer) {
            $this->addPriceListTransferToResponse($priceListTransfer, $restResponse);
        }

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceListTransfer $priceListTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function addPriceListTransferToResponse(
        PriceListTransfer $priceListTransfer,
        RestResponseInterface $restResponse
    ): RestResponseInterface {
        $restPriceListAttributesTransfer = $this->priceListMapper->mapPriceListTransferToRestPriceListAttributesTransfer(
            $priceListTransfer,
            new RestPriceListAttributesTransfer()
        );

        $restResource = $this->restResourceBuilder->createRestResource(
            PriceListsRestApiConfig::RESOURCE_PRICE_LISTS,
            $priceListTransfer->getUuid(),
            $restPriceListAttributesTransfer
        );

        return $restResponse->addResource($restResource);
    }
}
