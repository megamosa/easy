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

namespace MagoArab\EasYorder\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Catalog\Model\Product;
use Magento\Directory\Model\Config\Source\Country;
use Magento\Payment\Model\Config;
use Magento\Store\Model\StoreManagerInterface;
use MagoArab\EasYorder\Helper\Data as HelperData;

class QuickOrder extends Template
{
    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @var Country
     */
    protected $countrySource;

    /**
     * @var Config
     */
    protected $paymentConfig;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * QuickOrder constructor.
     *
     * @param Context $context
     * @param HelperData $helperData
     * @param Country $countrySource
     * @param Config $paymentConfig
     * @param StoreManagerInterface $storeManager
     * @param array $data
     */
    public function __construct(
        Context $context,
        HelperData $helperData,
        Country $countrySource,
        Config $paymentConfig,
        StoreManagerInterface $storeManager,
        array $data = []
    ) {
        $this->helperData = $helperData;
        $this->countrySource = $countrySource;
        $this->paymentConfig = $paymentConfig;
        $this->storeManager = $storeManager;
        parent::__construct($context, $data);
    }

    /**
     * Check if module is enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->helperData->isEnabled();
    }

    /**
     * Get current product
     *
     * @return Product
     */
    public function getCurrentProduct()
    {
        return $this->getData('product');
    }

    /**
     * Get quick order title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->helperData->getTitle();
    }

    /**
     * Check if shipping should be shown
     *
     * @return bool
     */
    public function showShipping()
    {
        return $this->helperData->showShipping();
    }

    /**
     * Get default payment method
     *
     * @return string
     */
    public function getDefaultPaymentMethod()
    {
        return $this->helperData->getDefaultPaymentMethod();
    }

    /**
     * Check if phone is required
     *
     * @return bool
     */
    public function isPhoneRequired()
    {
        return $this->helperData->isPhoneRequired();
    }

    /**
     * Get allowed countries
     *
     * @return array
     */
    public function getAllowedCountries()
    {
        $allowedCountries = $this->helperData->getAllowedCountries();
        $countries = $this->countrySource->toOptionArray();
        
        $filteredCountries = [];
        foreach ($countries as $country) {
            if (in_array($country['value'], $allowedCountries)) {
                $filteredCountries[] = $country;
            }
        }
        
        return $filteredCountries;
    }

    /**
     * Get available payment methods
     *
     * @return array
     */
    public function getAvailablePaymentMethods()
    {
        $activeMethods = $this->paymentConfig->getActiveMethods();
        $methods = [];
        
        foreach ($activeMethods as $methodCode => $methodModel) {
            $methods[] = [
                'code' => $methodCode,
                'title' => $methodModel->getTitle()
            ];
        }
        
        return $methods;
    }

    /**
     * Get button color
     *
     * @return string
     */
    public function getButtonColor()
    {
        return $this->helperData->getButtonColor();
    }

    /**
     * Get custom CSS
     *
     * @return string
     */
    public function getCustomCss()
    {
        return $this->helperData->getCustomCss();
    }

    /**
     * Get quick order URL
     *
     * @return string
     */
    public function getQuickOrderUrl()
    {
        return $this->getUrl('easyorder/order/submit');
    }

    /**
     * Get estimate shipping URL
     *
     * @return string
     */
    public function getEstimateShippingUrl()
    {
        return $this->getUrl('easyorder/order/estimate');
    }

    /**
     * Get current store currency symbol
     *
     * @return string
     */
    public function getCurrentCurrencySymbol()
    {
        return $this->storeManager->getStore()->getCurrentCurrencyCode();
    }

    /**
     * Get formatted price
     *
     * @param float $price
     * @return string
     */
    public function getFormattedPrice($price)
    {
        return $this->storeManager->getStore()->formatPrice($price);
    }
}