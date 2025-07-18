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

namespace MagoArab\EasYorder\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * Install schema
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        /**
         * Create table 'magoarab_easyorder_quickorder'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('magoarab_easyorder_quickorder'))
            ->addColumn(
                'entity_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true],
                'Entity ID'
            )
            ->addColumn(
                'increment_id',
                Table::TYPE_TEXT,
                32,
                ['nullable' => false],
                'Increment ID'
            )
            ->addColumn(
                'store_id',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Store ID'
            )
            ->addColumn(
                'customer_name',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Customer Name'
            )
            ->addColumn(
                'customer_phone',
                Table::TYPE_TEXT,
                50,
                ['nullable' => true],
                'Customer Phone'
            )
            ->addColumn(
                'customer_email',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Customer Email'
            )
            ->addColumn(
                'customer_address',
                Table::TYPE_TEXT,
                '64k',
                ['nullable' => false],
                'Customer Address'
            )
            ->addColumn(
                'customer_city',
                Table::TYPE_TEXT,
                100,
                ['nullable' => true],
                'Customer City'
            )
            ->addColumn(
                'customer_country',
                Table::TYPE_TEXT,
                2,
                ['nullable' => false, 'default' => 'EG'],
                'Customer Country'
            )
            ->addColumn(
                'province',
                Table::TYPE_TEXT,
                100,
                ['nullable' => true],
                'Province/State'
            )
            ->addColumn(
                'product_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Product ID'
            )
            ->addColumn(
                'product_sku',
                Table::TYPE_TEXT,
                64,
                ['nullable' => false],
                'Product SKU'
            )
            ->addColumn(
                'product_name',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Product Name'
            )
            ->addColumn(
                'qty',
                Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => false, 'default' => '1.0000'],
                'Quantity'
            )
            ->addColumn(
                'price',
                Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => false, 'default' => '0.0000'],
                'Product Price'
            )
            ->addColumn(
                'shipping_cost',
                Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => false, 'default' => '0.0000'],
                'Shipping Cost'
            )
            ->addColumn(
                'tax_amount',
                Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => false, 'default' => '0.0000'],
                'Tax Amount'
            )
            ->addColumn(
                'discount_amount',
                Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => false, 'default' => '0.0000'],
                'Discount Amount'
            )
            ->addColumn(
                'total_amount',
                Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => false, 'default' => '0.0000'],
                'Total Amount'
            )
            ->addColumn(
                'currency_code',
                Table::TYPE_TEXT,
                3,
                ['nullable' => false, 'default' => 'USD'],
                'Currency Code'
            )
            ->addColumn(
                'payment_method',
                Table::TYPE_TEXT,
                50,
                ['nullable' => false],
                'Payment Method'
            )
            ->addColumn(
                'shipping_method',
                Table::TYPE_TEXT,
                50,
                ['nullable' => false],
                'Shipping Method'
            )
            ->addColumn(
                'status',
                Table::TYPE_TEXT,
                20,
                ['nullable' => false, 'default' => 'pending'],
                'Order Status'
            )
            ->addColumn(
                'notes',
                Table::TYPE_TEXT,
                '64k',
                ['nullable' => true],
                'Order Notes'
            )
            ->addColumn(
                'magento_order_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => true],
                'Magento Order ID'
            )
            ->addColumn(
                'magento_order_increment_id',
                Table::TYPE_TEXT,
                32,
                ['nullable' => true],
                'Magento Order Increment ID'
            )
            ->addColumn(
                'ip_address',
                Table::TYPE_TEXT,
                45,
                ['nullable' => true],
                'Customer IP Address'
            )
            ->addColumn(
                'user_agent',
                Table::TYPE_TEXT,
                500,
                ['nullable' => true],
                'User Agent'
            )
            ->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Created At'
            )
            ->addColumn(
                'updated_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
                'Updated At'
            )
            ->addIndex(
                $installer->getIdxName('magoarab_easyorder_quickorder', ['increment_id']),
                ['increment_id'],
                ['type' => 'unique']
            )
            ->addIndex(
                $installer->getIdxName('magoarab_easyorder_quickorder', ['store_id']),
                ['store_id']
            )
            ->addIndex(
                $installer->getIdxName('magoarab_easyorder_quickorder', ['customer_name']),
                ['customer_name']
            )
            ->addIndex(
                $installer->getIdxName('magoarab_easyorder_quickorder', ['customer_phone']),
                ['customer_phone']
            )
            ->addIndex(
                $installer->getIdxName('magoarab_easyorder_quickorder', ['customer_email']),
                ['customer_email']
            )
            ->addIndex(
                $installer->getIdxName('magoarab_easyorder_quickorder', ['customer_country']),
                ['customer_country']
            )
            ->addIndex(
                $installer->getIdxName('magoarab_easyorder_quickorder', ['product_id']),
                ['product_id']
            )
            ->addIndex(
                $installer->getIdxName('magoarab_easyorder_quickorder', ['product_sku']),
                ['product_sku']
            )
            ->addIndex(
                $installer->getIdxName('magoarab_easyorder_quickorder', ['status']),
                ['status']
            )
            ->addIndex(
                $installer->getIdxName('magoarab_easyorder_quickorder', ['payment_method']),
                ['payment_method']
            )
            ->addIndex(
                $installer->getIdxName('magoarab_easyorder_quickorder', ['shipping_method']),
                ['shipping_method']
            )
            ->addIndex(
                $installer->getIdxName('magoarab_easyorder_quickorder', ['created_at']),
                ['created_at']
            )
            ->addIndex(
                $installer->getIdxName('magoarab_easyorder_quickorder', ['updated_at']),
                ['updated_at']
            )
            ->addForeignKey(
                $installer->getFkName(
                    'magoarab_easyorder_quickorder',
                    'store_id',
                    'store',
                    'store_id'
                ),
                'store_id',
                $installer->getTable('store'),
                'store_id',
                Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName(
                    'magoarab_easyorder_quickorder',
                    'product_id',
                    'catalog_product_entity',
                    'entity_id'
                ),
                'product_id',
                $installer->getTable('catalog_product_entity'),
                'entity_id',
                Table::ACTION_CASCADE
            )
            ->setComment('MagoArab EasyOrder Quick Orders Table');

        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}