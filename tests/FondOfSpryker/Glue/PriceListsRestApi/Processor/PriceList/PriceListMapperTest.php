<?php

namespace FondOfSpryker\Glue\PriceListsRestApi\Processor\PriceList;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PriceListTransfer;
use Generated\Shared\Transfer\RestPriceListAttributesTransfer;

class PriceListMapperTest extends Unit
{
    /**
     * @var \FondOfSpryker\Glue\PriceListsRestApi\Processor\PriceList\PriceListMapper
     */
    protected $priceListMapper;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\PriceListTransfer
     */
    protected $priceListTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\RestPriceListAttributesTransfer
     */
    protected $restPriceListAttributesTransferMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->priceListTransferMock = $this->getMockBuilder(PriceListTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->restPriceListAttributesTransferMock = $this->getMockBuilder(RestPriceListAttributesTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceListMapper = new PriceListMapper();
    }

    /**
     * @return void
     */
    public function testMapPriceListTransferToRestPriceListAttributesTransfer(): void
    {
        $this->priceListTransferMock->expects($this->atLeastOnce())
            ->method('toArray')
            ->willReturn([]);

        $this->restPriceListAttributesTransferMock->expects($this->atLeastOnce())
            ->method('fromArray')
            ->with([], true)
            ->willReturnSelf();

        $this->assertInstanceOf(
            RestPriceListAttributesTransfer::class,
            $this->priceListMapper->mapPriceListTransferToRestPriceListAttributesTransfer(
                $this->priceListTransferMock,
                $this->restPriceListAttributesTransferMock
            )
        );
    }
}
