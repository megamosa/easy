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

namespace MagoArab\EasYorder\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Shipping\Model\Config;
use Magento\Store\Model\StoreManagerInterface;

class ShippingMethods implements OptionSourceInterface
{
    /**
     * @var Config
     */
    protected $shippingConfig;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * ShippingMethods constructor.
     *
     * @param Config $shippingConfig
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Config $shippingConfig,
        StoreManagerInterface $storeManager
    ) {
        $this->shippingConfig = $shippingConfig;
        $this->storeManager = $storeManager;
    }

    /**
     * Get options array
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];
        $store = $this->storeManager->getStore();
        $carriers = $this->shippingConfig->getActiveCarriers($store);
        
        foreach ($carriers as $carrierCode => $carrierModel) {
            if ($carrierMethods = $carrierModel->getAllowedMethods()) {
                $carrierTitle = $this->shippingConfig->getCarrierTitle($carrierCode, $store);
                
                foreach ($carrierMethods as $methodCode => $methodTitle) {
                    $options[] = [
                        'value' => $carrierCode . '_' . $methodCode,
                        'label' => $carrierTitle . ' - ' . $methodTitle
                    ];
                }
            }
        }
        
        return $options;
    }
}