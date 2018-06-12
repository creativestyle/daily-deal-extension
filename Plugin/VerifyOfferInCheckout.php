<?php

namespace Creativestyle\DailyDealExtension\Plugin;

class VerifyOfferInCheckout
{
    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @var \Creativestyle\DailyDealExtension\Helper\Configuration
     */
    protected $configuration;

    /**
     * @var \Creativestyle\DailyDealExtension\Service\OfferManagerInterface
     */
    private $offerManager;

    public function __construct(
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Creativestyle\DailyDealExtension\Helper\Configuration $configuration,
        \Creativestyle\DailyDealExtension\Service\OfferManagerInterface $offerManager
    )
    {
        $this->quoteRepository = $quoteRepository;
        $this->messageManager = $messageManager;
        $this->configuration = $configuration;
        $this->offerManager = $offerManager;
    }

    public function aroundPlaceOrder($subject, $proceed, $cartId, $paymentMethod = null)
    {
        $isActive = $this->configuration->isActive();

        if(!$isActive){
            return $proceed($cartId, $paymentMethod);
        }

        $quote = $this->quoteRepository->getActive($cartId);

        $validate = true;

        foreach($quote->getAllItems() as $item){
            if ($item->getParentItem()) {
                continue;
            }

            $option = $item->getOptionByCode(
                \Creativestyle\DailyDealExtension\Service\OfferManager::ITEM_OPTION_DD_OFFER
            );

            if(!$option or !$option->getValue()){
                continue;
            }

            $validate = $this->offerManager->validateOfferInQuote($item->getProductId(), $item->getQty());

            if(!$validate){
                break;
            }
        }

        if(!$validate){

            $this->offerManager->applyAction(
                $item->getProductId(),
                \Creativestyle\DailyDealExtension\Service\OfferManager::TYPE_REMOVE
            );

            $this->messageManager->addError(__('Offer is ended and product was removed from the cart.'));
            return false;
        }

        return $proceed($cartId, $paymentMethod);
    }
}