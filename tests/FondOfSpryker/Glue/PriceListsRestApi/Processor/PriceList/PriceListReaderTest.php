<?php

namespace FondOfSpryker\Glue\PriceListsRestApi\Processor\PriceList;

use ArrayObject;
use Codeception\Test\Unit;
use FondOfOryx\Glue\PriceListsRestApiExtension\Dependency\Plugin\FilterFieldsExpanderPluginInterface;
use FondOfSpryker\Glue\PriceListsRestApi\Dependency\Client\PriceListsRestApiToPriceListClientInterface;
use FondOfSpryker\Glue\PriceListsRestApi\PriceListsRestApiConfig;
use FondOfSpryker\Glue\PriceListsRestApi\Processor\Validation\RestApiErrorInterface;
use Generated\Shared\Transfer\PriceListListTransfer;
use Generated\Shared\Transfer\PriceListTransfer;
use Generated\Shared\Transfer\RestPriceListAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class PriceListReaderTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilderMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Glue\PriceListsRestApi\Processor\Validation\RestApiErrorInterface
     */
    protected $restApiErrorMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Glue\PriceListsRestApi\Dependency\Client\PriceListsRestApiToPriceListClientInterface
     */
    protected $priceListClientMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Glue\PriceListsRestApi\Processor\PriceList\PriceListMapperInterface
     */
    protected $priceListMapperMock;

    /**
     * @var array<\FondOfOryx\Glue\PriceListsRestApiExtension\Dependency\Plugin\FilterFieldsExpanderPluginInterface|\PHPUnit\Framework\MockObject\MockObject>
     */
    protected $filterFieldsExpanderPluginMocks;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface
     */
    protected $restRequestMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected $restResponseMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\PriceListListTransfer
     */
    protected $priceListListTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\PriceListTransfer
     */
    protected $priceListTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\RestPriceListAttributesTransfer
     */
    protected $restPriceListAttributesTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected $restResourceMock;

    /**
     * @var \FondOfSpryker\Glue\PriceListsRestApi\Processor\PriceList\PriceListReader
     */
    protected $priceListReader;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->restResourceBuilderMock = $this->getMockBuilder(RestResourceBuilderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->restApiErrorMock = $this->getMockBuilder(RestApiErrorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceListClientMock = $this->getMockBuilder(PriceListsRestApiToPriceListClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceListMapperMock = $this->getMockBuilder(PriceListMapperInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->filterFieldsExpanderPluginMocks = [
            $this->getMockBuilder(FilterFieldsExpanderPluginInterface::class)
                ->disableOriginalConstructor()
                ->getMock(),
        ];

        $this->restRequestMock = $this->getMockBuilder(RestRequestInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->restResponseMock = $this->getMockBuilder(RestResponseInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceListListTransferMock = $this->getMockBuilder(PriceListListTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceListTransferMock = $this->getMockBuilder(PriceListTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->restPriceListAttributesTransferMock = $this->getMockBuilder(RestPriceListAttributesTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->restResourceMock = $this->getMockBuilder(RestResourceInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceListReader = new PriceListReader(
            $this->restResourceBuilderMock,
            $this->restApiErrorMock,
            $this->priceListClientMock,
            $this->priceListMapperMock,
            $this->filterFieldsExpanderPluginMocks,
        );
    }

    /**
     * @return void
     */
    public function testGetAllPriceLists(): void
    {
        $uuid = '011c4a22-49de-4ed6-b4cc-5a24c5b031b8';
        $filterFieldTransfer = new ArrayObject();

        $this->filterFieldsExpanderPluginMocks[0]->expects(static::atLeastOnce())
            ->method('expand')
            ->with($this->restRequestMock, static::callback(
                static function (ArrayObject $filterFieldTransfers) {
                    return $filterFieldTransfers->count() === 0;
                },
            ))->willReturn($filterFieldTransfer);

        $this->priceListClientMock->expects(static::atLeastOnce())
            ->method('findPriceLists')
            ->with(
                static::callback(
                    static function (PriceListListTransfer $priceListListTransfer) use ($filterFieldTransfer) {
                        return $priceListListTransfer->getQueryJoins() === null
                            && $priceListListTransfer->getPriceLists()->count() === 0
                            && $priceListListTransfer->getFilterFields() === $filterFieldTransfer;
                    },
                ),
            )->willReturn($this->priceListListTransferMock);

        $this->priceListListTransferMock->expects(static::atLeastOnce())
            ->method('getPriceLists')
            ->willReturn(new ArrayObject([$this->priceListTransferMock]));

        $this->restResourceBuilderMock->expects(static::atLeastOnce())
            ->method('createRestResponse')
            ->willReturn($this->restResponseMock);

        $this->priceListMapperMock->expects(static::atLeastOnce())
            ->method('mapPriceListTransferToRestPriceListAttributesTransfer')
            ->with(
                $this->priceListTransferMock,
                new RestPriceListAttributesTransfer(),
            )->willReturn($this->restPriceListAttributesTransferMock);

        $this->priceListTransferMock->expects(static::atLeastOnce())
            ->method('getUuid')
            ->willReturn($uuid);

        $this->restResourceBuilderMock->expects(static::atLeastOnce())
            ->method('createRestResource')
            ->with(
                PriceListsRestApiConfig::RESOURCE_PRICE_LISTS,
                $uuid,
                $this->restPriceListAttributesTransferMock,
            )->willReturn($this->restResourceMock);

        $this->restResponseMock->expects(static::atLeastOnce())
            ->method('addResource')
            ->with($this->restResourceMock)
            ->willReturn($this->restResponseMock);

        static::assertEquals(
            $this->restResponseMock,
            $this->priceListReader->getAllPriceLists($this->restRequestMock),
        );
    }

    /**
     * @return void
     */
    public function testGetPriceListByUuid(): void
    {
        $uuid = '011c4a22-49de-4ed6-b4cc-5a24c5b031b8';
        $filterFieldTransfer = new ArrayObject();

        $this->restResourceBuilderMock->expects(static::atLeastOnce())
            ->method('createRestResponse')
            ->willReturn($this->restResponseMock);

        $this->restRequestMock->expects(static::atLeastOnce())
            ->method('getResource')
            ->willReturn($this->restResourceMock);

        $this->restResourceMock->expects(static::atLeastOnce())
            ->method('getId')
            ->willReturn($uuid);

        $this->restApiErrorMock->expects(static::never())
            ->method('addPriceListIdMissingError');

        $this->filterFieldsExpanderPluginMocks[0]->expects(static::atLeastOnce())
            ->method('expand')
            ->with($this->restRequestMock, static::callback(
                static function (ArrayObject $filterFieldTransfers) {
                    return $filterFieldTransfers->count() === 0;
                },
            ))->willReturn($filterFieldTransfer);

        $this->priceListClientMock->expects(static::atLeastOnce())
            ->method('findPriceLists')
            ->with(
                static::callback(
                    static function (PriceListListTransfer $priceListListTransfer) use ($filterFieldTransfer) {
                        return $priceListListTransfer->getQueryJoins() === null
                            && $priceListListTransfer->getPriceLists()->count() === 0
                            && $priceListListTransfer->getFilterFields() === $filterFieldTransfer;
                    },
                ),
            )->willReturn($this->priceListListTransferMock);

        $this->priceListListTransferMock->expects(static::atLeastOnce())
            ->method('getPriceLists')
            ->willReturn(new ArrayObject([$this->priceListTransferMock]));

        $this->priceListMapperMock->expects(static::atLeastOnce())
            ->method('mapPriceListTransferToRestPriceListAttributesTransfer')
            ->with(
                $this->priceListTransferMock,
                new RestPriceListAttributesTransfer(),
            )->willReturn($this->restPriceListAttributesTransferMock);

        $this->priceListTransferMock->expects(static::atLeastOnce())
            ->method('getUuid')
            ->willReturn($uuid);

        $this->restApiErrorMock->expects(static::never())
            ->method('addPriceListNotFoundError');

        $this->restResourceBuilderMock->expects(static::atLeastOnce())
            ->method('createRestResource')
            ->with(
                PriceListsRestApiConfig::RESOURCE_PRICE_LISTS,
                $uuid,
                $this->restPriceListAttributesTransferMock,
            )->willReturn($this->restResourceMock);

        $this->restResponseMock->expects(static::atLeastOnce())
            ->method('addResource')
            ->with($this->restResourceMock)
            ->willReturn($this->restResponseMock);

        static::assertEquals(
            $this->restResponseMock,
            $this->priceListReader->getPriceListByUuid($this->restRequestMock),
        );
    }

    /**
     * @return void
     */
    public function testGetPriceListByUuidWithoutResourceId(): void
    {
        $this->restResourceBuilderMock->expects(static::atLeastOnce())
            ->method('createRestResponse')
            ->willReturn($this->restResponseMock);

        $this->restRequestMock->expects(static::atLeastOnce())
            ->method('getResource')
            ->willReturn($this->restResourceMock);

        $this->restResourceMock->expects(static::atLeastOnce())
            ->method('getId')
            ->willReturn(null);

        $this->restApiErrorMock->expects(static::atLeastOnce())
            ->method('addPriceListIdMissingError')
            ->with($this->restResponseMock)
            ->willReturn($this->restResponseMock);

        $this->filterFieldsExpanderPluginMocks[0]->expects(static::never())
            ->method('expand');

        $this->priceListClientMock->expects(static::never())
            ->method('findPriceLists');

        $this->priceListMapperMock->expects(static::never())
            ->method('mapPriceListTransferToRestPriceListAttributesTransfer');

        $this->restApiErrorMock->expects(static::never())
            ->method('addPriceListNotFoundError')
            ->with($this->restResponseMock)
            ->willReturn($this->restResponseMock);

        $this->restResourceBuilderMock->expects(static::never())
            ->method('createRestResource');

        $this->restResponseMock->expects(static::never())
            ->method('addResource');

        static::assertEquals(
            $this->restResponseMock,
            $this->priceListReader->getPriceListByUuid($this->restRequestMock),
        );
    }

    /**
     * @return void
     */
    public function testGetPriceListByUuidWithNonExistingPriceList(): void
    {
        $uuid = '011c4a22-49de-4ed6-b4cc-5a24c5b031b8';
        $filterFieldTransfer = new ArrayObject();

        $this->restResourceBuilderMock->expects(static::atLeastOnce())
            ->method('createRestResponse')
            ->willReturn($this->restResponseMock);

        $this->restRequestMock->expects(static::atLeastOnce())
            ->method('getResource')
            ->willReturn($this->restResourceMock);

        $this->restResourceMock->expects(static::atLeastOnce())
            ->method('getId')
            ->willReturn($uuid);

        $this->restApiErrorMock->expects(static::never())
            ->method('addPriceListIdMissingError');

        $this->filterFieldsExpanderPluginMocks[0]->expects(static::atLeastOnce())
            ->method('expand')
            ->with($this->restRequestMock, static::callback(
                static function (ArrayObject $filterFieldTransfers) {
                    return $filterFieldTransfers->count() === 0;
                },
            ))->willReturn($filterFieldTransfer);

        $this->priceListClientMock->expects(static::atLeastOnce())
            ->method('findPriceLists')
            ->with(
                static::callback(
                    static function (PriceListListTransfer $priceListListTransfer) use ($filterFieldTransfer) {
                        return $priceListListTransfer->getQueryJoins() === null
                            && $priceListListTransfer->getPriceLists()->count() === 0
                            && $priceListListTransfer->getFilterFields() === $filterFieldTransfer;
                    },
                ),
            )->willReturn($this->priceListListTransferMock);

        $this->priceListListTransferMock->expects(static::atLeastOnce())
            ->method('getPriceLists')
            ->willReturn(new ArrayObject());

        $this->priceListMapperMock->expects(static::never())
            ->method('mapPriceListTransferToRestPriceListAttributesTransfer');

        $this->restApiErrorMock->expects(static::atLeastOnce())
            ->method('addPriceListNotFoundError')
            ->with($this->restResponseMock)
            ->willReturn($this->restResponseMock);

        $this->restResourceBuilderMock->expects(static::never())
            ->method('createRestResource');

        $this->restResponseMock->expects(static::never())
            ->method('addResource');

        static::assertEquals(
            $this->restResponseMock,
            $this->priceListReader->getPriceListByUuid($this->restRequestMock),
        );
    }
}
