<?php

namespace Creativestyle\DailyDealExtension\Observer;

class DecreaseOfferUsage implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Creativestyle\DailyDealExtension\Helper\Configuration
     */
    protected $configuration;

    /**
     * @var \Creativestyle\DailyDealExtension\Service\OfferManagerInterface
     */
    protected $offerManager;

    public function __construct(
        \Creativestyle\DailyDealExtension\Helper\Configuration $configuration,
        \Creativestyle\DailyDealExtension\Service\OfferManagerInterface $offerManager
    ) {
        $this->configuration = $configuration;
        $this->offerManager = $offerManager;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $isQtyLimitationEnabled = $this->configuration->isQtyLimitationEnabled();

        if(!$isQtyLimitationEnabled){
            return $this;
        }

        $order = $observer->getEvent()->getOrder();

        foreach($order->getAllItems() as $item){
            if ($item->getParentItem()) {
                continue;
            }

            $buyRequest = $item->getProductOptionByCode('info_buyRequest');
            $offerKey = \Creativestyle\DailyDealExtension\Service\OfferManager::ITEM_OPTION_DD_OFFER;

            if(!isset($buyRequest[$offerKey]) or !$buyRequest[$offerKey]) {
                continue;
            }

            $this->offerManager->decreaseOfferLimit($item->getProductId(), $item->getQtyOrdered());
        }

        return $this;
    }
}