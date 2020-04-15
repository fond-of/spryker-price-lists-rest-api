<?php

namespace FondOfSpryker\Glue\PriceListsRestApi\Plugin;

use Codeception\Test\Unit;
use FondOfSpryker\Glue\PriceListsRestApi\PriceListsRestApiConfig;
use Generated\Shared\Transfer\RestPriceListAttributesTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface;

class PriceListResourceRoutePluginTest extends Unit
{
    /**
     * @var \FondOfSpryker\Glue\PriceListsRestApi\Plugin\PriceListResourceRoutePlugin
     */
    protected $priceListResourceRoutePlugin;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface
     */
    protected $resourceRouteCollectionInterfaceMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->resourceRouteCollectionInterfaceMock = $this->getMockBuilder(ResourceRouteCollectionInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceListResourceRoutePlugin = new PriceListResourceRoutePlugin();
    }

    /**
     * @return void
     */
    public function testConfigure(): void
    {
        $this->assertInstanceOf(
            ResourceRouteCollectionInterface::class,
            $this->priceListResourceRoutePlugin->configure(
                $this->resourceRouteCollectionInterfaceMock
            )
        );
    }

    /**
     * @return void
     */
    public function testGetResourceType(): void
    {
        $this->assertSame(
            PriceListsRestApiConfig::RESOURCE_PRICE_LISTS,
            $this->priceListResourceRoutePlugin->getResourceType()
        );
    }

    /**
     * @return void
     */
    public function testGetController(): void
    {
        $this->assertSame(
            PriceListsRestApiConfig::CONTROLLER_PRICE_LISTS,
            $this->priceListResourceRoutePlugin->getController()
        );
    }

    /**
     * @return void
     */
    public function testGetResourceAttributesClassName(): void
    {
        $this->assertSame(
            RestPriceListAttributesTransfer::class,
            $this->priceListResourceRoutePlugin->getResourceAttributesClassName()
        );
    }
}
