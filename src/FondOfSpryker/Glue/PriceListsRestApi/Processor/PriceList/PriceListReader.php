<?php

namespace FondOfSpryker\Glue\PriceListsRestApi\Processor\PriceList;

use FondOfSpryker\Glue\PriceListsRestApi\Dependency\Client\PriceListsRestApiToCustomerPriceClientInterface;
use FondOfSpryker\Glue\PriceListsRestApi\PriceListsRestApiConfig;
use FondOfSpryker\Glue\PriceListsRestApi\Processor\Validation\RestApiErrorInterface;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PriceListCollectionTransfer;
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
     * @var \FondOfSpryker\Glue\PriceListsRestApi\Processor\PriceList\PriceListMapper
     */
    protected $priceListMapper;

    /**
     * @var \FondOfSpryker\Client\CustomerPriceList\CustomerPriceListClientInterface
     */
    protected $customerPriceListClient;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \FondOfSpryker\Glue\PriceListsRestApi\Processor\Validation\RestApiErrorInterface $restApiError
     * @param \FondOfSpryker\Glue\PriceListsRestApi\Dependency\Client\PriceListsRestApiToCustomerPriceClientInterface $customerPriceListClient
     * @param \FondOfSpryker\Glue\PriceListsRestApi\Processor\PriceList\PriceListMapperInterface $priceListMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        RestApiErrorInterface $restApiError,
        PriceListsRestApiToCustomerPriceClientInterface $customerPriceListClient,
        PriceListMapperInterface $priceListMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->restApiError = $restApiError;
        $this->customerPriceListClient = $customerPriceListClient;
        $this->priceListMapper = $priceListMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getAllPriceLists(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $customerTransfer = (new CustomerTransfer())
            ->setIdCustomer($restRequest->getRestUser()->getSurrogateIdentifier());

        $priceListCollectionTransfer = $this->customerPriceListClient->getPriceListCollectionByIdCustomer($customerTransfer);

        if ($priceListCollectionTransfer->getPriceLists()->count() === 0) {
            return $this->restApiError->addPriceListNotFoundError($restResponse);
        }

        return $this->addPriceListCollectionTransferToResponse($priceListCollectionTransfer, $restResponse);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getPriceListByUuid(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        if (!$restRequest->getResource()->getId()) {
            return $this->restApiError->addPriceListIdMissingError($restResponse);
        }

        $customerTransfer = (new CustomerTransfer())
            ->setIdCustomer($restRequest->getRestUser()->getSurrogateIdentifier());

        $priceListCollectionTransfer = $this->customerPriceListClient->getPriceListCollectionByIdCustomer($customerTransfer);

        $priceListTransfer = $this->getPriceListByPriceListCollection($priceListCollectionTransfer, $restRequest->getResource()->getId());

        if (!$priceListTransfer && $priceListCollectionTransfer->getPriceLists()->count() === 0) {
            return $this->restApiError->addPriceListNoPermission($restResponse);
        }

        if (!$priceListTransfer) {
            return $this->restApiError->addPriceListNotFoundError($restResponse);
        }

        return $this->addPriceListTransferToResponse($priceListTransfer, $restResponse);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceListCollectionTransfer $priceListCollectionTransfer
     * @param string $uuidPriceList
     *
     * @return \Generated\Shared\Transfer\PriceListTransfer|null
     */
    protected function getPriceListByPriceListCollection(PriceListCollectionTransfer $priceListCollectionTransfer, string $uuidPriceList): ?PriceListTransfer
    {
        foreach ($priceListCollectionTransfer->getPriceLists() as $priceListTransfer) {
            if ($priceListTransfer->getUuid() === $uuidPriceList) {
                return $priceListTransfer;
            }
        }

        return null;
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
