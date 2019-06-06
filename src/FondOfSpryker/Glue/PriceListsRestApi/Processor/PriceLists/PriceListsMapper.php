<?php

namespace FondOfSpryker\Glue\PriceListsRestApi\Processor\PriceLists;

use Generated\Shared\Transfer\PriceListTransfer;
use Generated\Shared\Transfer\RestPriceListsAttributesTransfer;
use Generated\Shared\Transfer\RestProductListsAttributesTransfer;

class PriceListsMapper implements PriceListsMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\RestPriceListsAttributesTransfer
     */
    public function mapRestPriceListsAttributesTransfer(PriceListTransfer $priceListTransfer): RestPriceListsAttributesTransfer
    {
        $restPriceListsAttributesTransfer = new RestPriceListsAttributesTransfer();

        $restPriceListsAttributesTransfer->fromArray(
            $priceListTransfer->toArray(),
            true
        );

        return $restPriceListsAttributesTransfer;
    }
}
