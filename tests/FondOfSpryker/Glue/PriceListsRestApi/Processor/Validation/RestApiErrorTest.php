<?php

namespace FondOfSpryker\Glue\PriceListsRestApi\Processor\Validation;

use Codeception\Test\Unit;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

class RestApiErrorTest extends Unit
{
    /**
     * @var \FondOfSpryker\Glue\PriceListsRestApi\Processor\Validation\RestApiError
     */
    protected $restApiError;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected $restResponseInterfaceMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->restResponseInterfaceMock = $this->getMockBuilder(RestResponseInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->restApiError = new RestApiError();
    }

    /**
     * @return void
     */
    public function testAddPriceListNotFoundError(): void
    {
        $this->restResponseInterfaceMock->expects($this->atLeastOnce())
            ->method('addError')
            ->willReturnSelf();

        $this->assertInstanceOf(
            RestResponseInterface::class,
            $this->restApiError->addPriceListNotFoundError(
                $this->restResponseInterfaceMock
            )
        );
    }

    /**
     * @return void
     */
    public function testAddPriceListIdMissingError(): void
    {
        $this->restResponseInterfaceMock->expects($this->atLeastOnce())
            ->method('addError')
            ->willReturnSelf();

        $this->assertInstanceOf(
            RestResponseInterface::class,
            $this->restApiError->addPriceListIdMissingError(
                $this->restResponseInterfaceMock
            )
        );
    }

    /**
     * @return void
     */
    public function testAddPriceListNoPermission(): void
    {
        $this->restResponseInterfaceMock->expects($this->atLeastOnce())
            ->method('addError')
            ->willReturnSelf();

        $this->assertInstanceOf(
            RestResponseInterface::class,
            $this->restApiError->addPriceListNoPermission(
                $this->restResponseInterfaceMock
            )
        );
    }
}
