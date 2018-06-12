<?php

namespace Creativestyle\DailyDealExtension\Plugin;

class DailyDealSaleOriginPlugin
{
    const DISCOUNT_TYPE_DAILY_DEAL = 'daily_deal';

    /**
     * @var \Creativestyle\DailyDealExtension\Service\OfferManagerInterface
     */
    protected $offerManager;

    public function __construct(
        \Creativestyle\DailyDealExtension\Service\OfferManagerInterface $offerManager
    ) {
        $this->offerManager = $offerManager;
    }

    public function aroundGetSaleOrigin(\Creativestyle\FrontendExtension\Helper\Product $subject, callable $proceed, $product)
    {
        $offerPrice = $this->offerManager->getOfferPrice($product->getId());

        if(!$offerPrice){
            return $proceed($product);
        }

        $specialPrice = $product->getSpecialPrice();

        if (!$specialPrice || $specialPrice >= $offerPrice) {
            return self::DISCOUNT_TYPE_DAILY_DEAL;
        }

        return $proceed($product);

    }
}