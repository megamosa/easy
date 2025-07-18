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
use Magento\Store\Model\StoreManagerInterface;
use MagoArab\EasYorder\Helper\Data as HelperData;
use Psr\Log\LoggerInterface;

class Estimate extends Action
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
     * Estimate constructor.
     *
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param ProductRepositoryInterface $productRepository
     * @param StoreManagerInterface $storeManager
     * @param HelperData $helperData
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        ProductRepositoryInterface $productRepository,
        StoreManagerInterface $storeManager,
        HelperData $helperData,
        LoggerInterface $logger
    ) {
        $this->jsonFactory = $jsonFactory;
        $this->productRepository = $productRepository;
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
            
            if (empty($data['province'])) {
                throw new LocalizedException(__('Province is required.'));
            }

            $productId = $data['product_id'] ?? null;
            $province = $data['province'];
            
            // Load product if provided
            $product = null;
            if ($productId) {
                $product = $this->productRepository->getById($productId);
            }

            // Calculate shipping cost based on province
            $shippingCost = $this->calculateShippingCost($province, $product);
            
            return $result->setData([
                'success' => true,
                'shipping_cost' => $shippingCost,
                'formatted_shipping_cost' => $this->formatPrice($shippingCost),
                'province' => $province
            ]);

        } catch (LocalizedException $e) {
            $this->logger->error('EasYorder Estimate Error: ' . $e->getMessage());
            return $result->setData([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } catch (\Exception $e) {
            $this->logger->error('EasYorder Estimate Error: ' . $e->getMessage());
            return $result->setData([
                'success' => false,
                'message' => __('An error occurred while calculating shipping.')
            ]);
        }
    }

    /**
     * Calculate shipping cost based on province
     *
     * @param string $province
     * @param \Magento\Catalog\Model\Product|null $product
     * @return float
     */
    protected function calculateShippingCost($province, $product = null)
    {
        // Base shipping cost
        $baseShippingCost = 15;
        
        // Define shipping costs for different provinces in Egypt
        $provinceCosts = [
            'EG' => [
                'cairo' => 15,
                'giza' => 15,
                'alexandria' => 20,
                'luxor' => 25,
                'aswan' => 30,
                'port_said' => 25,
                'suez' => 20,
                'mansoura' => 20,
                'tanta' => 20,
                'zagazig' => 20,
                'ismailia' => 22,
                'damanhur' => 18,
                'beni_suef' => 22,
                'faiyum' => 20,
                'minya' => 25,
                'asyut' => 28,
                'sohag' => 30,
                'qena' => 28,
                'hurghada' => 35,
                'sharm_el_sheikh' => 40,
                'marsa_matrouh' => 35,
                'arish' => 30,
                'siwa' => 50
            ],
            'SA' => [
                'riyadh' => 50,
                'jeddah' => 55,
                'mecca' => 55,
                'medina' => 55,
                'dammam' => 60,
                'tabuk' => 65,
                'abha' => 60,
                'hail' => 60,
                'najran' => 65,
                'jazan' => 65
            ],
            'AE' => [
                'dubai' => 45,
                'abu_dhabi' => 45,
                'sharjah' => 45,
                'ajman' => 45,
                'ras_al_khaimah' => 50,
                'fujairah' => 50,
                'umm_al_quwain' => 50
            ],
            'KW' => [
                'kuwait_city' => 40,
                'hawalli' => 40,
                'farwaniya' => 40,
                'ahmadi' => 45,
                'jahra' => 45,
                'mubarak_al_kabeer' => 45
            ]
        ];

        // Get country from province if it contains country code
        $country = 'EG'; // Default to Egypt
        if (strpos($province, '_') !== false) {
            $parts = explode('_', $province);
            if (isset($provinceCosts[$parts[0]])) {
                $country = $parts[0];
                $province = $parts[1];
            }
        }

        // Normalize province name
        $province = strtolower(str_replace(' ', '_', $province));

        // Get shipping cost
        if (isset($provinceCosts[$country][$province])) {
            $shippingCost = $provinceCosts[$country][$province];
        } else {
            // Default shipping cost for unknown provinces
            $shippingCost = $baseShippingCost;
            
            // Add extra cost for international shipping
            if ($country !== 'EG') {
                $shippingCost += 30;
            }
        }

        // Apply product-specific shipping rules
        if ($product) {
            $weight = $product->getWeight();
            $price = $product->getFinalPrice();
            
            // Add extra cost for heavy products
            if ($weight > 5) {
                $shippingCost += ($weight - 5) * 3;
            }
            
            // Free shipping for high-value orders
            if ($price > 1000) {
                $shippingCost = max(0, $shippingCost - 10);
            }
        }

        return $shippingCost;
    }

    /**
     * Format price with currency
     *
     * @param float $price
     * @return string
     */
    protected function formatPrice($price)
    {
        return $this->storeManager->getStore()->formatPrice($price);
    }
}