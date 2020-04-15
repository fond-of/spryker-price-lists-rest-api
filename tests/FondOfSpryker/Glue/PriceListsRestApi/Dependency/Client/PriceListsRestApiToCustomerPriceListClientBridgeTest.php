<?php

namespace FondOfSpryker\Glue\PriceListsRestApi\Dependency\Client;

use Codeception\Test\Unit;
use FondOfSpryker\Client\CustomerPriceList\CustomerPriceListClientInterface;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PriceListCollectionTransfer;

class PriceListsRestApiToCustomerPriceListClientBridgeTest extends Unit
{
    /**
     * @var \FondOfSpryker\Glue\PriceListsRestApi\Dependency\Client\PriceListsRestApiToCustomerPriceListClientBridge
     */
    protected $priceListsRestApiToCustomerPriceListClientBridge;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Client\CustomerPriceList\CustomerPriceListClientInterface
     */
    protected $customerPriceListClientInterfaceMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\PriceListCollectionTransfer
     */
    protected $priceListCollectionTransferMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->customerPriceListClientInterfaceMock = $this->getMockBuilder(CustomerPriceListClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerTransferMock = $this->getMockBuilder(CustomerTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceListCollectionTransferMock = $this->getMockBuilder(PriceListCollectionTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceListsRestApiToCustomerPriceListClientBridge = new PriceListsRestApiToCustomerPriceListClientBridge(
            $this->customerPriceListClientInterfaceMock
        );
    }

    /**
     * @return void
     */
    public function testGetPriceListCollectionByIdCustomer(): void
    {
        $this->customerPriceListClientInterfaceMock->expects($this->atLeastOnce())
            ->method('getPriceListCollectionByIdCustomer')
            ->with($this->customerTransferMock)
            ->willReturn($this->priceListCollectionTransferMock);

        $this->assertInstanceOf(
            PriceListCollectionTransfer::class,
            $this->priceListsRestApiToCustomerPriceListClientBridge->getPriceListCollectionByIdCustomer(
                $this->customerTransferMock
            )
        );
    }
}
