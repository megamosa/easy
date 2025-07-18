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

namespace MagoArab\EasYorder\Api;

/**
 * Quick Order API Interface
 */
interface QuickOrderInterface
{
    /**
     * Submit quick order
     *
     * @param \MagoArab\EasYorder\Api\Data\QuickOrderInterface $quickOrder
     * @return \MagoArab\EasYorder\Api\Data\QuickOrderResponseInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function submitOrder(\MagoArab\EasYorder\Api\Data\QuickOrderInterface $quickOrder);

    /**
     * Get quick order by ID
     *
     * @param int $quickOrderId
     * @return \MagoArab\EasYorder\Api\Data\QuickOrderInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getQuickOrder($quickOrderId);

    /**
     * Get quick orders list
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \MagoArab\EasYorder\Api\Data\QuickOrderSearchResultInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Update quick order status
     *
     * @param int $quickOrderId
     * @param string $status
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function updateStatus($quickOrderId, $status);

    /**
     * Estimate shipping cost
     *
     * @param int $productId
     * @param string $country
     * @param string $region
     * @param string $postcode
     * @param int $qty
     * @return \MagoArab\EasYorder\Api\Data\ShippingEstimateInterface
     */
    public function estimateShipping($productId, $country, $region, $postcode, $qty = 1);

    /**
     * Get available payment methods
     *
     * @param int|null $storeId
     * @return \MagoArab\EasYorder\Api\Data\PaymentMethodInterface[]
     */
    public function getAvailablePaymentMethods($storeId = null);

    /**
     * Get available shipping methods
     *
     * @param int $productId
     * @param string $country
     * @param string $region
     * @param string $postcode
     * @param int|null $storeId
     * @return \MagoArab\EasYorder\Api\Data\ShippingMethodInterface[]
     */
    public function getAvailableShippingMethods($productId, $country, $region, $postcode, $storeId = null);
}