<?php

namespace Creativestyle\DailyDealExtension\Setup;

class UpgradeData implements \Magento\Framework\Setup\UpgradeDataInterface
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

    public function upgrade(
        \Magento\Framework\Setup\ModuleDataSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    )
    {
        if (version_compare($context->getVersion(), '0.0.2', '<')) {
            $this->upgradeToVersion002();
        }

        if (version_compare($context->getVersion(), '0.0.3', '<')) {
            $this->upgradeToVersion003();
        }
    }

    protected function upgradeToVersion002()
    {
        $entityType = $this->eavSetup->getEntityTypeId('catalog_product');

        $this->eavSetup->updateAttribute($entityType,'daily_deal_from','note','Daily deal offer from. Use 5 min interval when setting time e.g. 8:30, 8:35, 9:40.');
        $this->eavSetup->updateAttribute($entityType,'daily_deal_to','note','Daily deal offer to. Use 5 min interval when setting time e.g. 8:30, 8:35, 9:40.');
    }

    protected function upgradeToVersion003()
    {
        $entityType = $this->eavSetup->getEntityTypeId('catalog_product');

        $this->eavSetup->updateAttribute($entityType,'daily_deal_enabled','is_searchable',true);
        $this->eavSetup->updateAttribute($entityType,'daily_deal_to','used_for_sort_by',true);
    }
}