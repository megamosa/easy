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

namespace MagoArab\EasYorder\Api\Data;

/**
 * Quick Order Data Interface
 */
interface QuickOrderInterface
{
    const ENTITY_ID = 'entity_id';
    const INCREMENT_ID = 'increment_id';
    const STORE_ID = 'store_id';
    const CUSTOMER_NAME = 'customer_name';
    const CUSTOMER_PHONE = 'customer_phone';
    const CUSTOMER_EMAIL = 'customer_email';
    const CUSTOMER_ADDRESS = 'customer_address';
    const CUSTOMER_CITY = 'customer_city';
    const CUSTOMER_COUNTRY = 'customer_country';
    const PROVINCE = 'province';
    const PRODUCT_ID = 'product_id';
    const PRODUCT_SKU = 'product_sku';
    const PRODUCT_NAME = 'product_name';
    const QTY = 'qty';
    const PRICE = 'price';
    const SHIPPING_COST = 'shipping_cost';
    const TAX_AMOUNT = 'tax_amount';
    const DISCOUNT_AMOUNT = 'discount_amount';
    const TOTAL_AMOUNT = 'total_amount';
    const CURRENCY_CODE = 'currency_code';
    const PAYMENT_METHOD = 'payment_method';
    const SHIPPING_METHOD = 'shipping_method';
    const STATUS = 'status';
    const NOTES = 'notes';
    const MAGENTO_ORDER_ID = 'magento_order_id';
    const MAGENTO_ORDER_INCREMENT_ID = 'magento_order_increment_id';
    const IP_ADDRESS = 'ip_address';
    const USER_AGENT = 'user_agent';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * Get entity ID
     *
     * @return int|null
     */
    public function getEntityId();

    /**
     * Set entity ID
     *
     * @param int $entityId
     * @return $this
     */
    public function setEntityId($entityId);

    /**
     * Get increment ID
     *
     * @return string|null
     */
    public function getIncrementId();

    /**
     * Set increment ID
     *
     * @param string $incrementId
     * @return $this
     */
    public function setIncrementId($incrementId);

    /**
     * Get store ID
     *
     * @return int|null
     */
    public function getStoreId();

    /**
     * Set store ID
     *
     * @param int $storeId
     * @return $this
     */
    public function setStoreId($storeId);

    /**
     * Get customer name
     *
     * @return string|null
     */
    public function getCustomerName();

    /**
     * Set customer name
     *
     * @param string $customerName
     * @return $this
     */
    public function setCustomerName($customerName);

    /**
     * Get customer phone
     *
     * @return string|null
     */
    public function getCustomerPhone();

    /**
     * Set customer phone
     *
     * @param string $customerPhone
     * @return $this
     */
    public function setCustomerPhone($customerPhone);

    /**
     * Get customer email
     *
     * @return string|null
     */
    public function getCustomerEmail();

    /**
     * Set customer email
     *
     * @param string $customerEmail
     * @return $this
     */
    public function setCustomerEmail($customerEmail);

    /**
     * Get customer address
     *
     * @return string|null
     */
    public function getCustomerAddress();

    /**
     * Set customer address
     *
     * @param string $customerAddress
     * @return $this
     */
    public function setCustomerAddress($customerAddress);

    /**
     * Get customer city
     *
     * @return string|null
     */
    public function getCustomerCity();

    /**
     * Set customer city
     *
     * @param string $customerCity
     * @return $this
     */
    public function setCustomerCity($customerCity);

    /**
     * Get customer country
     *
     * @return string|null
     */
    public function getCustomerCountry();

    /**
     * Set customer country
     *
     * @param string $customerCountry
     * @return $this
     */
    public function setCustomerCountry($customerCountry);

    /**
     * Get province
     *
     * @return string|null
     */
    public function getProvince();

    /**
     * Set province
     *
     * @param string $province
     * @return $this
     */
    public function setProvince($province);

    /**
     * Get product ID
     *
     * @return int|null
     */
    public function getProductId();

    /**
     * Set product ID
     *
     * @param int $productId
     * @return $this
     */
    public function setProductId($productId);

    /**
     * Get product SKU
     *
     * @return string|null
     */
    public function getProductSku();

    /**
     * Set product SKU
     *
     * @param string $productSku
     * @return $this
     */
    public function setProductSku($productSku);

    /**
     * Get product name
     *
     * @return string|null
     */
    public function getProductName();

    /**
     * Set product name
     *
     * @param string $productName
     * @return $this
     */
    public function setProductName($productName);

    /**
     * Get quantity
     *
     * @return float|null
     */
    public function getQty();

    /**
     * Set quantity
     *
     * @param float $qty
     * @return $this
     */
    public function setQty($qty);

    /**
     * Get price
     *
     * @return float|null
     */
    public function getPrice();

    /**
     * Set price
     *
     * @param float $price
     * @return $this
     */
    public function setPrice($price);

    /**
     * Get shipping cost
     *
     * @return float|null
     */
    public function getShippingCost();

    /**
     * Set shipping cost
     *
     * @param float $shippingCost
     * @return $this
     */
    public function setShippingCost($shippingCost);

    /**
     * Get tax amount
     *
     * @return float|null
     */
    public function getTaxAmount();

    /**
     * Set tax amount
     *
     * @param float $taxAmount
     * @return $this
     */
    public function setTaxAmount($taxAmount);

    /**
     * Get discount amount
     *
     * @return float|null
     */
    public function getDiscountAmount();

    /**
     * Set discount amount
     *
     * @param float $discountAmount
     * @return $this
     */
    public function setDiscountAmount($discountAmount);

    /**
     * Get total amount
     *
     * @return float|null
     */
    public function getTotalAmount();

    /**
     * Set total amount
     *
     * @param float $totalAmount
     * @return $this
     */
    public function setTotalAmount($totalAmount);

    /**
     * Get currency code
     *
     * @return string|null
     */
    public function getCurrencyCode();

    /**
     * Set currency code
     *
     * @param string $currencyCode
     * @return $this
     */
    public function setCurrencyCode($currencyCode);

    /**
     * Get payment method
     *
     * @return string|null
     */
    public function getPaymentMethod();

    /**
     * Set payment method
     *
     * @param string $paymentMethod
     * @return $this
     */
    public function setPaymentMethod($paymentMethod);

    /**
     * Get shipping method
     *
     * @return string|null
     */
    public function getShippingMethod();

    /**
     * Set shipping method
     *
     * @param string $shippingMethod
     * @return $this
     */
    public function setShippingMethod($shippingMethod);

    /**
     * Get status
     *
     * @return string|null
     */
    public function getStatus();

    /**
     * Set status
     *
     * @param string $status
     * @return $this
     */
    public function setStatus($status);

    /**
     * Get notes
     *
     * @return string|null
     */
    public function getNotes();

    /**
     * Set notes
     *
     * @param string $notes
     * @return $this
     */
    public function setNotes($notes);

    /**
     * Get Magento order ID
     *
     * @return int|null
     */
    public function getMagentoOrderId();

    /**
     * Set Magento order ID
     *
     * @param int $magentoOrderId
     * @return $this
     */
    public function setMagentoOrderId($magentoOrderId);

    /**
     * Get Magento order increment ID
     *
     * @return string|null
     */
    public function getMagentoOrderIncrementId();

    /**
     * Set Magento order increment ID
     *
     * @param string $magentoOrderIncrementId
     * @return $this
     */
    public function setMagentoOrderIncrementId($magentoOrderIncrementId);

    /**
     * Get IP address
     *
     * @return string|null
     */
    public function getIpAddress();

    /**
     * Set IP address
     *
     * @param string $ipAddress
     * @return $this
     */
    public function setIpAddress($ipAddress);

    /**
     * Get user agent
     *
     * @return string|null
     */
    public function getUserAgent();

    /**
     * Set user agent
     *
     * @param string $userAgent
     * @return $this
     */
    public function setUserAgent($userAgent);

    /**
     * Get created at
     *
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created at
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt);

    /**
     * Get updated at
     *
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set updated at
     *
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt);
}