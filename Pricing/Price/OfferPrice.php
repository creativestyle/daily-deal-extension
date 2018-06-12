<?php

namespace Creativestyle\DailyDealExtension\Pricing\Price;

use Magento\Catalog\Model\Product;

class OfferPrice extends \Magento\Framework\Pricing\Price\AbstractPrice implements \Magento\Framework\Pricing\Price\BasePriceProviderInterface
{
    /**
     * Price type
     */
    const PRICE_CODE = 'offer_price';

    /**
     * @var \Creativestyle\DailyDealExtension\Helper\Configuration
     */
    protected $configuration;

    /**
     * @var \Creativestyle\DailyDealExtension\Service\OfferManagerInterface
     */
    protected $offerManager;

    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    public function __construct(
        \Magento\Framework\Pricing\SaleableInterface $saleableItem,
        $quantity,
        \Magento\Framework\Pricing\Adjustment\CalculatorInterface $calculator,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Creativestyle\DailyDealExtension\Helper\Configuration $configuration,
        \Creativestyle\DailyDealExtension\Service\OfferManagerInterface $offerManager,
        \Magento\Framework\Registry $registry
    )
    {
        parent::__construct($saleableItem, $quantity, $calculator, $priceCurrency);

        $this->configuration = $configuration;
        $this->offerManager = $offerManager;
        $this->registry = $registry;
    }

    public function getValue()
    {
        $isActive = $this->configuration->isActive();

        if(!$isActive){
            return false;
        }

        if ($this->value === null) {
            $price = $this->getOfferPrice();
            $priceInCurrentCurrency = $this->priceCurrency->convertAndRound($price);
            $this->value = $priceInCurrentCurrency ? floatval($priceInCurrentCurrency) : false;
        }

        return $this->value;
    }

    public function getOfferPrice()
    {
        if (!$this->value) {

            $productId = $this->product->getId();
            $parentId = $this->offerManager->getProductParentId($productId);

            $productId = $parentId ? $parentId : $productId;

            if(!$productId){
                $this->value = false;
                return $this->value;
            }

            $offerPrice = $this->offerManager->getOfferPrice($productId);
            $priceInCurrentCurrency = $this->priceCurrency->convertAndRound($offerPrice);

            $this->value = $priceInCurrentCurrency ? floatval($priceInCurrentCurrency) : false;
        }

        return $this->value;
    }
}