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

namespace MagoArab\EasYorder\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use MagoArab\EasYorder\Model\Order\QuickOrderFactory;
use MagoArab\EasYorder\Helper\Data as HelperData;
use Psr\Log\LoggerInterface;

class OrderSubmitObserver implements ObserverInterface
{
    /**
     * @var QuickOrderFactory
     */
    protected $quickOrderFactory;

    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * OrderSubmitObserver constructor.
     *
     * @param QuickOrderFactory $quickOrderFactory
     * @param HelperData $helperData
     * @param LoggerInterface $logger
     */
    public function __construct(
        QuickOrderFactory $quickOrderFactory,
        HelperData $helperData,
        LoggerInterface $logger
    ) {
        $this->quickOrderFactory = $quickOrderFactory;
        $this->helperData = $helperData;
        $this->logger = $logger;
    }

    /**
     * Execute observer
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        if (!$this->helperData->isEnabled()) {
            return;
        }

        try {
            $order = $observer->getEvent()->getOrder();
            $formData = $observer->getEvent()->getFormData();

            if (!$order || !$formData) {
                return;
            }

            // Create quick order record
            $quickOrder = $this->quickOrderFactory->create();
            $quickOrder->setData([
                'increment_id' => $this->generateIncrementId(),
                'store_id' => $order->getStoreId(),
                'customer_name' => $formData['customer_name'] ?? '',
                'customer_phone' => $formData['customer_phone'] ?? '',
                'customer_email' => $formData['customer_email'] ?? '',
                'customer_address' => $formData['customer_address'] ?? '',
                'customer_city' => $formData['customer_city'] ?? '',
                'customer_country' => $formData['customer_country'] ?? 'EG',
                'province' => $formData['province'] ?? '',
                'product_id' => $formData['product_id'] ?? 0,
                'product_sku' => $order->getAllItems()[0]->getSku() ?? '',
                'product_name' => $order->getAllItems()[0]->getName() ?? '',
                'qty' => $formData['qty'] ?? 1,
                'price' => $order->getAllItems()[0]->getPrice() ?? 0,
                'shipping_cost' => $order->getShippingAmount() ?? 0,
                'tax_amount' => $order->getTaxAmount() ?? 0,
                'discount_amount' => $order->getDiscountAmount() ?? 0,
                'total_amount' => $order->getGrandTotal() ?? 0,
                'currency_code' => $order->getOrderCurrencyCode() ?? 'USD',
                'payment_method' => $formData['payment_method'] ?? '',
                'shipping_method' => $formData['shipping_method'] ?? '',
                'status' => 'processing',
                'magento_order_id' => $order->getId(),
                'magento_order_increment_id' => $order->getIncrementId(),
                'ip_address' => $this->getClientIpAddress(),
                'user_agent' => $this->getUserAgent()
            ]);

            $quickOrder->save();

            // Log successful creation
            $this->logger->info('Quick order created successfully', [
                'quick_order_id' => $quickOrder->getId(),
                'magento_order_id' => $order->getId(),
                'increment_id' => $quickOrder->getIncrementId()
            ]);

        } catch (\Exception $e) {
            $this->logger->error('Error creating quick order record: ' . $e->getMessage(), [
                'exception' => $e
            ]);
        }
    }

    /**
     * Generate increment ID for quick order
     *
     * @return string
     */
    protected function generateIncrementId()
    {
        return 'QO' . date('Ymd') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
    }

    /**
     * Get client IP address
     *
     * @return string
     */
    protected function getClientIpAddress()
    {
        $ipKeys = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'];
        
        foreach ($ipKeys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '';
    }

    /**
     * Get user agent
     *
     * @return string
     */
    protected function getUserAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? '';
    }
}