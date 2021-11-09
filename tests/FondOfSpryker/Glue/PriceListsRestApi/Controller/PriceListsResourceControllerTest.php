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
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Glue\PriceListsRestApi\PriceListsRestApiFactory
     */
    protected $priceListsRestApiFactoryMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface
     */
    protected $restRequestMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Glue\PriceListsRestApi\Processor\PriceList\PriceListReaderInterface
     */
    protected $priceListReaderMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected $restResponseMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected $restResourceMock;

    /**
     * @var \FondOfSpryker\Glue\PriceListsRestApi\Controller\PriceListsResourceController
     */
    protected $priceListsResourceController;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->priceListsRestApiFactoryMock = $this->getMockBuilder(PriceListsRestApiFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->restRequestMock = $this->getMockBuilder(RestRequestInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceListReaderMock = $this->getMockBuilder(PriceListReaderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->restResponseMock = $this->getMockBuilder(RestResponseInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->restResourceMock = $this->getMockBuilder(RestResourceInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceListsResourceController = new class (
            $this->priceListsRestApiFactoryMock
        ) extends PriceListsResourceController {
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
    public function testGetActionWithResourceId(): void
    {
        $resourceId = '8bb8ea24-51f1-47b6-9291-95d37611108e';

        $this->restRequestMock->expects(static::atLeastOnce())
            ->method('getResource')
            ->willReturn($this->restResourceMock);

        $this->restResourceMock->expects(static::atLeastOnce())
            ->method('getId')
            ->willReturn($resourceId);

        $this->priceListsRestApiFactoryMock->expects(static::atLeastOnce())
            ->method('createPriceListReader')
            ->willReturn($this->priceListReaderMock);

        $this->priceListReaderMock->expects(static::atLeastOnce())
            ->method('getPriceListByUuid')
            ->with($this->restRequestMock)
            ->willReturn($this->restResponseMock);

        static::assertEquals(
            $this->restResponseMock,
            $this->priceListsResourceController->getAction(
                $this->restRequestMock
            )
        );
    }

    /**
     * @return void
     */
    public function testGetAction(): void
    {
        $this->restRequestMock->expects(static::atLeastOnce())
            ->method('getResource')
            ->willReturn($this->restResourceMock);

        $this->restResourceMock->expects(static::atLeastOnce())
            ->method('getId')
            ->willReturn(null);

        $this->priceListsRestApiFactoryMock->expects(static::atLeastOnce())
            ->method('createPriceListReader')
            ->willReturn($this->priceListReaderMock);

        $this->priceListReaderMock->expects(static::atLeastOnce())
            ->method('getAllPriceLists')
            ->with($this->restRequestMock)
            ->willReturn($this->restResponseMock);

        static::assertEquals(
            $this->restResponseMock,
            $this->priceListsResourceController->getAction(
                $this->restRequestMock
            )
        );
    }
}
