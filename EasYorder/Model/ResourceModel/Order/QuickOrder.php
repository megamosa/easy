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

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class QuickOrder extends AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('magoarab_easyorder_quickorder', 'entity_id');
    }

    /**
     * Get orders by status
     *
     * @param string $status
     * @return array
     */
    public function getOrdersByStatus($status)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable())
            ->where('status = ?', $status)
            ->order('created_at DESC');
            
        return $connection->fetchAll($select);
    }

    /**
     * Get orders by date range
     *
     * @param string $fromDate
     * @param string $toDate
     * @return array
     */
    public function getOrdersByDateRange($fromDate, $toDate)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable())
            ->where('created_at >= ?', $fromDate)
            ->where('created_at <= ?', $toDate)
            ->order('created_at DESC');
            
        return $connection->fetchAll($select);
    }

    /**
     * Get orders count by status
     *
     * @param string $status
     * @return int
     */
    public function getOrdersCountByStatus($status)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable(), 'COUNT(*)')
            ->where('status = ?', $status);
            
        return (int)$connection->fetchOne($select);
    }

    /**
     * Update order status
     *
     * @param int $orderId
     * @param string $status
     * @return int
     */
    public function updateOrderStatus($orderId, $status)
    {
        $connection = $this->getConnection();
        $data = [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        return $connection->update(
            $this->getMainTable(),
            $data,
            ['entity_id = ?' => $orderId]
        );
    }

    /**
     * Get total revenue
     *
     * @param string|null $fromDate
     * @param string|null $toDate
     * @return float
     */
    public function getTotalRevenue($fromDate = null, $toDate = null)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable(), 'SUM(total_amount)')
            ->where('status IN (?)', ['processing', 'complete', 'shipped']);
            
        if ($fromDate) {
            $select->where('created_at >= ?', $fromDate);
        }
        
        if ($toDate) {
            $select->where('created_at <= ?', $toDate);
        }
        
        return (float)$connection->fetchOne($select);
    }
}