<?php

namespace FondOfSpryker\Glue\PriceListsRestApi\Dependency\Client;

use FondOfSpryker\Client\CustomerPriceList\CustomerPriceListClientInterface;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PriceListCollectionTransfer;
use Generated\Shared\Transfer\PriceListRequestTransfer;

class PriceListsRestApiToCustomerPriceListClientBridge implements PriceListsRestApiToCustomerPriceClientInterface
{
    /**
     * @var \FondOfSpryker\Client\CustomerPriceList\CustomerPriceListClientInterface
     */
    protected $customerPriceListClient;

    /**
     * @param \FondOfSpryker\Client\CustomerPriceList\CustomerPriceListClientInterface $customerPriceListClient
     */
    public function __construct(CustomerPriceListClientInterface $customerPriceListClient)
    {
        $this->customerPriceListClient = $customerPriceListClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\PriceListCollectionTransfer
     */
    public function getPriceListCollectionByIdCustomer(CustomerTransfer $customerTransfer): PriceListCollectionTransfer
    {
        return $this->customerPriceListClient->getPriceListCollectionByIdCustomer($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceListRequestTransfer $priceListRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PriceListCollectionTransfer
     */
    public function getPriceListsByIdCustomerAndCompanyUuid(PriceListRequestTransfer $priceListRequestTransfer): PriceListCollectionTransfer
    {
        return $this->customerPriceListClient->getPriceListsByIdCustomerAndCompanyUuid($priceListRequestTransfer);
    }
}
