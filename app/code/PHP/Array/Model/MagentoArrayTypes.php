<?php

namespace PHP\Array\Model;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Customer\Api\Data\CustomerInterface;

/**
 * Magento Array Types Examples
 *
 * This class demonstrates different array types using real Magento data structures
 * that developers commonly work with in Magento 2 development.
 */
class MagentoArrayTypes
{
    /**
     * Indexed Array Examples
     *
     * Indexed arrays use numeric keys (0, 1, 2, ...) and are commonly used in Magento for:
     * - Lists of SKUs, IDs, or simple values
     * - Product attribute option values
     * - Store/website IDs
     * - Category paths
     *
     * @return array
     */
    public function indexedArrayExamples(): array
    {
        // Example 1: Product SKUs for bulk operations
        $productSkus = ['WS03', 'WS04', 'WS05', 'WH01', 'WH02'];

        // Add new SKU at the end
        $productSkus[] = 'WH03';

        // Add multiple SKUs using array_push()
        array_push($productSkus, 'WT01', 'WT02', 'WT03');

        // Update SKU at specific index
        $productSkus[0] = 'WS03-UPDATED';

        // Example 2: Store IDs for multi-store operations
        $storeIds = [1, 2, 3, 4]; // Default, Main Website Store, German Store, French Store

        // Example 3: Category IDs for product assignment
        $categoryIds = [2, 3, 4, 5, 11, 12]; // Root categories and subcategories

        // Example 4: Customer Group IDs
        $customerGroupIds = [0, 1, 2, 3]; // NOT LOGGED IN, General, Wholesale, Retailer

        // Example 5: Product Attribute Option Values (for color attribute)
        $colorOptions = ['Red', 'Blue', 'Green', 'Black', 'White'];

        return [
            'product_skus' => $productSkus,
            'store_ids' => $storeIds,
            'category_ids' => $categoryIds,
            'customer_group_ids' => $customerGroupIds,
            'color_options' => $colorOptions
        ];
    }

    /**
     * Associative Array Examples
     *
     * Associative arrays use string keys and are extensively used in Magento for:
     * - Product data
     * - Customer information
     * - Configuration values
     * - Request parameters
     * - API responses
     *
     * @return array
     */
    public function associativeArrayExamples(): array
    {
        // Example 1: Product Data (similar to what you get from ProductInterface)
        $productData = [
            'entity_id' => 1,
            'sku' => 'WS03',
            'name' => 'Cassius Sparring Tank',
            'price' => 29.00,
            'special_price' => 24.00,
            'type_id' => 'simple',
            'status' => 1, // Enabled
            'visibility' => 4, // Catalog, Search
            'weight' => 0.5,
            'created_at' => '2024-01-15 10:30:00',
            'updated_at' => '2024-07-12 14:20:00'
        ];

        // Add new attributes
        $productData['stock_status'] = 1; // In Stock
        //        $productData['qty'] => 100;
        $productData['meta_title'] = 'Best Sparring Tank for Training';

        // Example 2: Customer Data (from CustomerInterface)
        $customerData = [
            'entity_id' => 5,
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@example.com',
            'group_id' => 1, // General customer group
            'store_id' => 1,
            'website_id' => 1,
            'created_at' => '2024-03-10 09:15:00',
            'is_active' => 1,
            'gender' => 1 // Male
        ];

        // Update customer information
        $customerData['dob'] = '1990-05-15';
        $customerData['phone'] = '+1-555-123-4567';

        // Example 3: Store Configuration
        $storeConfig = [
            'store_code' => 'default',
            'store_name' => 'Main Website Store',
            'locale' => 'en_US',
            'currency' => 'USD',
            'timezone' => 'America/Chicago',
            'base_url' => 'https://example.com/',
            'secure_base_url' => 'https://example.com/',
            'allow_guest_checkout' => 1,
            'min_password_length' => 8
        ];

        // Example 4: Order Status Configuration
        $orderStatuses = [
            'pending' => 'Pending',
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'complete' => 'Complete',
            'canceled' => 'Canceled',
            'closed' => 'Closed',
            'refunded' => 'Refunded'
        ];

        return [
            'product_data' => $productData,
            'customer_data' => $customerData,
            'store_config' => $storeConfig,
            'order_statuses' => $orderStatuses
        ];
    }

    /**
     * Multidimensional Array Examples
     *
     * Multidimensional arrays contain arrays as values and are crucial in Magento for:
     * - Complex product data (configurable products with variants)
     * - Order data with items
     * - Category trees
     * - Customer addresses
     * - Shopping cart data
     *
     * @return array
     */
    public function multidimensionalArrayExamples(): array
    {
        // Example 1: Configurable Product with Variants
        $configurableProduct = [];
        $configurableProduct['entity_id'] = 50;
        $configurableProduct['sku'] = 'WS03-CONFIG';
        $configurableProduct['name'] = 'Cassius Sparring Tank';
        $configurableProduct['type_id'] = 'configurable';
        $configurableProduct['price'] = 29.00;

        // Add configurable options
        $configurableProduct['configurable_options'] = [
            'color' => [
                'attribute_id' => 93,
                'attribute_code' => 'color',
                'label' => 'Color',
                'values' => [
                    ['value_index' => 49, 'label' => 'Black'],
                    ['value_index' => 50, 'label' => 'Blue'],
                    ['value_index' => 51, 'label' => 'Red']
                ]
            ],
            'size' => [
                'attribute_id' => 142,
                'attribute_code' => 'size',
                'label' => 'Size',
                'values' => [
                    ['value_index' => 166, 'label' => 'XS'],
                    ['value_index' => 167, 'label' => 'S'],
                    ['value_index' => 168, 'label' => 'M'],
                    ['value_index' => 169, 'label' => 'L']
                ]
            ]
        ];

        // Add child products (variants)
        $configurableProduct['variants'] = [
            [
                'entity_id' => 51,
                'sku' => 'WS03-BLACK-XS',
                'price' => 29.00,
                'color' => 'Black',
                'size' => 'XS',
                'qty' => 25
            ],
            [
                'entity_id' => 52,
                'sku' => 'WS03-BLACK-S',
                'price' => 29.00,
                'color' => 'Black',
                'size' => 'S',
                'qty' => 30
            ],
            [
                'entity_id' => 53,
                'sku' => 'WS03-BLUE-M',
                'price' => 31.00,
                'color' => 'Blue',
                'size' => 'M',
                'qty' => 15
            ]
        ];

        // Example 2: Order with Items and Addresses
        $orderData = [];
        $orderData['entity_id'] = 100;
        $orderData['increment_id'] = '000000100';
        $orderData['status'] = 'processing';
        $orderData['state'] = 'processing';
        $orderData['customer_id'] = 5;
        $orderData['customer_email'] = 'john.doe@example.com';
        $orderData['created_at'] = '2024-07-12 15:30:00';

        // Add order totals
        $orderData['totals'] = [
            'subtotal' => 58.00,
            'shipping_amount' => 5.00,
            'tax_amount' => 4.64,
            'discount_amount' => -5.80,
            'grand_total' => 61.84
        ];

        // Add shipping address
        $orderData['shipping_address'] = [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'company' => 'Acme Corp',
            'street' => ['123 Main Street', 'Suite 100'],
            'city' => 'Austin',
            'region' => 'Texas',
            'region_id' => 57,
            'postcode' => '78701',
            'country_id' => 'US',
            'telephone' => '+1-555-123-4567'
        ];

        // Add billing address (same as shipping in this example)
        $orderData['billing_address'] = $orderData['shipping_address'];

        // Add order items
        $orderData['items'] = [
            [
                'item_id' => 150,
                'product_id' => 51,
                'sku' => 'WS03-BLACK-XS',
                'name' => 'Cassius Sparring Tank-XS-Black',
                'qty_ordered' => 1,
                'price' => 29.00,
                'row_total' => 29.00,
                'product_options' => [
                    'super_attribute' => [
                        93 => 49,  // color: Black
                        142 => 166 // size: XS
                    ]
                ]
            ],
            [
                'item_id' => 151,
                'product_id' => 53,
                'sku' => 'WS03-BLUE-M',
                'name' => 'Cassius Sparring Tank-M-Blue',
                'qty_ordered' => 1,
                'price' => 31.00,
                'row_total' => 31.00,
                'product_options' => [
                    'super_attribute' => [
                        93 => 50,  // color: Blue
                        142 => 168 // size: M
                    ]
                ]
            ]
        ];

        // Example 3: Category Tree Structure
        $categoryTree = [
            'entity_id' => 2,
            'name' => 'Default Category',
            'level' => 1,
            'path' => '1/2',
            'children' => [
                [
                    'entity_id' => 3,
                    'name' => 'Men',
                    'level' => 2,
                    'path' => '1/2/3',
                    'children' => [
                        [
                            'entity_id' => 11,
                            'name' => 'Tops',
                            'level' => 3,
                            'path' => '1/2/3/11',
                            'children' => [
                                [
                                    'entity_id' => 12,
                                    'name' => 'Jackets',
                                    'level' => 4,
                                    'path' => '1/2/3/11/12',
                                    'children' => []
                                ],
                                [
                                    'entity_id' => 13,
                                    'name' => 'Hoodies & Sweatshirts',
                                    'level' => 4,
                                    'path' => '1/2/3/11/13',
                                    'children' => []
                                ]
                            ]
                        ]
                    ]
                ],
                [
                    'entity_id' => 4,
                    'name' => 'Women',
                    'level' => 2,
                    'path' => '1/2/4',
                    'children' => [
                        [
                            'entity_id' => 20,
                            'name' => 'Tops',
                            'level' => 3,
                            'path' => '1/2/4/20',
                            'children' => []
                        ]
                    ]
                ]
            ]
        ];

        // Example 4: Customer with Multiple Addresses
        $customerWithAddresses = [
            'entity_id' => 5,
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@example.com',
            'addresses' => [
                [
                    'entity_id' => 10,
                    'firstname' => 'John',
                    'lastname' => 'Doe',
                    'company' => 'Acme Corp',
                    'street' => ['123 Main Street', 'Suite 100'],
                    'city' => 'Austin',
                    'region' => 'Texas',
                    'region_id' => 57,
                    'postcode' => '78701',
                    'country_id' => 'US',
                    'telephone' => '+1-555-123-4567',
                    'is_default_billing' => true,
                    'is_default_shipping' => true
                ],
                [
                    'entity_id' => 11,
                    'firstname' => 'John',
                    'lastname' => 'Doe',
                    'company' => 'Home Office',
                    'street' => ['456 Oak Avenue'],
                    'city' => 'Dallas',
                    'region' => 'Texas',
                    'region_id' => 57,
                    'postcode' => '75201',
                    'country_id' => 'US',
                    'telephone' => '+1-555-987-6543',
                    'is_default_billing' => false,
                    'is_default_shipping' => false
                ]
            ]
        ];

        return [
            'configurable_product' => $configurableProduct,
            'order_data' => $orderData,
            'category_tree' => $categoryTree,
            'customer_with_addresses' => $customerWithAddresses
        ];
    }

    /**
     * Advanced Array Operations for Magento Development
     *
     * Common array operations that Magento developers use frequently
     *
     * @return array
     */
    public function advancedArrayOperations(): array
    {
        // Working with product collections
        $products = [
            ['sku' => 'WS03', 'price' => 29.00, 'category_id' => 11, 'status' => 1],
            ['sku' => 'WS04', 'price' => 35.00, 'category_id' => 12, 'status' => 1],
            ['sku' => 'WS05', 'price' => 42.00, 'category_id' => 11, 'status' => 0],
        ];

        // Filter enabled products only
        $enabledProducts = array_filter($products, function ($product) {
            return $product['status'] == 1;
        });

        // Extract SKUs only
        $skus = array_column($products, 'sku');

        // Group products by category
        $productsByCategory = [];
        foreach ($products as $product) {
            $categoryId = $product['category_id'];
            if (!isset($productsByCategory[$categoryId])) {
                $productsByCategory[$categoryId] = [];
            }
            $productsByCategory[$categoryId][] = $product;
        }

        // Calculate total price of enabled products
        $totalPrice = array_sum(array_column($enabledProducts, 'price'));

        // Merge product data with additional attributes
        $additionalData = [
            'WS03' => ['weight' => 0.5, 'color' => 'Black'],
            'WS04' => ['weight' => 0.7, 'color' => 'Blue'],
            'WS05' => ['weight' => 0.6, 'color' => 'Red']
        ];

        $mergedProducts = [];
        foreach ($products as $product) {
            $sku = $product['sku'];
            if (isset($additionalData[$sku])) {
                $mergedProducts[] = array_merge($product, $additionalData[$sku]);
            } else {
                $mergedProducts[] = $product;
            }
        }

        return [
            'original_products' => $products,
            'enabled_products' => $enabledProducts,
            'skus_only' => $skus,
            'products_by_category' => $productsByCategory,
            'total_price' => $totalPrice,
            'merged_products' => $mergedProducts
        ];
    }

    /**
     * Array to Object Conversion (Common in Magento)
     *
     * Shows how to convert arrays to DataObjects, which is very common in Magento
     *
     * @return \Magento\Framework\DataObject
     */
    public function arrayToDataObject(): \Magento\Framework\DataObject
    {
        $productData = [
            'sku' => 'WS03',
            'name' => 'Cassius Sparring Tank',
            'price' => 29.00,
            'status' => 1
        ];

        // Convert array to DataObject (common Magento pattern)
        $productObject = new \Magento\Framework\DataObject($productData);

        // You can now use methods like:
        // $productObject->getSku()
        // $productObject->getName()
        // $productObject->setPrice(35.00)

        return $productObject;
    }
}
