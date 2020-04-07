<?php

namespace FondOfSpryker\Glue\PriceListsRestApi;

use Codeception\Test\Unit;
use Spryker\Glue\Kernel\Container;

class PriceListsRestApiDependencyProviderTest extends Unit
{
    /**
     * @var \FondOfSpryker\Glue\PriceListsRestApi\PriceListsRestApiDependencyProvider
     */
    protected $priceListsRestApiDependencyProvider;

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

        $this->priceListsRestApiDependencyProvider = new PriceListsRestApiDependencyProvider();
    }

    /**
     * @return void
     */
    public function testProvideDependencies(): void
    {
        $this->assertInstanceOf(
            Container::class,
            $this->priceListsRestApiDependencyProvider->provideDependencies(
                $this->containerMock
            )
        );
    }
}
