<?php

namespace FondOfSpryker\Glue\PriceListsRestApi\Processor\PriceLists;

use Generated\Shared\Transfer\PriceListTransfer;
use Generated\Shared\Transfer\RestPriceListsAttributesTransfer;

interface PriceListsMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceListTransfer $priceListTransfer
     *
     * @return \Generated\Shared\Transfer\RestPriceListsAttributesTransfer
     */
    public function mapRestPriceListsAttributesTransfer(
        PriceListTransfer $priceListTransfer
    ): RestPriceListsAttributesTransfer;
}
