<?php

namespace FondOfSpryker\Glue\PriceListsRestApi\Dependency\Client;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PriceListCollectionTransfer;
use Generated\Shared\Transfer\PriceListRequestTransfer;

interface PriceListsRestApiToCustomerPriceClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\PriceListCollectionTransfer
     */
    public function getPriceListCollectionByIdCustomer(CustomerTransfer $customerTransfer): PriceListCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\PriceListRequestTransfer $priceListRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PriceListCollectionTransfer
     */
    public function getPriceListsByIdCustomerAndCompanyUuid(PriceListRequestTransfer $priceListRequestTransfer): PriceListCollectionTransfer;
}
