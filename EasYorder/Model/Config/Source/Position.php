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

namespace MagoArab\EasYorder\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class Position implements ArrayInterface
{
    /**
     * Return array of options as value-label pairs
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'above_product_details', 'label' => __('أعلى تفاصيل المنتج')],
            ['value' => 'below_product_details', 'label' => __('أسفل تفاصيل المنتج')],
            ['value' => 'above_reviews', 'label' => __('أعلى التقييمات')],
            ['value' => 'below_reviews', 'label' => __('أسفل التقييمات')],
            ['value' => 'in_product_tabs', 'label' => __('في تبويبات المنتج')],
            ['value' => 'sidebar', 'label' => __('الشريط الجانبي')]
        ];
    }
}