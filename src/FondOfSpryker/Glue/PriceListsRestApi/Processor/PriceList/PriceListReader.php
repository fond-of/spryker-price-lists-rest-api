<?php

namespace FondOfSpryker\Glue\PriceListsRestApi\Processor\PriceList;

use FondOfSpryker\Glue\PriceListsRestApi\Dependency\Client\PriceListsRestApiToCustomerPriceClientInterface;
use FondOfSpryker\Glue\PriceListsRestApi\PriceListsRestApiConfig;
use FondOfSpryker\Glue\PriceListsRestApi\Processor\Validation\RestApiErrorInterface;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PriceListCollectionTransfer;
use Generated\Shared\Transfer\PriceListRequestTransfer;
use Generated\Shared\Transfer\PriceListTransfer;
use Generated\Shared\Transfer\RestPriceListAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class PriceListReader implements PriceListReaderInterface
{
    protected const FILTER_COMPANY_ID = 'company-id';

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
     * @var \FondOfSpryker\Glue\PriceListsRestApi\Dependency\Client\PriceListsRestApiToCustomerPriceClientInterface
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
    public function getPriceLists(RestRequestInterface $restRequest): RestResponseInterface
    {
        $companyUuid = $this->getRequestParameter($restRequest, static::FILTER_COMPANY_ID);

        if ($companyUuid !== null) {
            return $this->getPriceListByCompanyUuid($restRequest, $companyUuid);
        }

        if ($restRequest->getResource()->getId()) {
            return $this->getPriceListByUuid($restRequest);
        }

        return $this->getAllPriceLists($restRequest);
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
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param string $companyUuid
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getPriceListByCompanyUuid(RestRequestInterface $restRequest, string $companyUuid): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $customerTransfer = (new CustomerTransfer())
            ->setIdCustomer($restRequest->getRestUser()->getSurrogateIdentifier());

        $companyTransfer = (new CompanyTransfer())
            ->setUuid($companyUuid);

        $priceListRequestTransfer = new PriceListRequestTransfer();
        $priceListRequestTransfer->setCompany($companyTransfer)->setCustomer($customerTransfer);

        $priceListCollectionTransfer = $this->customerPriceListClient->getPriceListsByIdCustomerAndCompanyUuid($priceListRequestTransfer);

        if ($restRequest->getResource()->getId() === null) {
            if ($priceListCollectionTransfer->getPriceLists()->count() === 0) {
                return $this->restApiError->addPriceListNotFoundError($restResponse);
            }

            return $this->addPriceListCollectionTransferToResponse($priceListCollectionTransfer, $restResponse);
        }

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

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param string $parameterName
     *
     * @return string|null
     */
    protected function getRequestParameter(RestRequestInterface $restRequest, string $parameterName): ?string
    {
        return $restRequest->getHttpRequest()->query->get($parameterName, null);
    }
}
