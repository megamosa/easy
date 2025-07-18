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

namespace MagoArab\EasYorder\Model\Order;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;

class QuickOrder extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'magoarab_easyorder_quickorder';

    /**
     * @var string
     */
    protected $_cacheTag = 'magoarab_easyorder_quickorder';

    /**
     * @var string
     */
    protected $_eventPrefix = 'magoarab_easyorder_quickorder';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\MagoArab\EasYorder\Model\ResourceModel\Order\QuickOrder::class);
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Get default values
     *
     * @return array
     */
    public function getDefaultValues()
    {
        $values = [];
        $values['status'] = 'pending';
        $values['created_at'] = date('Y-m-d H:i:s');
        
        return $values;
    }

    /**
     * Get customer data
     *
     * @return array
     */
    public function getCustomerData()
    {
        return [
            'name' => $this->getCustomerName(),
            'phone' => $this->getCustomerPhone(),
            'email' => $this->getCustomerEmail(),
            'address' => $this->getCustomerAddress(),
            'city' => $this->getCustomerCity(),
            'country' => $this->getCustomerCountry(),
            'province' => $this->getProvince()
        ];
    }

    /**
     * Get order data
     *
     * @return array
     */
    public function getOrderData()
    {
        return [
            'product_id' => $this->getProductId(),
            'product_sku' => $this->getProductSku(),
            'product_name' => $this->getProductName(),
            'qty' => $this->getQty(),
            'price' => $this->getPrice(),
            'shipping_cost' => $this->getShippingCost(),
            'total_amount' => $this->getTotalAmount(),
            'currency_code' => $this->getCurrencyCode(),
            'payment_method' => $this->getPaymentMethod(),
            'shipping_method' => $this->getShippingMethod()
        ];
    }

    /**
     * Set order status
     *
     * @param string $status
     * @return $this
     */
    public function setOrderStatus($status)
    {
        return $this->setData('status', $status);
    }

    /**
     * Get order status
     *
     * @return string
     */
    public function getOrderStatus()
    {
        return $this->getData('status');
    }

    /**
     * Check if order is processed
     *
     * @return bool
     */
    public function isProcessed()
    {
        return in_array($this->getOrderStatus(), ['processing', 'complete', 'shipped']);
    }

    /**
     * Check if order is pending
     *
     * @return bool
     */
    public function isPending()
    {
        return $this->getOrderStatus() === 'pending';
    }

    /**
     * Get formatted total amount
     *
     * @return string
     */
    public function getFormattedTotalAmount()
    {
        return number_format($this->getTotalAmount(), 2) . ' ' . $this->getCurrencyCode();
    }
}