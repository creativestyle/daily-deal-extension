<?php

namespace Creativestyle\DailyDealExtension\Test\Integration\Helper;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class OfferDataTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Creativestyle\DailyDealExtension\Helper\OfferData
     *
     */
    protected $offerDataHelper;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->offerDataHelper = $this->objectManager->get(\Creativestyle\DailyDealExtension\Helper\OfferData::class);
    }

    /**
     * @magentoAppArea frontend
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     * @magentoDataFixture loadProducts
     * @magentoConfigFixture current_store daily_deal/general/active 1
     * @magentoConfigFixture current_store daily_deal/general/use_qty_limitation 1
     */
    public function testItReturnEnabledDeal()
    {
        $offerData = $this->offerDataHelper->prepareOfferData(600);

        $expectedData = [
            'deal' => true,
            'items' => '50',
            'from' => 1521417600,
            'to' => 1931932800,
            'price' => '5.0000',
            'displayType' => 'none'
        ];

        $this->assertEquals($expectedData, $offerData);
    }

    /**
     * @magentoAppArea frontend
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     * @magentoDataFixture loadProducts
     * @magentoConfigFixture current_store daily_deal/general/active 1
     * @magentoConfigFixture current_store daily_deal/general/use_qty_limitation 1
     */
    public function testItReturnDisabledDeal()
    {
        $offerData = $this->offerDataHelper->prepareOfferData(601);

        $expectedData = [
            'deal' => false,
            'items' => '2',
            'from' => 1520726400,
            'to' => 1521187200,
            'price' => '1.0000',
            'displayType' => 'none'
        ];

        $this->assertEquals($expectedData, $offerData);
    }

    /**
     * @magentoAppArea frontend
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     * @magentoDataFixture loadProducts
     * @magentoConfigFixture current_store daily_deal/general/active 1
     * @magentoConfigFixture current_store daily_deal/general/use_qty_limitation 0
     */
    public function testItReturnCorrectDataWithDisabledLimit()
    {
        $offerData = $this->offerDataHelper->prepareOfferData(600);

        $expectedData = [
            'deal' => true,
            'items' => 0,
            'from' => 1521417600,
            'to' => 1931932800,
            'price' => '5.0000',
            'displayType' => 'none'
        ];

        $this->assertEquals($expectedData, $offerData);
    }

    public static function loadProducts()
    {
        require __DIR__ . '/../_files/products.php';
    }

    public static function loadProductsRollback()
    {
        require __DIR__ . '/../_files/products_rollback.php';
    }
}