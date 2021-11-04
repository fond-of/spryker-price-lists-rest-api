<?php

namespace FondOfSpryker\Glue\PriceListsRestApi;

use Codeception\Test\Unit;
use FondOfSpryker\Glue\PriceListsRestApi\Dependency\Client\PriceListsRestApiToCustomerPriceClientInterface;
use FondOfSpryker\Glue\PriceListsRestApi\Processor\PriceList\PriceListReaderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\Kernel\Container;

class PriceListsRestApiFactoryTest extends Unit
{
    /**
     * @var \FondOfSpryker\Glue\PriceListsRestApi\PriceListsRestApiFactory
     */
    protected $priceListsRestApiFactory;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilderInterfaceMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Glue\PriceListsRestApi\Dependency\Client\PriceListsRestApiToCustomerPriceClientInterface
     */
    protected $priceListsRestApiToCustomerPriceClientInterfaceMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\Kernel\Container
     */
    protected $containerMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->containerMock = $this->getMockBuilder(Container::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->restResourceBuilderInterfaceMock = $this->getMockBuilder(RestResourceBuilderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceListsRestApiToCustomerPriceClientInterfaceMock = $this->getMockBuilder(PriceListsRestApiToCustomerPriceClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceListsRestApiFactory = new class (
            $this->restResourceBuilderInterfaceMock
        ) extends PriceListsRestApiFactory {
            /**
             * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
             */
            protected $restResourceBuilder;

            /**
             * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
             */
            public function __construct(RestResourceBuilderInterface $restResourceBuilder)
            {
                $this->restResourceBuilder = $restResourceBuilder;
            }

            /**
             * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
             */
            public function getResourceBuilder(): RestResourceBuilderInterface
            {
                return $this->restResourceBuilder;
            }
        };
        $this->priceListsRestApiFactory->setContainer($this->containerMock);
    }

    /**
     * @return void
     */
    public function testCreatePriceListMapper(): void
    {
        $this->containerMock->expects($this->atLeastOnce())
            ->method('has')
            ->willReturn(true);

        $this->containerMock->expects($this->atLeastOnce())
            ->method('get')
            ->with(PriceListsRestApiDependencyProvider::CLIENT_PRICE_LIST)
            ->willReturn($this->priceListsRestApiToCustomerPriceClientInterfaceMock);

        $this->assertInstanceOf(
            PriceListReaderInterface::class,
            $this->priceListsRestApiFactory->createPriceListReader()
        );
    }
}
