<?php

namespace Creativestyle\DailyDealExtension\Helper;

class OfferData extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Creativestyle\DailyDealExtension\Helper\Configuration
     */
    protected $configuration;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface;
     */
    protected $productRepository;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $dateTime;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Creativestyle\DailyDealExtension\Helper\Configuration $configuration,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
    ) {
        parent::__construct($context);

        $this->configuration = $configuration;
        $this->productRepository = $productRepository;
        $this->dateTime = $dateTime;
    }

    public function isOfferEnabled($product)
    {
        $product = $this->getProduct($product);

        if(!$product->getId()){
            return false;
        }

        $offerEnabled = (boolean)$product->getDailyDealEnabled();

        if (!$offerEnabled) {
            return false;
        }

        $offerTo = $product->getDailyDealTo();

        return $this->dateTime->gmtTimestamp() < strtotime($offerTo);
    }

    public function prepareOfferData($product)
    {
        $isActive = $this->configuration->isActive();

        if(!$isActive){
            return false;
        }

        $product = $this->getProduct($product);

        if(!$product){
            return false;
        }

        $isQtyLimitationEnabled = $this->configuration->isQtyLimitationEnabled();

        return [
            'deal' => $this->isOfferEnabled($product),
            'items' => $isQtyLimitationEnabled ? $this->getOfferLimit($product) : 0,
            'from' => strtotime($product->getDailyDealFrom()),
            'to' => strtotime($product->getDailyDealTo()),
            'price' => $product->getDailyDealPrice(),
            'displayType' => $this->displayOnTile()
        ];
    }

    public function displayOnTile()
    {
        return $this->configuration->displayOnTile();
    }

    private function getOfferLimit($product)
    {
        $offerLimit = $product->getDailyDealLimit();
        $quantityAndStockStatus = $product->getQuantityAndStockStatus();

        if(!$quantityAndStockStatus){
            return $offerLimit;
        }

        $qty = isset($quantityAndStockStatus['qty']) ? $quantityAndStockStatus['qty'] : null;

        if($qty === null or $qty < 0){
            return $offerLimit;
        }

        return min($qty, $offerLimit);
    }

    private function getProduct($product)
    {
        if ($product instanceof \Magento\Catalog\Api\Data\ProductInterface) {
            return $product;
        }

        if (!is_int($product) and !is_string($product)) {
            return null;
        }
        
        try {
            $product = $this->productRepository->getById($product);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            return null;
        }

        return $product;
    }
}
