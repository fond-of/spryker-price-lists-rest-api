<?php

namespace FondOfSpryker\Glue\PriceListsRestApi\Processor\Validation;

use Codeception\Test\Unit;
use FondOfSpryker\Glue\PriceListsRestApi\PriceListsRestApiConfig;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class RestApiErrorTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected $restResponseMock;

    /**
     * @var \FondOfSpryker\Glue\PriceListsRestApi\Processor\Validation\RestApiError
     */
    protected $restApiError;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->restResponseMock = $this->getMockBuilder(RestResponseInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->restApiError = new RestApiError();
    }

    /**
     * @return void
     */
    public function testAddPriceListNotFoundError(): void
    {
        $this->restResponseMock->expects(static::atLeastOnce())
            ->method('addError')
            ->with(
                static::callback(
                    static function (RestErrorMessageTransfer $restErrorMessageTransfer) {
                        return $restErrorMessageTransfer->getCode() === PriceListsRestApiConfig::RESPONSE_CODE_PRICE_LIST_NOT_FOUND
                            && $restErrorMessageTransfer->getStatus() === Response::HTTP_NOT_FOUND
                            && $restErrorMessageTransfer->getDetail() === PriceListsRestApiConfig::RESPONSE_DETAILS_PRICE_LIST_NOT_FOUND;
                    },
                ),
            )->willReturnSelf();

        static::assertEquals(
            $this->restResponseMock,
            $this->restApiError->addPriceListNotFoundError(
                $this->restResponseMock,
            ),
        );
    }

    /**
     * @return void
     */
    public function testAddPriceListIdMissingError(): void
    {
        $this->restResponseMock->expects(static::atLeastOnce())
            ->method('addError')
            ->with(
                static::callback(
                    static function (RestErrorMessageTransfer $restErrorMessageTransfer) {
                        return $restErrorMessageTransfer->getCode() === PriceListsRestApiConfig::RESPONSE_CODE_UUID_MISSING
                            && $restErrorMessageTransfer->getStatus() === Response::HTTP_BAD_REQUEST
                            && $restErrorMessageTransfer->getDetail() === PriceListsRestApiConfig::RESPONSE_DETAILS_UUID_MISSING;
                    },
                ),
            )->willReturnSelf();

        static::assertEquals(
            $this->restResponseMock,
            $this->restApiError->addPriceListIdMissingError(
                $this->restResponseMock,
            ),
        );
    }
}
