<?php

namespace FondOfSpryker\Glue\PriceListsRestApi\Controller;

use Codeception\Test\Unit;
use FondOfSpryker\Glue\PriceListsRestApi\PriceListsRestApiFactory;
use FondOfSpryker\Glue\PriceListsRestApi\Processor\PriceList\PriceListReaderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class PriceListsResourceControllerTest extends Unit
{
    /**
     * @var \FondOfSpryker\Glue\PriceListsRestApi\Controller\PriceListsResourceController
     */
    protected $priceListsResourceController;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Glue\PriceListsRestApi\PriceListsRestApiFactory
     */
    protected $priceListsRestApiFactoryMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface
     */
    protected $restRequestInterfaceMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Glue\PriceListsRestApi\Processor\PriceList\PriceListReaderInterface
     */
    protected $priceListReaderInterfaceMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected $restResponseInterfaceMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected $restResourceInterfaceMock;

    /**
     * @var string
     */
    protected $id;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->priceListsRestApiFactoryMock = $this->getMockBuilder(PriceListsRestApiFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->restRequestInterfaceMock = $this->getMockBuilder(RestRequestInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceListReaderInterfaceMock = $this->getMockBuilder(PriceListReaderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->restResponseInterfaceMock = $this->getMockBuilder(RestResponseInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->restResourceInterfaceMock = $this->getMockBuilder(RestResourceInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->id = 'id';

        $this->priceListsResourceController = new class (
            $this->priceListsRestApiFactoryMock
        ) extends PriceListsResourceController{
            /**
             * @var \FondOfSpryker\Glue\PriceListsRestApi\PriceListsRestApiFactory
             */
            protected $priceListsRestApiFactory;

            /**
             * @param \FondOfSpryker\Glue\PriceListsRestApi\PriceListsRestApiFactory $priceListsRestApiFactory
             */
            public function __construct(PriceListsRestApiFactory $priceListsRestApiFactory)
            {
                $this->priceListsRestApiFactory = $priceListsRestApiFactory;
            }

            /**
             * @return \FondOfSpryker\Glue\PriceListsRestApi\PriceListsRestApiFactory
             */
            public function getFactory(): PriceListsRestApiFactory
            {
                return $this->priceListsRestApiFactory;
            }
        };
    }

    /**
     * @return void
     */
    public function testGetActionPriceListByUuid(): void
    {
        $this->restRequestInterfaceMock->expects($this->atLeastOnce())
            ->method('getResource')
            ->willReturn($this->restResourceInterfaceMock);

        $this->restResourceInterfaceMock->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($this->id);

        $this->priceListsRestApiFactoryMock->expects($this->atLeastOnce())
            ->method('createPriceListReader')
            ->willReturn($this->priceListReaderInterfaceMock);

        $this->priceListReaderInterfaceMock->expects($this->atLeastOnce())
            ->method('getPriceListByUuid')
            ->with($this->restRequestInterfaceMock)
            ->willReturn($this->restResponseInterfaceMock);

        $this->assertInstanceOf(
            RestResponseInterface::class,
            $this->priceListsResourceController->getAction(
                $this->restRequestInterfaceMock
            )
        );
    }

    /**
     * @return void
     */
    public function testGetActionAllPriceLists(): void
    {
        $this->restRequestInterfaceMock->expects($this->atLeastOnce())
            ->method('getResource')
            ->willReturn($this->restResourceInterfaceMock);

        $this->restResourceInterfaceMock->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn(null);

        $this->priceListsRestApiFactoryMock->expects($this->atLeastOnce())
            ->method('createPriceListReader')
            ->willReturn($this->priceListReaderInterfaceMock);

        $this->priceListReaderInterfaceMock->expects($this->atLeastOnce())
            ->method('getAllPriceLists')
            ->with($this->restRequestInterfaceMock)
            ->willReturn($this->restResponseInterfaceMock);

        $this->assertInstanceOf(
            RestResponseInterface::class,
            $this->priceListsResourceController->getAction(
                $this->restRequestInterfaceMock
            )
        );
    }
}
