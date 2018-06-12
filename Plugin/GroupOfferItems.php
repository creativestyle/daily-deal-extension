<?php

namespace Creativestyle\DailyDealExtension\Plugin;

class GroupOfferItems
{
    /**
     * @var \Creativestyle\DailyDealExtension\Helper\Configuration
     */
    protected $configuration;

    /**
     * @var \Creativestyle\DailyDealExtension\Service\OfferManagerInterface
     */
    protected $offerManager;

    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    private $serializer;


    public function __construct(
        \Creativestyle\DailyDealExtension\Helper\Configuration $configuration,
        \Creativestyle\DailyDealExtension\Service\OfferManagerInterface $offerManager,
        \Magento\Framework\Serialize\SerializerInterface $serializer
    )
    {
        $this->configuration = $configuration;
        $this->offerManager = $offerManager;
        $this->serializer = $serializer;
    }

    public function aroundRepresentProduct(\Magento\Quote\Model\Quote\Item $subject, callable $proceed, $product)
    {
        if(!$this->configuration->isActive()){
            return $proceed($product);
        }

        if(!$this->offerManager->getOfferPrice($product->getId())){
            return $proceed($product);
        }

        if($subject->getProductId() != $product->getId()){
            return $proceed($product);
        }

        $customOption = \Creativestyle\DailyDealExtension\Service\OfferManager::ITEM_OPTION_DD_OFFER;

        $offerItemOption = $product->getCustomOption($customOption);

        if($offerItemOption){
            $itemOption = $subject->getOptionByCode($customOption);

            if(!$itemOption){
                return $proceed($product);
            }

            return $itemOption->getValue() == $offerItemOption->getValue() ? true : false;
        }

        $product->addCustomOption(
            $customOption,
            true
        );

        return $proceed($product);
    }

}