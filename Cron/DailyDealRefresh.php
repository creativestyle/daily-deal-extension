<?php
namespace Creativestyle\DailyDealExtension\Cron;

class DailyDealRefresh
{
    /**
     * @var \Creativestyle\DailyDealExtension\Helper\Configuration
     */
    protected $configuration;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Creativestyle\DailyDealExtension\Service\OfferManagerInterface
     */
    protected $offerManager;

    public function __construct(
        \Creativestyle\DailyDealExtension\Helper\Configuration $configuration,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Creativestyle\DailyDealExtension\Service\OfferManagerInterface $offerManager
    )
    {
        $this->configuration = $configuration;
        $this->storeManager = $storeManager;
        $this->offerManager = $offerManager;
    }

    public function execute()
    {
        $isActive = $this->configuration->isActive();

        if(!$isActive){
            return false;
        }

        $stores = $this->storeManager->getStores(true);

        $storeIds = array_keys($stores);
        sort($storeIds);

        foreach($storeIds as $storeId){
            $this->offerManager->refreshOffers($storeId);
        }

        return true;
    }
}