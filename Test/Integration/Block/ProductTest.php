<?php

namespace Creativestyle\DailyDealExtension\Test\Integration\Block;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class ProductTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var \Creativestyle\DailyDealExtension\Block\Product
     */
    protected $productBlock;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->coreRegistry = $this->objectManager->get(\Magento\Framework\Registry::class);
        $this->productRepository = $this->objectManager->get(\Magento\Catalog\Api\ProductRepositoryInterface::class);
        $this->productBlock = $this->objectManager->get(\Creativestyle\DailyDealExtension\Block\Product::class);
    }

    /**
     * @magentoAppArea frontend
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     * @magentoDataFixture loadProducts
     * @magentoConfigFixture current_store daily_deal/general/active 1
     * @magentoConfigFixture current_store daily_deal/general/use_qty_limitation 1
     */
    public function testItReturnCorrectData()
    {
        $product = $this->productRepository->get('active_offer');
        $this->coreRegistry->register('product', $product);

        $excepted = [
            'deal' => true,
            'items' => '50',
            'from' => 1521417600,
            'to' => 1931932800,
            'price' => '5.0000',
            'displayType' => 'none'
        ];

        $offerData = $this->productBlock->getOfferData();

        $this->assertEquals($excepted, $offerData);
    }

    /**
     * @magentoAppArea frontend
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     * @magentoConfigFixture current_store daily_deal/general/active 1
     */
    public function testItReturnsFalseWhenNoCurrentProductIsRegistered()
    {
        $this->coreRegistry->register('product', null);

        $offerData = $this->productBlock->getOfferData();

        $this->assertFalse($offerData);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDataFixture loadProducts
     * @magentoConfigFixture current_store daily_deal/general/active 0
     */
    public function testItReturnsFalseIfDailyDealIsNotActive()
    {
        $product = $this->productRepository->get('active_offer');

        $this->coreRegistry->register('product', $product);

        $offerData = $this->productBlock->getOfferData();

        $this->assertFalse($offerData);
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