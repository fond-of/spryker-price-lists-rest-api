<?php

namespace FondOfSpryker\Glue\PriceListsRestApi\Dependency\Client;

use FondOfSpryker\Client\PriceList\PriceListClientInterface;
use Generated\Shared\Transfer\PriceListListTransfer;

class PriceListsRestApiToPriceListClientBridge implements PriceListsRestApiToPriceListClientInterface
{
    /**
     * @var \FondOfSpryker\Client\PriceList\PriceListClientInterface
     */
    protected $priceListClient;

    /**
     * @param \FondOfSpryker\Client\PriceList\PriceListClientInterface $priceListClient
     */
    public function __construct(PriceListClientInterface $priceListClient)
    {
        $this->priceListClient = $priceListClient;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceListListTransfer $priceListListTransfer
     *
     * @return \Generated\Shared\Transfer\PriceListListTransfer
     */
    public function findPriceLists(PriceListListTransfer $priceListListTransfer): PriceListListTransfer
    {
        return $this->priceListClient->findPriceLists($priceListListTransfer);
    }
}
