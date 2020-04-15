<?php

namespace FondOfSpryker\Glue\PriceListsRestApi\Processor\PriceList;

use ArrayObject;
use Codeception\Test\Unit;
use FondOfSpryker\Glue\PriceListsRestApi\Dependency\Client\PriceListsRestApiToCustomerPriceClientInterface;
use FondOfSpryker\Glue\PriceListsRestApi\PriceListsRestApiConfig;
use FondOfSpryker\Glue\PriceListsRestApi\Processor\Validation\RestApiErrorInterface;
use Generated\Shared\Transfer\PriceListCollectionTransfer;
use Generated\Shared\Transfer\PriceListTransfer;
use Generated\Shared\Transfer\RestPriceListAttributesTransfer;
use Generated\Shared\Transfer\RestUserTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class PriceListReaderTest extends Unit
{
    /**
     * @var \FondOfSpryker\Glue\PriceListsRestApi\Processor\PriceList\PriceListReader
     */
    protected $priceListReader;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilderInterfaceMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Glue\PriceListsRestApi\Processor\Validation\RestApiErrorInterface
     */
    protected $restApiErrorInterfaceMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Glue\PriceListsRestApi\Dependency\Client\PriceListsRestApiToCustomerPriceClientInterface
     */
    protected $priceListsRestApiToCustomerPriceClientInterfaceMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Glue\PriceListsRestApi\Processor\PriceList\PriceListMapperInterface
     */
    protected $priceListMapperInterfaceMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface
     */
    protected $restRequestInterfaceMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected $restResponseInterfaceMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\RestUserTransfer
     */
    protected $restUserTransferMock;

    /**
     * @var int
     */
    protected $surrogateIdentifier;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\PriceListCollectionTransfer
     */
    protected $priceListCollectionTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\PriceListTransfer
     */
    protected $priceListTransferMock;

    /**
     * @var \ArrayObject|\Generated\Shared\Transfer\PriceListTransfer[]
     */
    protected $priceLists;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\RestPriceListAttributesTransfer
     */
    protected $restPriceListAttributesTransferMock;

    /**
     * @var string
     */
    protected $uuid;

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
        $this->restResourceBuilderInterfaceMock = $this->getMockBuilder(RestResourceBuilderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->restApiErrorInterfaceMock = $this->getMockBuilder(RestApiErrorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceListsRestApiToCustomerPriceClientInterfaceMock = $this->getMockBuilder(PriceListsRestApiToCustomerPriceClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceListMapperInterfaceMock = $this->getMockBuilder(PriceListMapperInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->restRequestInterfaceMock = $this->getMockBuilder(RestRequestInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->restResponseInterfaceMock = $this->getMockBuilder(RestResponseInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->restUserTransferMock = $this->getMockBuilder(RestUserTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->surrogateIdentifier = 1;

        $this->priceListCollectionTransferMock = $this->getMockBuilder(PriceListCollectionTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceListTransferMock = $this->getMockBuilder(PriceListTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceLists = new ArrayObject([
            $this->priceListTransferMock,
        ]);

        $this->restPriceListAttributesTransferMock = $this->getMockBuilder(RestPriceListAttributesTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->uuid = 'uuid';

        $this->restResourceInterfaceMock = $this->getMockBuilder(RestResourceInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->id = 'id';

        $this->priceListReader = new PriceListReader(
            $this->restResourceBuilderInterfaceMock,
            $this->restApiErrorInterfaceMock,
            $this->priceListsRestApiToCustomerPriceClientInterfaceMock,
            $this->priceListMapperInterfaceMock
        );
    }

    /**
     * @return void
     */
    public function testGetAllPriceLists(): void
    {
        $this->restResourceBuilderInterfaceMock->expects($this->atLeastOnce())
            ->method('createRestResponse')
            ->willReturn($this->restResponseInterfaceMock);

        $this->restRequestInterfaceMock->expects($this->atLeastOnce())
            ->method('getRestUser')
            ->willReturn($this->restUserTransferMock);

        $this->restUserTransferMock->expects($this->atLeastOnce())
            ->method('getSurrogateIdentifier')
            ->willReturn($this->surrogateIdentifier);

        $this->priceListsRestApiToCustomerPriceClientInterfaceMock->expects($this->atLeastOnce())
            ->method('getPriceListCollectionByIdCustomer')
            ->willReturn($this->priceListCollectionTransferMock);

        $this->priceListCollectionTransferMock->expects($this->atLeastOnce())
            ->method('getPriceLists')
            ->willReturn($this->priceLists);

        $this->priceListMapperInterfaceMock->expects($this->atLeastOnce())
            ->method('mapPriceListTransferToRestPriceListAttributesTransfer')
            ->with(
                $this->priceListTransferMock,
                new RestPriceListAttributesTransfer()
            )->willReturn($this->restPriceListAttributesTransferMock);

        $this->priceListTransferMock->expects($this->atLeastOnce())
            ->method('getUuid')
            ->willReturn($this->uuid);

        $this->restResourceBuilderInterfaceMock->expects($this->atLeastOnce())
            ->method('createRestResource')
            ->with(
                PriceListsRestApiConfig::RESOURCE_PRICE_LISTS,
                $this->uuid,
                $this->restPriceListAttributesTransferMock
            )->willReturn($this->restResourceInterfaceMock);

        $this->restResponseInterfaceMock->expects($this->atLeastOnce())
            ->method('addResource')
            ->with($this->restResourceInterfaceMock)
            ->willReturnSelf();

        $this->assertInstanceOf(
            RestResponseInterface::class,
            $this->priceListReader->getAllPriceLists($this->restRequestInterfaceMock)
        );
    }

    /**
     * @return void
     */
    public function testGetAllPriceListsPriceListNotFound(): void
    {
        $this->restResourceBuilderInterfaceMock->expects($this->atLeastOnce())
            ->method('createRestResponse')
            ->willReturn($this->restResponseInterfaceMock);

        $this->restRequestInterfaceMock->expects($this->atLeastOnce())
            ->method('getRestUser')
            ->willReturn($this->restUserTransferMock);

        $this->restUserTransferMock->expects($this->atLeastOnce())
            ->method('getSurrogateIdentifier')
            ->willReturn($this->surrogateIdentifier);

        $this->priceListsRestApiToCustomerPriceClientInterfaceMock->expects($this->atLeastOnce())
            ->method('getPriceListCollectionByIdCustomer')
            ->willReturn($this->priceListCollectionTransferMock);

        $this->priceListCollectionTransferMock->expects($this->atLeastOnce())
            ->method('getPriceLists')
            ->willReturn(new ArrayObject([]));

        $this->restApiErrorInterfaceMock->expects($this->atLeastOnce())
            ->method('addPriceListNotFoundError')
            ->with($this->restResponseInterfaceMock)
            ->willReturn($this->restResponseInterfaceMock);

        $this->assertInstanceOf(
            RestResponseInterface::class,
            $this->priceListReader->getAllPriceLists($this->restRequestInterfaceMock)
        );
    }

    /**
     * @return void
     */
    public function testGetPriceListByUuidPriceListMissing(): void
    {
        $this->restResourceBuilderInterfaceMock->expects($this->atLeastOnce())
            ->method('createRestResponse')
            ->willReturn($this->restResponseInterfaceMock);

        $this->restRequestInterfaceMock->expects($this->atLeastOnce())
            ->method('getResource')
            ->willReturn($this->restResourceInterfaceMock);

        $this->restResourceInterfaceMock->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($this->uuid);

        $this->restRequestInterfaceMock->expects($this->atLeastOnce())
            ->method('getRestUser')
            ->willReturn($this->restUserTransferMock);

        $this->restUserTransferMock->expects($this->atLeastOnce())
            ->method('getSurrogateIdentifier')
            ->willReturn($this->surrogateIdentifier);

        $this->priceListsRestApiToCustomerPriceClientInterfaceMock->expects($this->atLeastOnce())
            ->method('getPriceListCollectionByIdCustomer')
            ->willReturn($this->priceListCollectionTransferMock);

        $this->priceListCollectionTransferMock->expects($this->atLeastOnce())
            ->method('getPriceLists')
            ->willReturn($this->priceLists);

        $this->priceListTransferMock->expects($this->atLeastOnce())
            ->method('getUuid')
            ->willReturn($this->uuid);

        $this->priceListMapperInterfaceMock->expects($this->atLeastOnce())
            ->method('mapPriceListTransferToRestPriceListAttributesTransfer')
            ->with(
                $this->priceListTransferMock,
                new RestPriceListAttributesTransfer()
            )->willReturn($this->restPriceListAttributesTransferMock);

        $this->restResourceBuilderInterfaceMock->expects($this->atLeastOnce())
            ->method('createRestResource')
            ->with(
                PriceListsRestApiConfig::RESOURCE_PRICE_LISTS,
                $this->uuid,
                $this->restPriceListAttributesTransferMock
            )->willReturn($this->restResourceInterfaceMock);

        $this->restResponseInterfaceMock->expects($this->atLeastOnce())
            ->method('addResource')
            ->with($this->restResourceInterfaceMock)
            ->willReturnSelf();

        $this->assertInstanceOf(
            RestResponseInterface::class,
            $this->priceListReader->getPriceListByUuid(
                $this->restRequestInterfaceMock
            )
        );
    }

    /**
     * @return void
     */
    public function testGetPriceListByUuid(): void
    {
        $this->restResourceBuilderInterfaceMock->expects($this->atLeastOnce())
            ->method('createRestResponse')
            ->willReturn($this->restResponseInterfaceMock);

        $this->restRequestInterfaceMock->expects($this->atLeastOnce())
            ->method('getResource')
            ->willReturn($this->restResourceInterfaceMock);

        $this->restResourceInterfaceMock->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($this->uuid);

        $this->restRequestInterfaceMock->expects($this->atLeastOnce())
            ->method('getRestUser')
            ->willReturn($this->restUserTransferMock);

        $this->restUserTransferMock->expects($this->atLeastOnce())
            ->method('getSurrogateIdentifier')
            ->willReturn($this->surrogateIdentifier);

        $this->priceListsRestApiToCustomerPriceClientInterfaceMock->expects($this->atLeastOnce())
            ->method('getPriceListCollectionByIdCustomer')
            ->willReturn($this->priceListCollectionTransferMock);

        $this->priceListCollectionTransferMock->expects($this->atLeastOnce())
            ->method('getPriceLists')
            ->willReturn($this->priceLists);

        $this->priceListTransferMock->expects($this->atLeastOnce())
            ->method('getUuid')
            ->willReturn($this->uuid);

        $this->priceListMapperInterfaceMock->expects($this->atLeastOnce())
            ->method('mapPriceListTransferToRestPriceListAttributesTransfer')
            ->with(
                $this->priceListTransferMock,
                new RestPriceListAttributesTransfer()
            )->willReturn($this->restPriceListAttributesTransferMock);

        $this->restResourceBuilderInterfaceMock->expects($this->atLeastOnce())
            ->method('createRestResource')
            ->with(
                PriceListsRestApiConfig::RESOURCE_PRICE_LISTS,
                $this->uuid,
                $this->restPriceListAttributesTransferMock
            )->willReturn($this->restResourceInterfaceMock);

        $this->restResponseInterfaceMock->expects($this->atLeastOnce())
            ->method('addResource')
            ->with($this->restResourceInterfaceMock)
            ->willReturnSelf();

        $this->assertInstanceOf(
            RestResponseInterface::class,
            $this->priceListReader->getPriceListByUuid(
                $this->restRequestInterfaceMock
            )
        );
    }

    /**
     * @return void
     */
    public function testGetPriceListByUuidPriceListIdMissing(): void
    {
        $this->restResourceBuilderInterfaceMock->expects($this->atLeastOnce())
            ->method('createRestResponse')
            ->willReturn($this->restResponseInterfaceMock);

        $this->restRequestInterfaceMock->expects($this->atLeastOnce())
            ->method('getResource')
            ->willReturn($this->restResourceInterfaceMock);

        $this->restResourceInterfaceMock->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn(null);

        $this->restApiErrorInterfaceMock->expects($this->atLeastOnce())
            ->method('addPriceListIdMissingError')
            ->with($this->restResponseInterfaceMock)
            ->willReturn($this->restResponseInterfaceMock);

        $this->assertInstanceOf(
            RestResponseInterface::class,
            $this->priceListReader->getPriceListByUuid(
                $this->restRequestInterfaceMock
            )
        );
    }

    /**
     * @return void
     */
    public function testGetPriceListByUuidPriceListNoPermission(): void
    {
        $this->restResourceBuilderInterfaceMock->expects($this->atLeastOnce())
            ->method('createRestResponse')
            ->willReturn($this->restResponseInterfaceMock);

        $this->restRequestInterfaceMock->expects($this->atLeastOnce())
            ->method('getResource')
            ->willReturn($this->restResourceInterfaceMock);

        $this->restResourceInterfaceMock->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($this->uuid);

        $this->restRequestInterfaceMock->expects($this->atLeastOnce())
            ->method('getRestUser')
            ->willReturn($this->restUserTransferMock);

        $this->restUserTransferMock->expects($this->atLeastOnce())
            ->method('getSurrogateIdentifier')
            ->willReturn($this->surrogateIdentifier);

        $this->priceListsRestApiToCustomerPriceClientInterfaceMock->expects($this->atLeastOnce())
            ->method('getPriceListCollectionByIdCustomer')
            ->willReturn($this->priceListCollectionTransferMock);

        $this->priceListCollectionTransferMock->expects($this->atLeastOnce())
            ->method('getPriceLists')
            ->willReturn(new ArrayObject([]));

        $this->restApiErrorInterfaceMock->expects($this->atLeastOnce())
            ->method('addPriceListNoPermission')
            ->with($this->restResponseInterfaceMock)
            ->willReturn($this->restResponseInterfaceMock);

        $this->assertInstanceOf(
            RestResponseInterface::class,
            $this->priceListReader->getPriceListByUuid(
                $this->restRequestInterfaceMock
            )
        );
    }

    /**
     * @return void
     */
    public function testGetPriceListByUuidPriceListNotFound(): void
    {
        $this->restResourceBuilderInterfaceMock->expects($this->atLeastOnce())
            ->method('createRestResponse')
            ->willReturn($this->restResponseInterfaceMock);

        $this->restRequestInterfaceMock->expects($this->atLeastOnce())
            ->method('getResource')
            ->willReturn($this->restResourceInterfaceMock);

        $this->restResourceInterfaceMock->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($this->uuid);

        $this->restRequestInterfaceMock->expects($this->atLeastOnce())
            ->method('getRestUser')
            ->willReturn($this->restUserTransferMock);

        $this->restUserTransferMock->expects($this->atLeastOnce())
            ->method('getSurrogateIdentifier')
            ->willReturn($this->surrogateIdentifier);

        $this->priceListsRestApiToCustomerPriceClientInterfaceMock->expects($this->atLeastOnce())
            ->method('getPriceListCollectionByIdCustomer')
            ->willReturn($this->priceListCollectionTransferMock);

        $this->priceListCollectionTransferMock->expects($this->atLeastOnce())
            ->method('getPriceLists')
            ->willReturn($this->priceLists);

        $this->priceListTransferMock->expects($this->atLeastOnce())
            ->method('getUuid')
            ->willReturn($this->id);

        $this->restApiErrorInterfaceMock->expects($this->atLeastOnce())
            ->method('addPriceListNotFoundError')
            ->with($this->restResponseInterfaceMock)
            ->willReturn($this->restResponseInterfaceMock);

        $this->assertInstanceOf(
            RestResponseInterface::class,
            $this->priceListReader->getPriceListByUuid(
                $this->restRequestInterfaceMock
            )
        );
    }
}
