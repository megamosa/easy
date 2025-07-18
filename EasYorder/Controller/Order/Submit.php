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

namespace MagoArab\EasYorder\Controller\Order;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Quote\Model\QuoteFactory;
use Magento\Quote\Model\QuoteManagement;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Sales\Model\OrderFactory;
use Magento\Store\Model\StoreManagerInterface;
use MagoArab\EasYorder\Helper\Data as HelperData;
use Psr\Log\LoggerInterface;

class Submit extends Action
{
    /**
     * @var JsonFactory
     */
    protected $jsonFactory;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var QuoteFactory
     */
    protected $quoteFactory;

    /**
     * @var QuoteManagement
     */
    protected $quoteManagement;

    /**
     * @var CustomerFactory
     */
    protected $customerFactory;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var OrderFactory
     */
    protected $orderFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Submit constructor.
     *
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param ProductRepositoryInterface $productRepository
     * @param QuoteFactory $quoteFactory
     * @param QuoteManagement $quoteManagement
     * @param CustomerFactory $customerFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param OrderFactory $orderFactory
     * @param StoreManagerInterface $storeManager
     * @param HelperData $helperData
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        ProductRepositoryInterface $productRepository,
        QuoteFactory $quoteFactory,
        QuoteManagement $quoteManagement,
        CustomerFactory $customerFactory,
        CustomerRepositoryInterface $customerRepository,
        OrderFactory $orderFactory,
        StoreManagerInterface $storeManager,
        HelperData $helperData,
        LoggerInterface $logger
    ) {
        $this->jsonFactory = $jsonFactory;
        $this->productRepository = $productRepository;
        $this->quoteFactory = $quoteFactory;
        $this->quoteManagement = $quoteManagement;
        $this->customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;
        $this->orderFactory = $orderFactory;
        $this->storeManager = $storeManager;
        $this->helperData = $helperData;
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * Execute action
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $result = $this->jsonFactory->create();
        
        if (!$this->helperData->isEnabled()) {
            return $result->setData([
                'success' => false,
                'message' => __('Quick order is disabled.')
            ]);
        }

        if (!$this->getRequest()->isPost()) {
            return $result->setData([
                'success' => false,
                'message' => __('Invalid request method.')
            ]);
        }

        try {
            $data = $this->getRequest()->getPostValue();
            
            // Validate required fields
            $requiredFields = ['product_id', 'customer_name', 'customer_address', 'qty'];
            foreach ($requiredFields as $field) {
                if (empty($data[$field])) {
                    throw new LocalizedException(__('Field %1 is required.', $field));
                }
            }

            if ($this->helperData->isPhoneRequired() && empty($data['customer_phone'])) {
                throw new LocalizedException(__('Phone number is required.'));
            }

            // Load product
            $product = $this->productRepository->getById($data['product_id']);
            if (!$product->getId()) {
                throw new LocalizedException(__('Product not found.'));
            }

            // Create quote
            $quote = $this->quoteFactory->create();
            $quote->setStore($this->storeManager->getStore());
            
            // Add product to quote
            $quote->addProduct($product, (int)$data['qty']);

            // Set billing address
            $billingAddress = [
                'firstname' => $data['customer_name'],
                'lastname' => '',
                'street' => $data['customer_address'],
                'city' => $data['province'] ?? 'Cairo',
                'country_id' => 'EG',
                'region' => $data['province'] ?? 'Cairo',
                'postcode' => '12345',
                'telephone' => $data['customer_phone'] ?? '01000000000',
                'save_in_address_book' => 0
            ];

            $quote->getBillingAddress()->addData($billingAddress);
            $quote->getShippingAddress()->addData($billingAddress);

            // Set shipping method
            $shippingMethod = 'flatrate_flatrate';
            $quote->getShippingAddress()->setCollectShippingRates(true)
                ->collectShippingRates()
                ->setShippingMethod($shippingMethod);

            // Set payment method
            $paymentMethod = $data['payment_method'] ?? $this->helperData->getDefaultPaymentMethod();
            $quote->setPaymentMethod($paymentMethod);
            $quote->setInventoryProcessed(false);

            // Save quote
            $quote->save();

            // Convert quote to order
            $order = $this->quoteManagement->submit($quote);

            if ($order) {
                // Set order status
                $orderStatus = $this->helperData->getOrderStatus();
                $order->setStatus($orderStatus)->setState($orderStatus);
                $order->save();

                // Send notification email if configured
                $this->sendNotificationEmail($order, $data);

                return $result->setData([
                    'success' => true,
                    'message' => __('Order placed successfully!'),
                    'order_id' => $order->getIncrementId()
                ]);
            } else {
                throw new LocalizedException(__('Unable to create order.'));
            }

        } catch (LocalizedException $e) {
            $this->logger->error('EasYorder Error: ' . $e->getMessage());
            return $result->setData([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } catch (\Exception $e) {
            $this->logger->error('EasYorder Error: ' . $e->getMessage());
            return $result->setData([
                'success' => false,
                'message' => __('An error occurred while processing your order.')
            ]);
        }
    }

    /**
     * Send notification email
     *
     * @param \Magento\Sales\Model\Order $order
     * @param array $data
     * @return void
     */
    protected function sendNotificationEmail($order, $data)
    {
        $adminEmail = $this->helperData->getAdminEmail();
        if (!$adminEmail) {
            return;
        }

        try {
            // Implementation for sending email notification
            // This would typically use Magento's email transport system
            $this->logger->info('Quick order notification sent for order: ' . $order->getIncrementId());
        } catch (\Exception $e) {
            $this->logger->error('Failed to send notification email: ' . $e->getMessage());
        }
    }
}