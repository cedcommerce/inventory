<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\InventorySales\Test\Integration\IsProductSalableForRequestedQty;

use Magento\InventoryConfigurationApi\Api\GetStockItemConfigurationInterface;
use Magento\InventoryConfigurationApi\Api\SaveStockItemConfigurationInterface;
use Magento\InventorySalesApi\Api\IsProductSalableForRequestedQtyInterface;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;

class IsCorrectQtyConditionTest extends TestCase
{
    /**
     * @var GetStockItemConfigurationInterface
     */
    private $getStockItemConfig;

    /**
     * @var SaveStockItemConfigurationInterface
     */
    private $saveStockItemConfig;

    /**
     * @var IsProductSalableForRequestedQtyInterface
     */
    private $isProductSalableForRequestedQty;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->getStockItemConfig = Bootstrap::getObjectManager()->get(GetStockItemConfigurationInterface::class);
        $this->saveStockItemConfig = Bootstrap::getObjectManager()->get(SaveStockItemConfigurationInterface::class);
        $this->isProductSalableForRequestedQty
            = Bootstrap::getObjectManager()->get(IsProductSalableForRequestedQtyInterface::class);
    }

    /**
     * @magentoDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/products.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/sources.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/stocks.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/source_items.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/stock_source_links.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryIndexer/Test/_files/reindex_inventory.php
     * @dataProvider executeWithMissingConfigurationDataProvider
     */
    public function testExecuteWithMissingConfiguration($sku, $stockId, $requestedQty, bool $expectedResult)
    {
        $result = $this->isProductSalableForRequestedQty->execute($sku, $stockId, $requestedQty);
        $this->assertEquals($expectedResult, $result->isSalable());
    }

    public function executeWithMissingConfigurationDataProvider(): array
    {
        return [
            ['SKU-2', 10, 1, false],
        ];
    }
    
    public function testExecuteWithUseConfigMinSaleQty()
    {
        $this->markTestIncomplete('Still to implement');
    }

    public function testExecuteWithMinSaleQty()
    {
        $this->markTestIncomplete('Still to implement');
    }

    public function testExecuteWithUseConfigMaxSaleQty()
    {
        $this->markTestIncomplete('Still to implement');
    }

    public function testExecuteWithMaxSaleQty()
    {
        $this->markTestIncomplete('Still to implement');
    }

    /**
     * @magentoDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/products.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/sources.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/stocks.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/source_items.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/stock_source_links.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryIndexer/Test/_files/reindex_inventory.php
     * @magentoConfigFixture current_store cataloginventory/item_options/enable_qty_increments 1
     * @magentoConfigFixture current_store cataloginventory/item_options/qty_increments 3
     *
     * @param string $sku
     * @param int $stockId
     * @param int $requestedQty
     * @param bool $expectedResult
     *
     * @return void
     *
     * @dataProvider executeWithUseConfigMaxSaleQtyDataProvider
     */
    public function testExecuteWithUseConfigQtyIncrements(
        string $sku,
        int $stockId,
        int $requestedQty,
        bool $expectedResult
    ): void {
        $result = $this->isProductSalableForRequestedQty->execute($sku, $stockId, $requestedQty);
        $this->assertEquals($expectedResult, $result->isSalable());
    }

    /**
     * @return array
     */
    public function executeWithUseConfigMaxSaleQtyDataProvider(): array
    {
        return [
            ['SKU-1', 10, 1, false],
            ['SKU-1', 10, 3, true],
            ['SKU-1', 10, 6, true],
            ['SKU-1', 10, 9, false],
            ['SKU-2', 10, 1, false],
            ['SKU-2', 10, 3, false],
            ['SKU-3', 10, 1, false],
            ['SKU-3', 10, 3, false],
            ['SKU-1', 20, 1, false],
            ['SKU-1', 20, 3, false],
            ['SKU-2', 20, 1, false],
            ['SKU-2', 20, 3, true],
            ['SKU-2', 20, 6, false],
            ['SKU-3', 20, 1, false],
            ['SKU-3', 20, 3, false],
            ['SKU-1', 30, 1, false],
            ['SKU-1', 30, 3, true],
            ['SKU-1', 30, 6, true],
            ['SKU-1', 30, 9, false],
            ['SKU-2', 30, 1, false],
            ['SKU-2', 30, 3, true],
            ['SKU-2', 30, 6, false],
            ['SKU-3', 30, 1, false],
            ['SKU-3', 30, 3, false],
        ];
    }

    public function testExecuteWithQtyIncrements()
    {
        $this->markTestIncomplete('Still to implement');
    }
}
