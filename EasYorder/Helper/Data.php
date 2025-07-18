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

namespace MagoArab\EasYorder\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Directory\Model\Config\Source\Country;
use Magento\Directory\Model\CurrencyFactory;
use Magento\Payment\Model\Config as PaymentConfig;
use Magento\Shipping\Model\Config as ShippingConfig;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;

class Data extends AbstractHelper
{
    const XML_PATH_ENABLED = 'magoarab_easyorder/general/enabled';
    const XML_PATH_TITLE = 'magoarab_easyorder/general/title';
    const XML_PATH_SHOW_SHIPPING = 'magoarab_easyorder/general/show_shipping';
    const XML_PATH_DEFAULT_PAYMENT = 'magoarab_easyorder/general/default_payment_method';
    const XML_PATH_DEFAULT_SHIPPING = 'magoarab_easyorder/general/default_shipping_method';
    const XML_PATH_REQUIRE_PHONE = 'magoarab_easyorder/general/require_phone';
    const XML_PATH_REQUIRE_EMAIL = 'magoarab_easyorder/general/require_email';
    const XML_PATH_AUTO_CREATE_CUSTOMER = 'magoarab_easyorder/general/auto_create_customer';
    const XML_PATH_MIN_ORDER_AMOUNT = 'magoarab_easyorder/general/min_order_amount';
    const XML_PATH_MAX_ORDER_AMOUNT = 'magoarab_easyorder/general/max_order_amount';
    const XML_PATH_ALLOWED_CUSTOMER_GROUPS = 'magoarab_easyorder/general/allowed_customer_groups';

    // Multi-Country Settings
    const XML_PATH_USE_STORE_COUNTRIES = 'magoarab_easyorder/multicountry/use_store_countries';
    const XML_PATH_SPECIFIC_COUNTRIES = 'magoarab_easyorder/multicountry/specific_countries';
    const XML_PATH_CURRENCY_CONVERSION = 'magoarab_easyorder/multicountry/currency_conversion';
    const XML_PATH_DETECT_COUNTRY = 'magoarab_easyorder/multicountry/detect_country';

    // Design Settings
    const XML_PATH_BUTTON_COLOR = 'magoarab_easyorder/design/button_color';
    const XML_PATH_POSITION = 'magoarab_easyorder/design/position';
    const XML_PATH_SHOW_ON_CATEGORIES = 'magoarab_easyorder/design/show_on_categories';
    const XML_PATH_HIDE_FOR_GUEST = 'magoarab_easyorder/design/hide_for_guest';
    const XML_PATH_ENABLE_ANIMATIONS = 'magoarab_easyorder/design/enable_animations';
    const XML_PATH_CUSTOM_CSS = 'magoarab_easyorder/design/custom_css';

    // Notification Settings
    const XML_PATH_ADMIN_EMAIL = 'magoarab_easyorder/notifications/admin_email';
    const XML_PATH_ORDER_STATUS = 'magoarab_easyorder/notifications/order_status';
    const XML_PATH_SEND_CUSTOMER_EMAIL = 'magoarab_easyorder/notifications/send_customer_email';
    const XML_PATH_SEND_ADMIN_EMAIL = 'magoarab_easyorder/notifications/send_admin_email';
    const XML_PATH_SMS_NOTIFICATIONS = 'magoarab_easyorder/notifications/sms_notifications';

    // Advanced Settings
    const XML_PATH_ENABLE_LOGGING = 'magoarab_easyorder/advanced/enable_logging';
    const XML_PATH_RATE_LIMITING = 'magoarab_easyorder/advanced/rate_limiting';
    const XML_PATH_MAX_ORDERS_PER_HOUR = 'magoarab_easyorder/advanced/max_orders_per_hour';
    const XML_PATH_ENABLE_CAPTCHA = 'magoarab_easyorder/advanced/enable_captcha';
    const XML_PATH_INVENTORY_CHECK = 'magoarab_easyorder/advanced/inventory_check';

    /**
     * @var Country
     */
    protected $countrySource;

    /**
     * @var CurrencyFactory
     */
    protected $currencyFactory;

    /**
     * @var PaymentConfig
     */
    protected $paymentConfig;

    /**
     * @var ShippingConfig
     */
    protected $shippingConfig;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var RemoteAddress
     */
    protected $remoteAddress;

    /**
     * Data constructor.
     *
     * @param Context $context
     * @param Country $countrySource
     * @param CurrencyFactory $currencyFactory
     * @param PaymentConfig $paymentConfig
     * @param ShippingConfig $shippingConfig
     * @param StoreManagerInterface $storeManager
     * @param RemoteAddress $remoteAddress
     */
    public function __construct(
        Context $context,
        Country $countrySource,
        CurrencyFactory $currencyFactory,
        PaymentConfig $paymentConfig,
        ShippingConfig $shippingConfig,
        StoreManagerInterface $storeManager,
        RemoteAddress $remoteAddress
    ) {
        $this->countrySource = $countrySource;
        $this->currencyFactory = $currencyFactory;
        $this->paymentConfig = $paymentConfig;
        $this->shippingConfig = $shippingConfig;
        $this->storeManager = $storeManager;
        $this->remoteAddress = $remoteAddress;
        parent::__construct($context);
    }

    /**
     * Check if module is enabled
     *
     * @param int|null $storeId
     * @return bool
     */
    public function isEnabled($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get quick order title
     *
     * @param int|null $storeId
     * @return string
     */
    public function getTitle($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_TITLE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if shipping cost should be shown
     *
     * @param int|null $storeId
     * @return bool
     */
    public function showShipping($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_SHOW_SHIPPING,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get default payment method
     *
     * @param int|null $storeId
     * @return string
     */
    public function getDefaultPaymentMethod($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_DEFAULT_PAYMENT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get default shipping method
     *
     * @param int|null $storeId
     * @return string
     */
    public function getDefaultShippingMethod($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_DEFAULT_SHIPPING,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if phone number is required
     *
     * @param int|null $storeId
     * @return bool
     */
    public function isPhoneRequired($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_REQUIRE_PHONE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if email is required
     *
     * @param int|null $storeId
     * @return bool
     */
    public function isEmailRequired($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_REQUIRE_EMAIL,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if auto create customer is enabled
     *
     * @param int|null $storeId
     * @return bool
     */
    public function isAutoCreateCustomerEnabled($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_AUTO_CREATE_CUSTOMER,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get minimum order amount
     *
     * @param int|null $storeId
     * @return float
     */
    public function getMinOrderAmount($storeId = null)
    {
        return (float)$this->scopeConfig->getValue(
            self::XML_PATH_MIN_ORDER_AMOUNT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get maximum order amount
     *
     * @param int|null $storeId
     * @return float
     */
    public function getMaxOrderAmount($storeId = null)
    {
        return (float)$this->scopeConfig->getValue(
            self::XML_PATH_MAX_ORDER_AMOUNT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get allowed customer groups
     *
     * @param int|null $storeId
     * @return array
     */
    public function getAllowedCustomerGroups($storeId = null)
    {
        $groups = $this->scopeConfig->getValue(
            self::XML_PATH_ALLOWED_CUSTOMER_GROUPS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
        
        return $groups ? explode(',', $groups) : [];
    }

    /**
     * Check if use store countries
     *
     * @param int|null $storeId
     * @return bool
     */
    public function useStoreCountries($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_USE_STORE_COUNTRIES,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get allowed countries from store configuration
     *
     * @param int|null $storeId
     * @return array
     */
    public function getAllowedCountries($storeId = null)
    {
        if ($this->useStoreCountries($storeId)) {
            // Get allowed countries from general store configuration
            $allowedCountries = $this->scopeConfig->getValue(
                'general/country/allow',
                ScopeInterface::SCOPE_STORE,
                $storeId
            );
        } else {
            // Get specific countries from module configuration
            $allowedCountries = $this->scopeConfig->getValue(
                self::XML_PATH_SPECIFIC_COUNTRIES,
                ScopeInterface::SCOPE_STORE,
                $storeId
            );
        }

        if ($allowedCountries) {
            return explode(',', $allowedCountries);
        }

        // If no specific countries configured, get all countries
        $allCountries = $this->countrySource->toOptionArray();
        $countryCodes = [];
        
        foreach ($allCountries as $country) {
            if (!empty($country['value'])) {
                $countryCodes[] = $country['value'];
            }
        }
        
        return $countryCodes;
    }

    /**
     * Get all available countries with names
     *
     * @param int|null $storeId
     * @return array
     */
    public function getAllowedCountriesWithNames($storeId = null)
    {
        $allowedCountries = $this->getAllowedCountries($storeId);
        $allCountries = $this->countrySource->toOptionArray();
        
        $filteredCountries = [];
        foreach ($allCountries as $country) {
            if (in_array($country['value'], $allowedCountries)) {
                $filteredCountries[] = $country;
            }
        }
        
        return $filteredCountries;
    }

    /**
     * Check if currency conversion is enabled
     *
     * @param int|null $storeId
     * @return bool
     */
    public function isCurrencyConversionEnabled($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_CURRENCY_CONVERSION,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if country detection is enabled
     *
     * @param int|null $storeId
     * @return bool
     */
    public function isCountryDetectionEnabled($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_DETECT_COUNTRY,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Detect country from IP address
     *
     * @return string
     */
    public function detectCountryFromIP()
    {
        if (!$this->isCountryDetectionEnabled()) {
            return $this->getDefaultCountry();
        }

        try {
            $ip = $this->remoteAddress->getRemoteAddress();
            
            // Use a simple IP to country service (you can integrate with GeoIP or other services)
            $countryCode = $this->getCountryByIP($ip);
            
            if ($countryCode && $this->isCountrySupported($countryCode)) {
                return $countryCode;
            }
        } catch (\Exception $e) {
            $this->_logger->error('Country detection failed: ' . $e->getMessage());
        }

        return $this->getDefaultCountry();
    }

    /**
     * Get country by IP (simplified implementation)
     *
     * @param string $ip
     * @return string|null
     */
    protected function getCountryByIP($ip)
    {
        // This is a simplified implementation
        // In production, you should use a proper GeoIP service
        
        // Default country mapping for common IP ranges (example)
        $ipRanges = [
            '2.0.0.0/8' => 'EG',      // Egypt
            '5.0.0.0/8' => 'SA',      // Saudi Arabia
            '37.0.0.0/8' => 'AE',     // UAE
            '78.0.0.0/8' => 'KW',     // Kuwait
            '109.0.0.0/8' => 'QA',    // Qatar
        ];

        foreach ($ipRanges as $range => $country) {
            if ($this->ipInRange($ip, $range)) {
                return $country;
            }
        }

        return null;
    }

    /**
     * Check if IP is in range
     *
     * @param string $ip
     * @param string $range
     * @return bool
     */
    protected function ipInRange($ip, $range)
    {
        list($subnet, $mask) = explode('/', $range);
        return (ip2long($ip) & ~((1 << (32 - $mask)) - 1)) == ip2long($subnet);
    }

    /**
     * Get available payment methods for store
     *
     * @param int|null $storeId
     * @return array
     */
    public function getAvailablePaymentMethods($storeId = null)
    {
        $store = $this->storeManager->getStore($storeId);
        $activeMethods = $this->paymentConfig->getActiveMethods($store);
        $methods = [];
        
        foreach ($activeMethods as $methodCode => $methodModel) {
            if ($methodModel->isAvailable()) {
                $methods[] = [
                    'code' => $methodCode,
                    'title' => $methodModel->getTitle(),
                    'is_offline' => $methodModel->isOffline()
                ];
            }
        }
        
        return $methods;
    }

    /**
     * Get available shipping methods for store
     *
     * @param int|null $storeId
     * @return array
     */
    public function getAvailableShippingMethods($storeId = null)
    {
        $store = $this->storeManager->getStore($storeId);
        $carriers = $this->shippingConfig->getActiveCarriers($store);
        $methods = [];
        
        foreach ($carriers as $carrierCode => $carrierModel) {
            if ($carrierMethods = $carrierModel->getAllowedMethods()) {
                $carrierTitle = $this->shippingConfig->getCarrierTitle($carrierCode, $store);
                
                foreach ($carrierMethods as $methodCode => $methodTitle) {
                    $methods[] = [
                        'code' => $carrierCode . '_' . $methodCode,
                        'carrier_code' => $carrierCode,
                        'method_code' => $methodCode,
                        'carrier_title' => $carrierTitle,
                        'method_title' => $methodTitle,
                        'title' => $carrierTitle . ' - ' . $methodTitle
                    ];
                }
            }
        }
        
        return $methods;
    }

    /**
     * Get store currencies
     *
     * @param int|null $storeId
     * @return array
     */
    public function getStoreCurrencies($storeId = null)
    {
        $allowedCurrencies = $this->scopeConfig->getValue(
            'currency/options/allow',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        if ($allowedCurrencies) {
            return explode(',', $allowedCurrencies);
        }

        return ['USD']; // Default fallback
    }

    /**
     * Get current store currency
     *
     * @param int|null $storeId
     * @return string
     */
    public function getCurrentCurrency($storeId = null)
    {
        try {
            $store = $this->storeManager->getStore($storeId);
            return $store->getCurrentCurrencyCode();
        } catch (\Exception $e) {
            return 'USD';
        }
    }

    /**
     * Get base currency
     *
     * @param int|null $storeId
     * @return string
     */
    public function getBaseCurrency($storeId = null)
    {
        try {
            $store = $this->storeManager->getStore($storeId);
            return $store->getBaseCurrencyCode();
        } catch (\Exception $e) {
            return 'USD';
        }
    }

    /**
     * Convert currency
     *
     * @param float $amount
     * @param string $fromCurrency
     * @param string $toCurrency
     * @param int|null $storeId
     * @return float
     */
    public function convertCurrency($amount, $fromCurrency, $toCurrency, $storeId = null)
    {
        if (!$this->isCurrencyConversionEnabled($storeId)) {
            return $amount;
        }

        try {
            $store = $this->storeManager->getStore($storeId);
            $currency = $this->currencyFactory->create();
            $rate = $currency->load($fromCurrency)->getAnyRate($toCurrency);
            
            return $amount * $rate;
        } catch (\Exception $e) {
            return $amount; // Return original amount if conversion fails
        }
    }

    /**
     * Get button color
     *
     * @param int|null $storeId
     * @return string
     */
    public function getButtonColor($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_BUTTON_COLOR,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get display position
     *
     * @param int|null $storeId
     * @return string
     */
    public function getPosition($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_POSITION,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get categories to show on
     *
     * @param int|null $storeId
     * @return array
     */
    public function getShowOnCategories($storeId = null)
    {
        $categories = $this->scopeConfig->getValue(
            self::XML_PATH_SHOW_ON_CATEGORIES,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
        
        return $categories ? explode(',', $categories) : [];
    }

    /**
     * Check if should hide for guest users
     *
     * @param int|null $storeId
     * @return bool
     */
    public function hideForGuest($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_HIDE_FOR_GUEST,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if animations are enabled
     *
     * @param int|null $storeId
     * @return bool
     */
    public function areAnimationsEnabled($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLE_ANIMATIONS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get custom CSS
     *
     * @param int|null $storeId
     * @return string
     */
    public function getCustomCss($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CUSTOM_CSS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get admin email for notifications
     *
     * @param int|null $storeId
     * @return string
     */
    public function getAdminEmail($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_ADMIN_EMAIL,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get default order status
     *
     * @param int|null $storeId
     * @return string
     */
    public function getOrderStatus($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_ORDER_STATUS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if customer email should be sent
     *
     * @param int|null $storeId
     * @return bool
     */
    public function shouldSendCustomerEmail($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_SEND_CUSTOMER_EMAIL,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if admin email should be sent
     *
     * @param int|null $storeId
     * @return bool
     */
    public function shouldSendAdminEmail($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_SEND_ADMIN_EMAIL,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if SMS notifications are enabled
     *
     * @param int|null $storeId
     * @return bool
     */
    public function isSmsNotificationEnabled($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_SMS_NOTIFICATIONS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if logging is enabled
     *
     * @param int|null $storeId
     * @return bool
     */
    public function isLoggingEnabled($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLE_LOGGING,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if rate limiting is enabled
     *
     * @param int|null $storeId
     * @return bool
     */
    public function isRateLimitingEnabled($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_RATE_LIMITING,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get max orders per hour
     *
     * @param int|null $storeId
     * @return int
     */
    public function getMaxOrdersPerHour($storeId = null)
    {
        return (int)$this->scopeConfig->getValue(
            self::XML_PATH_MAX_ORDERS_PER_HOUR,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if captcha is enabled
     *
     * @param int|null $storeId
     * @return bool
     */
    public function isCaptchaEnabled($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLE_CAPTCHA,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if inventory check is enabled
     *
     * @param int|null $storeId
     * @return bool
     */
    public function isInventoryCheckEnabled($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_INVENTORY_CHECK,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if country is supported for quick order
     *
     * @param string $countryCode
     * @param int|null $storeId
     * @return bool
     */
    public function isCountrySupported($countryCode, $storeId = null)
    {
        $allowedCountries = $this->getAllowedCountries($storeId);
        return in_array($countryCode, $allowedCountries);
    }

    /**
     * Get default country for store
     *
     * @param int|null $storeId
     * @return string
     */
    public function getDefaultCountry($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'general/country/default',
            ScopeInterface::SCOPE_STORE,
            $storeId
        ) ?: 'US';
    }

    /**
     * Get store locale
     *
     * @param int|null $storeId
     * @return string
     */
    public function getStoreLocale($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'general/locale/code',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if RTL layout
     *
     * @param int|null $storeId
     * @return bool
     */
    public function isRtlLayout($storeId = null)
    {
        $locale = $this->getStoreLocale($storeId);
        $rtlLocales = ['ar_SA', 'ar_EG', 'ar_AE', 'ar_KW', 'ar_QA', 'ar_BH', 'ar_OM', 'ar_JO', 'ar_LB', 'he_IL', 'fa_IR', 'ur_PK'];
        
        return in_array($locale, $rtlLocales);
    }

    /**
     * Format price with currency
     *
     * @param float $price
     * @param string|null $currencyCode
     * @param int|null $storeId
     * @return string
     */
    public function formatPrice($price, $currencyCode = null, $storeId = null)
    {
        try {
            $store = $this->storeManager->getStore($storeId);
            if ($currencyCode) {
                $currency = $this->currencyFactory->create()->load($currencyCode);
                return $currency->format($price, [], false);
            }
            return $store->formatPrice($price);
        } catch (\Exception $e) {
            return number_format($price, 2);
        }
    }

    /**
     * Get currency symbol
     *
     * @param string|null $currencyCode
     * @param int|null $storeId
     * @return string
     */
    public function getCurrencySymbol($currencyCode = null, $storeId = null)
    {
        try {
            if (!$currencyCode) {
                $currencyCode = $this->getCurrentCurrency($storeId);
            }
            
            $currency = $this->currencyFactory->create()->load($currencyCode);
            return $currency->getCurrencySymbol();
        } catch (\Exception $e) {
            return '$'; // Default fallback
        }
    }

    /**
     * Get tax display settings
     *
     * @param int|null $storeId
     * @return array
     */
    public function getTaxDisplaySettings($storeId = null)
    {
        return [
            'display_tax' => $this->scopeConfig->isSetFlag(
                'tax/display/type',
                ScopeInterface::SCOPE_STORE,
                $storeId
            ),
            'display_both' => $this->scopeConfig->getValue(
                'tax/display/type',
                ScopeInterface::SCOPE_STORE,
                $storeId
            ) == 3,
            'include_tax' => $this->scopeConfig->isSetFlag(
                'tax/calculation/price_includes_tax',
                ScopeInterface::SCOPE_STORE,
                $storeId
            )
        ];
    }
}