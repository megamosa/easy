<?php
/**
 * MagoArab_EasYorder
 *
 * @category    MagoArab
 * @package     MagoArab_EasYorder
 * @author      MagoArab Team
 * @copyright   Copyright (c) 2024 MagoArab (https://magoarab.com)
 * @license     https://opensource.org/licenses/mit-license.php MIT License
 */

namespace MagoArab\EasYorder\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;

class InstallData implements InstallDataInterface
{
    /**
     * @var WriterInterface
     */
    protected $configWriter;

    /**
     * InstallData constructor.
     *
     * @param WriterInterface $configWriter
     */
    public function __construct(WriterInterface $configWriter)
    {
        $this->configWriter = $configWriter;
    }

    /**
     * Installs data for the module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        // Set default configuration values
        $defaultConfig = [
            'magoarab_easyorder/general/enabled' => '1',
            'magoarab_easyorder/general/title' => 'طلب سريع',
            'magoarab_easyorder/general/show_shipping' => '1',
            'magoarab_easyorder/general/default_payment_method' => 'cashondelivery',
            'magoarab_easyorder/general/require_phone' => '1',
            'magoarab_easyorder/general/allowed_countries' => 'EG,SA,AE,KW,QA,BH,OM,JO,LB,SY,IQ,MA,TN,DZ,LY,SD,YE',
            'magoarab_easyorder/design/button_color' => '#007bff',
            'magoarab_easyorder/design/position' => 'below_product_details',
            'magoarab_easyorder/notifications/order_status' => 'pending'
        ];

        foreach ($defaultConfig as $path => $value) {
            $this->configWriter->save($path, $value);
        }

        $setup->endSetup();
    }
}