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

namespace MagoArab\EasYorder\Model\ResourceModel\Order;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use MagoArab\EasYorder\Model\Order\QuickOrder as QuickOrderModel;
use MagoArab\EasYorder\Model\ResourceModel\Order\QuickOrder as QuickOrderResource;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * @var string
     */
    protected $_eventPrefix = 'magoarab_easyorder_quickorder_collection';

    /**
     * @var string
     */
    protected $_eventObject = 'quickorder_collection';

    /**
     * Initialize collection
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(QuickOrderModel::class, QuickOrderResource::class);
    }

    /**
     * Add status filter
     *
     * @param string|array $status
     * @return $this
     */
    public function addStatusFilter($status)
    {
        if (is_array($status)) {
            $this->addFieldToFilter('status', ['in' => $status]);
        } else {
            $this->addFieldToFilter('status', $status);
        }
        
        return $this;
    }

    /**
     * Add date range filter
     *
     * @param string $fromDate
     * @param string $toDate
     * @return $this
     */
    public function addDateRangeFilter($fromDate, $toDate)
    {
        $this->addFieldToFilter('created_at', ['from' => $fromDate, 'to' => $toDate]);
        return $this;
    }

    /**
     * Add customer filter
     *
     * @param string $customerName
     * @return $this
     */
    public function addCustomerFilter($customerName)
    {
        $this->addFieldToFilter('customer_name', ['like' => '%' . $customerName . '%']);
        return $this;
    }

    /**
     * Add product filter
     *
     * @param int $productId
     * @return $this
     */
    public function addProductFilter($productId)
    {
        $this->addFieldToFilter('product_id', $productId);
        return $this;
    }

    /**
     * Add country filter
     *
     * @param string $country
     * @return $this
     */
    public function addCountryFilter($country)
    {
        $this->addFieldToFilter('customer_country', $country);
        return $this;
    }

    /**
     * Add payment method filter
     *
     * @param string $paymentMethod
     * @return $this
     */
    public function addPaymentMethodFilter($paymentMethod)
    {
        $this->addFieldToFilter('payment_method', $paymentMethod);
        return $this;
    }

    /**
     * Get pending orders
     *
     * @return $this
     */
    public function getPendingOrders()
    {
        return $this->addStatusFilter('pending');
    }

    /**
     * Get processed orders
     *
     * @return $this
     */
    public function getProcessedOrders()
    {
        return $this->addStatusFilter(['processing', 'complete', 'shipped']);
    }

    /**
     * Get today's orders
     *
     * @return $this
     */
    public function getTodayOrders()
    {
        $today = date('Y-m-d');
        return $this->addDateRangeFilter($today . ' 00:00:00', $today . ' 23:59:59');
    }

    /**
     * Get orders by store
     *
     * @param int $storeId
     * @return $this
     */
    public function addStoreFilter($storeId)
    {
        $this->addFieldToFilter('store_id', $storeId);
        return $this;
    }

    /**
     * Get total amount sum
     *
     * @return float
     */
    public function getTotalAmountSum()
    {
        $this->getSelect()->reset(\Magento\Framework\DB\Select::COLUMNS);
        $this->getSelect()->columns(['total' => 'SUM(total_amount)']);
        return (float)$this->getConnection()->fetchOne($this->getSelect());
    }

    /**
     * Group by status
     *
     * @return $this
     */
    public function groupByStatus()
    {
        $this->getSelect()->group('status');
        return $this;
    }

    /**
     * Group by date
     *
     * @return $this
     */
    public function groupByDate()
    {
        $this->getSelect()->group('DATE(created_at)');
        return $this;
    }
}