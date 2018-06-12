<?php

namespace Creativestyle\DailyDealExtension\Setup;

class InstallData implements \Magento\Framework\Setup\InstallDataInterface
{
    /**
     * @var \Magento\Eav\Setup\EavSetup
     */
    protected $eavSetup;

    /**
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    protected $eavSetupFactory;

    /**
     * @var \Magento\Framework\Setup\ModuleDataSetupInterface
     */
    protected $moduleDataSetupInterface;

    public function __construct(
        \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory,
        \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetupInterface
    )
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->moduleDataSetupInterface = $moduleDataSetupInterface;

        $this->eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetupInterface]);
    }

    public function install(
        \Magento\Framework\Setup\ModuleDataSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    )
    {
        $this->eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'daily_deal_price',
            [
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'type' => 'decimal',
                'unique' => false,
                'label' => 'Offer price',
                'input' => 'price',
                'source' => '',
                'group' => 'Daily Deal',
                'required' => false,
                'sort_order' => 10,
                'user_defined' => 1,
                'searchable' => false,
                'filterable' => false,
                'filterable_in_search' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'note' => 'Daily deal offer price'
            ]
        );

        $this->eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'daily_deal_from',
            [
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'type' => 'datetime',
                'unique' => false,
                'label' => 'Offer from',
                'input' => 'date',
                'source' => '',
                'group' => 'Daily Deal',
                'required' => false,
                'sort_order' => 20,
                'user_defined' => 1,
                'searchable' => false,
                'filterable' => false,
                'filterable_in_search' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'note' => 'Daily deal offer from'
            ]
        );

        $this->eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'daily_deal_to',
            [
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'type' => 'datetime',
                'unique' => false,
                'label' => 'Offer to',
                'input' => 'date',
                'source' => '',
                'group' => 'Daily Deal',
                'required' => false,
                'sort_order' => 30,
                'user_defined' => 1,
                'searchable' => false,
                'filterable' => false,
                'filterable_in_search' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'note' => 'Daily deal offer to'
            ]
        );

        $this->eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'daily_deal_limit',
            [
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'type' => 'int',
                'unique' => false,
                'label' => 'Offer limit',
                'input' => 'text',
                'source' => '',
                'group' => 'Daily Deal',
                'required' => false,
                'sort_order' => 40,
                'user_defined' => 1,
                'searchable' => false,
                'filterable' => false,
                'filterable_in_search' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'note' => 'Daily deal offer limit'
            ]
        );

        $this->eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'daily_deal_enabled',
            [
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'type' => 'int',
                'unique' => false,
                'label' => 'Enabled',
                'input' => 'boolean',
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'group' => 'Daily Deal',
                'required' => false,
                'sort_order' => 50,
                'user_defined' => 1,
                'searchable' => false,
                'filterable' => false,
                'filterable_in_search' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true
            ]
        );
    }
}