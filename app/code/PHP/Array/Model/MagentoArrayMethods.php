<?php

namespace PHP\Array\Model;

/**
 * Complete PHP Array Methods with Magento Examples
 *
 * This class demonstrates all PHP array functions with real Magento use cases
 * that developers encounter in day-to-day Magento development.
 */
class MagentoArrayMethods
{
    /**
     * ============================================================================
     * BASIC ARRAY INFORMATION METHODS
     * ============================================================================
     */

    /**
     * count() / sizeof() - Get array length
     * Common in Magento for: Counting products, orders, customers, etc.
     */
    public function countExamples(): array
    {
        $productSkus = ['WS03', 'WS04', 'WS05', 'WH01', 'WH02'];
        $orderItems = [
            ['sku' => 'WS03', 'qty' => 2],
            ['sku' => 'WH01', 'qty' => 1],
            ['sku' => 'WS05', 'qty' => 3]
        ];

        $customerAddresses = [
            ['type' => 'billing', 'city' => 'Austin'],
            ['type' => 'shipping', 'city' => 'Dallas'],
            ['type' => 'shipping', 'city' => 'Houston']
        ];

        return [
            'total_products' => count($productSkus), // 5
            'total_order_items' => count($orderItems), // 3
            'total_addresses' => sizeof($customerAddresses), // 3 (sizeof is alias)
            'multidimensional_count' => count($orderItems, COUNT_RECURSIVE) // 9 (includes sub-arrays)
        ];
    }

    /**
     * empty() / isset() - Check if array exists or is empty
     * Essential for validation in Magento forms and data processing
     */
    public function emptyIssetExamples(): array
    {
        $customerData = ['firstname' => 'John', 'lastname' => 'Doe', 'email' => ''];
        $productOptions = [];
        $orderData = null;

        return [
            'customer_email_empty' => empty($customerData['email']), // true
            'customer_firstname_exists' => isset($customerData['firstname']), // true
            'product_options_empty' => empty($productOptions), // true
            'phone_field_exists' => isset($customerData['phone']), // false
            'order_data_null' => is_null($orderData) // true
        ];
    }

    /**
     * ============================================================================
     * ADDING AND REMOVING ELEMENTS
     * ============================================================================
     */

    /**
     * array_push() / array_pop() - Add/remove elements from end
     * Used in: Shopping cart operations, product collections
     */
    public function pushPopExamples(): array
    {
        $cartItems = ['WS03', 'WH01'];

        // Add items to cart
        array_push($cartItems, 'WS05', 'WH02'); // ['WS03', 'WH01', 'WS05', 'WH02']

        // Remove last item from cart
        $removedItem = array_pop($cartItems); // 'WH02'

        // Add single item (alternative syntax)
        $cartItems[] = 'WT01';

        return [
            'cart_items' => $cartItems, // ['WS03', 'WH01', 'WS05', 'WT01']
            'removed_item' => $removedItem // 'WH02'
        ];
    }

    /**
     * array_unshift() / array_shift() - Add/remove elements from beginning
     * Used in: Priority ordering, recent items lists
     */
    public function unshiftShiftExamples(): array
    {
        $recentlyViewedProducts = ['WS04', 'WH01', 'WS05'];

        // Add new product to beginning (most recent)
        array_unshift($recentlyViewedProducts, 'WS03'); // ['WS03', 'WS04', 'WH01', 'WS05']

        // Remove oldest viewed product
        $oldestProduct = array_shift($recentlyViewedProducts); // 'WS03'

        // Add priority customer group at beginning
        $customerGroups = ['General', 'Wholesale', 'Retailer'];
        array_unshift($customerGroups, 'VIP'); // ['VIP', 'General', 'Wholesale', 'Retailer']

        return [
            'recently_viewed' => $recentlyViewedProducts,
            'oldest_product' => $oldestProduct,
            'customer_groups' => $customerGroups
        ];
    }

    /**
     * unset() - Remove specific array elements
     * Critical for: Removing sensitive data, cleaning arrays
     */
    public function unsetExamples(): array
    {
        $customerData = [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'secret123',
            'credit_card' => '4111-1111-1111-1111'
        ];

        // Remove sensitive data before logging
        unset($customerData['password']);
        unset($customerData['credit_card']);

        $productAttributes = [
            'name' => 'T-Shirt',
            'price' => 25.00,
            'internal_cost' => 10.00, // Internal data
            'supplier_info' => 'ABC Corp' // Internal data
        ];

        // Remove internal data before API response
        unset($productAttributes['internal_cost'], $productAttributes['supplier_info']);

        return [
            'safe_customer_data' => $customerData,
            'public_product_data' => $productAttributes
        ];
    }

    /**
     * ============================================================================
     * SEARCHING AND CHECKING
     * ============================================================================
     */

    /**
     * in_array() - Check if value exists in array
     * Essential for: Permission checks, validation, filtering
     */
    public function inArrayExamples(): array
    {
        $allowedCustomerGroups = [1, 2, 3]; // General, Wholesale, Retailer
        $enabledStoreIds = [1, 2, 3, 4];
        $validOrderStatuses = ['pending', 'processing', 'shipped', 'complete'];

        $currentCustomerGroup = 2;
        $requestedStoreId = 5;
        $orderStatus = 'processing';

        return [
            'customer_has_access' => in_array($currentCustomerGroup, $allowedCustomerGroups), // true
            'store_is_enabled' => in_array($requestedStoreId, $enabledStoreIds), // false
            'status_is_valid' => in_array($orderStatus, $validOrderStatuses), // true
            'strict_check' => in_array('2', $allowedCustomerGroups, true) // false (strict type check)
        ];
    }

    /**
     * array_search() / array_key_exists() - Find keys and check existence
     * Used for: Finding product positions, checking attribute existence
     */
    public function searchKeyExistsExamples(): array
    {
        $productPositions = ['WS03' => 1, 'WH01' => 2, 'WS05' => 3, 'WT01' => 4];
        $customerAttributes = [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john@example.com'
        ];

        $colorOptions = ['Red', 'Blue', 'Green', 'Black'];

        return [
            'product_position' => array_search('WS05', array_keys($productPositions)), // 2
            'color_position' => array_search('Green', $colorOptions), // 2
            'email_exists' => array_key_exists('email', $customerAttributes), // true
            'phone_exists' => array_key_exists('phone', $customerAttributes), // false
            'sku_position' => array_search(3, $productPositions) // 'WS05'
        ];
    }

    /**
     * ============================================================================
     * FILTERING AND MAPPING
     * ============================================================================
     */

    /**
     * array_filter() - Filter array elements
     * Extremely common in Magento for: Product filtering, data validation
     */
    public function filterExamples(): array
    {
        $products = [
            ['sku' => 'WS03', 'price' => 29.00, 'status' => 1, 'stock' => 50],
            ['sku' => 'WS04', 'price' => 35.00, 'status' => 0, 'stock' => 0],
            ['sku' => 'WS05', 'price' => 42.00, 'status' => 1, 'stock' => 25],
            ['sku' => 'WH01', 'price' => 15.00, 'status' => 1, 'stock' => 100]
        ];

        // Filter enabled products only
        $enabledProducts = array_filter($products, function($product) {
            return $product['status'] == 1;
        });

        // Filter products in stock
        $inStockProducts = array_filter($products, function($product) {
            return $product['stock'] > 0;
        });

        // Filter premium products (price > 30)
        $premiumProducts = array_filter($products, function($product) {
            return $product['price'] > 30;
        });

        // Filter non-empty customer data
        $customerData = ['firstname' => 'John', 'lastname' => '', 'email' => 'john@example.com', 'phone' => ''];
        $validCustomerData = array_filter($customerData); // Removes empty values

        return [
            'enabled_products' => array_values($enabledProducts), // Re-index array
            'in_stock_products' => array_values($inStockProducts),
            'premium_products' => array_values($premiumProducts),
            'valid_customer_data' => $validCustomerData
        ];
    }

    /**
     * array_map() - Apply function to all elements
     * Used for: Data transformation, formatting, API responses
     */
    public function mapExamples(): array
    {
        $productPrices = [29.00, 35.50, 42.75, 15.99];
        $customerEmails = ['JOHN@EXAMPLE.COM', 'jane@EXAMPLE.com', 'Bob@Example.COM'];
        $orderIds = [100, 101, 102, 103];

        // Format prices with currency
        $formattedPrices = array_map(function($price) {
            return '$' . number_format($price, 2);
        }, $productPrices);

        // Normalize email addresses
        $normalizedEmails = array_map('strtolower', $customerEmails);

        // Generate order increment IDs
        $incrementIds = array_map(function($id) {
            return sprintf('ORD-%08d', $id);
        }, $orderIds);

        // Apply tax to prices
        $taxRate = 0.08;
        $pricesWithTax = array_map(function($price) use ($taxRate) {
            return round($price * (1 + $taxRate), 2);
        }, $productPrices);

        return [
            'formatted_prices' => $formattedPrices,
            'normalized_emails' => $normalizedEmails,
            'increment_ids' => $incrementIds,
            'prices_with_tax' => $pricesWithTax
        ];
    }

    /**
     * ============================================================================
     * SORTING ARRAYS
     * ============================================================================
     */

    /**
     * sort() / rsort() - Sort arrays by values
     * Used for: Product sorting, price ordering
     */
    public function sortExamples(): array
    {
        $productPrices = [29.00, 15.99, 42.75, 35.50];
        $customerNames = ['John Doe', 'Alice Smith', 'Bob Johnson', 'Carol Wilson'];
        $orderDates = ['2024-03-15', '2024-01-10', '2024-07-20', '2024-02-05'];

        // Sort prices ascending
        $pricesAsc = $productPrices;
        sort($pricesAsc); // [15.99, 29.00, 35.50, 42.75]

        // Sort prices descending
        $pricesDesc = $productPrices;
        rsort($pricesDesc); // [42.75, 35.50, 29.00, 15.99]

        // Sort customer names alphabetically
        $namesAsc = $customerNames;
        sort($namesAsc); // ['Alice Smith', 'Bob Johnson', 'Carol Wilson', 'John Doe']

        return [
            'original_prices' => $productPrices,
            'prices_asc' => $pricesAsc,
            'prices_desc' => $pricesDesc,
            'names_sorted' => $namesAsc
        ];
    }

    /**
     * asort() / arsort() / ksort() / krsort() - Sort maintaining keys
     * Critical for: Maintaining product IDs, preserving associations
     */
    public function associativeSortExamples(): array
    {
        $productPrices = [
            'WS03' => 29.00,
            'WH01' => 15.99,
            'WS05' => 42.75,
            'WT01' => 35.50
        ];

        $customerOrders = [
            'john_doe' => 5,
            'alice_smith' => 12,
            'bob_johnson' => 3,
            'carol_wilson' => 8
        ];

        // Sort by price, keep SKUs associated
        $pricesSorted = $productPrices;
        asort($pricesSorted); // ['WH01' => 15.99, 'WS03' => 29.00, ...]

        // Sort by price descending
        $pricesDesc = $productPrices;
        arsort($pricesDesc); // ['WS05' => 42.75, 'WT01' => 35.50, ...]

        // Sort by SKU alphabetically
        $skusSorted = $productPrices;
        ksort($skusSorted); // ['WH01' => 15.99, 'WS03' => 29.00, 'WS05' => 42.75, 'WT01' => 35.50]

        // Sort customers by order count
        $ordersSorted = $customerOrders;
        arsort($ordersSorted); // ['alice_smith' => 12, 'carol_wilson' => 8, ...]

        return [
            'prices_by_value_asc' => $pricesSorted,
            'prices_by_value_desc' => $pricesDesc,
            'prices_by_sku' => $skusSorted,
            'customers_by_orders' => $ordersSorted
        ];
    }

    /**
     * usort() / uasort() / uksort() - Custom sorting
     * Advanced sorting for: Complex product attributes, custom business logic
     */
    public function customSortExamples(): array
    {
        $products = [
            ['sku' => 'WS03', 'name' => 'Tank Top', 'price' => 29.00, 'rating' => 4.5],
            ['sku' => 'WH01', 'name' => 'Hoodie', 'price' => 65.00, 'rating' => 4.8],
            ['sku' => 'WS05', 'name' => 'T-Shirt', 'price' => 25.00, 'rating' => 4.2],
            ['sku' => 'WT01', 'name' => 'Polo', 'price' => 45.00, 'rating' => 4.6]
        ];

        // Sort by rating (highest first)
        $byRating = $products;
        usort($byRating, function($a, $b) {
            return $b['rating'] <=> $a['rating'];
        });

        // Sort by price then rating
        $byPriceThenRating = $products;
        usort($byPriceThenRating, function($a, $b) {
            $priceComparison = $a['price'] <=> $b['price'];
            if ($priceComparison === 0) {
                return $b['rating'] <=> $a['rating']; // Higher rating if same price
            }
            return $priceComparison;
        });

        // Sort customers by complex criteria
        $customers = [
            'customer_1' => ['orders' => 5, 'total_spent' => 500, 'last_order' => '2024-07-01'],
            'customer_2' => ['orders' => 3, 'total_spent' => 800, 'last_order' => '2024-07-10'],
            'customer_3' => ['orders' => 8, 'total_spent' => 300, 'last_order' => '2024-06-15']
        ];

        // Sort by total spent (maintaining keys)
        uasort($customers, function($a, $b) {
            return $b['total_spent'] <=> $a['total_spent'];
        });

        return [
            'products_by_rating' => $byRating,
            'products_by_price_rating' => $byPriceThenRating,
            'customers_by_spending' => $customers
        ];
    }

    /**
     * ============================================================================
     * ARRAY MANIPULATION
     * ============================================================================
     */

    /**
     * array_merge() / array_merge_recursive() - Combine arrays
     * Essential for: Merging configurations, combining data sources
     */
    public function mergeExamples(): array
    {
        $defaultConfig = [
            'payment' => ['enabled' => true, 'methods' => ['credit_card']],
            'shipping' => ['enabled' => true, 'methods' => ['flat_rate']],
            'tax' => ['enabled' => false]
        ];

        $storeConfig = [
            'payment' => ['methods' => ['credit_card', 'paypal']],
            'shipping' => ['methods' => ['flat_rate', 'free_shipping']],
            'currency' => 'USD'
        ];

        // Simple merge (overwrites completely)
        $simpleMerged = array_merge($defaultConfig, $storeConfig);

        // Recursive merge (merges sub-arrays)
        $recursiveMerged = array_merge_recursive($defaultConfig, $storeConfig);

        // Merge product attributes
        $baseAttributes = ['name', 'price', 'sku'];
        $configurableAttributes = ['color', 'size'];
        $allAttributes = array_merge($baseAttributes, $configurableAttributes);

        // Merge customer data from different sources
        $customerFromDB = ['id' => 5, 'email' => 'john@example.com', 'group' => 1];
        $customerFromSession = ['firstname' => 'John', 'lastname' => 'Doe', 'cart_items' => 3];
        $fullCustomerData = array_merge($customerFromDB, $customerFromSession);

        return [
            'simple_merged' => $simpleMerged,
            'recursive_merged' => $recursiveMerged,
            'all_attributes' => $allAttributes,
            'full_customer_data' => $fullCustomerData
        ];
    }

    /**
     * array_slice() / array_splice() - Extract and modify portions
     * Used for: Pagination, product limits, data chunking
     */
    public function sliceExamples(): array
    {
        $allProducts = ['WS03', 'WS04', 'WS05', 'WH01', 'WH02', 'WT01', 'WT02', 'WT03'];
        $orderItems = [
            ['sku' => 'WS03', 'qty' => 2],
            ['sku' => 'WH01', 'qty' => 1],
            ['sku' => 'WS05', 'qty' => 3],
            ['sku' => 'WT01', 'qty' => 1]
        ];

        // Pagination: Get page 2 (3 items per page)
        $page = 2;
        $pageSize = 3;
        $offset = ($page - 1) * $pageSize;
        $productsPage2 = array_slice($allProducts, $offset, $pageSize); // ['WH01', 'WH02', 'WT01']

        // Get first 5 products
        $featuredProducts = array_slice($allProducts, 0, 5);

        // Get last 3 products
        $recentProducts = array_slice($allProducts, -3);

        // Remove middle items from order (using splice)
        $modifiedItems = $orderItems;
        array_splice($modifiedItems, 1, 2); // Remove 2 items starting at index 1

        // Insert new item at position 2
        $itemsWithInsert = $orderItems;
        array_splice($itemsWithInsert, 2, 0, [['sku' => 'NEW01', 'qty' => 1]]);

        return [
            'page_2_products' => $productsPage2,
            'featured_products' => $featuredProducts,
            'recent_products' => $recentProducts,
            'modified_order_items' => $modifiedItems,
            'order_with_insert' => $itemsWithInsert
        ];
    }

    /**
     * array_chunk() - Split array into chunks
     * Perfect for: Batch processing, creating product grids
     */
    public function chunkExamples(): array
    {
        $allProductSkus = ['WS03', 'WS04', 'WS05', 'WH01', 'WH02', 'WT01', 'WT02', 'WT03', 'WB01', 'WB02'];
        $customerEmails = [
            'john@example.com', 'jane@example.com', 'bob@example.com',
            'alice@example.com', 'carol@example.com', 'david@example.com'
        ];

        // Create product grid (3 columns)
        $productGrid = array_chunk($allProductSkus, 3);
        // [['WS03', 'WS04', 'WS05'], ['WH01', 'WH02', 'WT01'], ...]

        // Batch email processing (100 emails per batch)
        $emailBatches = array_chunk($customerEmails, 2); // Using 2 for demo
        // [['john@example.com', 'jane@example.com'], ['bob@example.com', 'alice@example.com'], ...]

        // Preserve keys for batch processing with IDs
        $customerIds = [101, 102, 103, 104, 105, 106];
        $customerBatches = array_chunk($customerIds, 3, true); // Preserve keys

        return [
            'product_grid' => $productGrid,
            'email_batches' => $emailBatches,
            'customer_batches' => $customerBatches
        ];
    }

    /**
     * ============================================================================
     * ARRAY KEYS AND VALUES
     * ============================================================================
     */

    /**
     * array_keys() / array_values() - Extract keys or values
     * Common for: Getting attribute codes, extracting IDs
     */
    public function keysValuesExamples(): array
    {
        $productPrices = [
            'WS03' => 29.00,
            'WH01' => 65.00,
            'WS05' => 25.00,
            'WT01' => 45.00
        ];

        $customerAttributes = [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+1-555-123-4567'
        ];

        // Get all product SKUs
        $productSkus = array_keys($productPrices); // ['WS03', 'WH01', 'WS05', 'WT01']

        // Get all prices
        $priceValues = array_values($productPrices); // [29.00, 65.00, 25.00, 45.00]

        // Get attribute codes
        $attributeCodes = array_keys($customerAttributes);

        // Find products with specific price
        $expensiveProductSkus = array_keys($productPrices, 65.00); // ['WH01']

        // Get all prices above $30
        $expensivePrices = array_keys(array_filter($productPrices, function($price) {
            return $price > 30;
        }));

        return [
            'product_skus' => $productSkus,
            'price_values' => $priceValues,
            'attribute_codes' => $attributeCodes,
            'expensive_product_skus' => $expensiveProductSkus,
            'expensive_prices' => $expensivePrices
        ];
    }

    /**
     * array_flip() / array_reverse() - Flip or reverse arrays
     * Useful for: Creating lookup tables, reversing chronological data
     */
    public function flipReverseExamples(): array
    {
        $productCategories = [
            'WS03' => 'Tops',
            'WH01' => 'Hoodies',
            'WB01' => 'Bags',
            'WS05' => 'Tops'
        ];

        $orderStatuses = ['pending', 'processing', 'shipped', 'delivered'];
        $recentOrders = [100, 101, 102, 103, 104];

        // Create category lookup (category => products)
        $categoryLookup = [];
        foreach ($productCategories as $sku => $category) {
            if (!isset($categoryLookup[$category])) {
                $categoryLookup[$category] = [];
            }
            $categoryLookup[$category][] = $sku;
        }

        // Flip status array for quick lookups
        $statusPositions = array_flip($orderStatuses);
        // ['pending' => 0, 'processing' => 1, 'shipped' => 2, 'delivered' => 3]

        // Reverse order list (newest first)
        $newestOrdersFirst = array_reverse($recentOrders); // [104, 103, 102, 101, 100]

        // Reverse maintaining keys
        $reversedWithKeys = array_reverse($productCategories, true);

        return [
            'category_lookup' => $categoryLookup,
            'status_positions' => $statusPositions,
            'newest_orders_first' => $newestOrdersFirst,
            'reversed_with_keys' => $reversedWithKeys
        ];
    }

    /**
     * ============================================================================
     * ARRAY COLUMN AND WALK
     * ============================================================================
     */

    /**
     * array_column() - Extract specific column from multidimensional array
     * Very common in Magento for: Getting specific fields from collections
     */
    public function columnExamples(): array
    {
        $products = [
            ['id' => 1, 'sku' => 'WS03', 'name' => 'Tank Top', 'price' => 29.00, 'category_id' => 11],
            ['id' => 2, 'sku' => 'WH01', 'name' => 'Hoodie', 'price' => 65.00, 'category_id' => 12],
            ['id' => 3, 'sku' => 'WS05', 'name' => 'T-Shirt', 'price' => 25.00, 'category_id' => 11],
            ['id' => 4, 'sku' => 'WT01', 'name' => 'Polo', 'price' => 45.00, 'category_id' => 13]
        ];

        $orders = [
            ['id' => 100, 'customer_id' => 5, 'total' => 94.00, 'status' => 'complete'],
            ['id' => 101, 'customer_id' => 6, 'total' => 156.00, 'status' => 'processing'],
            ['id' => 102, 'customer_id' => 5, 'total' => 75.00, 'status' => 'shipped']
        ];

        // Extract all SKUs
        $allSkus = array_column($products, 'sku'); // ['WS03', 'WH01', 'WS05', 'WT01']

        // Extract prices
        $allPrices = array_column($products, 'price'); // [29.00, 65.00, 25.00, 45.00]

        // Create SKU => Name mapping
        $skuToName = array_column($products, 'name', 'sku');
        // ['WS03' => 'Tank Top', 'WH01' => 'Hoodie', ...]

        // Create ID => Price mapping
        $idToPrice = array_column($products, 'price', 'id');
        // [1 => 29.00, 2 => 65.00, 3 => 25.00, 4 => 45.00]

        // Get unique customer IDs from orders
        $customerIds = array_unique(array_column($orders, 'customer_id')); // [5, 6]

        // Calculate total revenue
        $totalRevenue = array_sum(array_column($orders, 'total')); // 325.00

        return [
            'all_skus' => $allSkus,
            'all_prices' => $allPrices,
            'sku_to_name' => $skuToName,
            'id_to_price' => $idToPrice,
            'unique_customers' => array_values($customerIds),
            'total_revenue' => $totalRevenue
        ];
    }

    /**
     * array_walk() / array_walk_recursive() - Apply function to each element
     * Used for: Data transformation, validation, formatting
     */
    public function walkExamples(): array
    {
        $productPrices = [
            'WS03' => 29.00,
            'WH01' => 65.00,
            'WS05' => 25.00
        ];

        $customerData = [
            'firstname' => '  john  ',
            'lastname' => '  DOE  ',
            'email' => '  JOHN@EXAMPLE.COM  ',
            'address' => [
                'street' => '  123 Main St  ',
                'city' => '  austin  '
            ]
        ];

        // Apply tax to all prices
        $taxRate = 0.08;
        array_walk($productPrices, function(&$price, $sku) use ($taxRate) {
            $price = round($price * (1 + $taxRate), 2);
        });

        // Clean customer data (trim and normalize)
        $cleanedData = $customerData;
        array_walk_recursive($cleanedData, function(&$value, $key) {
            if (is_string($value)) {
                $value = trim($value);
                if (in_array($key, ['firstname', 'lastname', 'city'])) {
                    $value = ucwords(strtolower($value));
                } elseif ($key === 'email') {
                    $value = strtolower($value);
                }
            }
        });

        // Validate required fields
        $requiredFields = ['firstname', 'lastname', 'email'];
        $validationErrors = [];
        array_walk($requiredFields, function($field) use ($cleanedData, &$validationErrors) {
            if (empty($cleanedData[$field])) {
                $validationErrors[] = "Field '{$field}' is required";
            }
        });

        return [
            'prices_with_tax' => $productPrices,
            'cleaned_customer_data' => $cleanedData,
            'validation_errors' => $validationErrors
        ];
    }

    /**
     * ============================================================================
     * STRING/ARRAY CONVERSION
     * ============================================================================
     */

    /**
     * explode() / implode() - Convert between strings and arrays
     * Essential for: Processing CSV data, creating comma-separated lists
     */
    public function explodeImplodeExamples(): array
    {
        // Product categories from CSV import
        $categoryString = "Men,Women,Accessories,Sale";
        $skuList = "WS03,WH01,WS05,WT01,WB01";
        $customerTags = "VIP,Wholesale,Newsletter,Birthday";

        // Convert strings to arrays
        $categoryArray = explode(',', $categoryString); // ['Men', 'Women', 'Accessories', 'Sale']
        $skuArray = explode(',', $skuList); // ['WS03', 'WH01', 'WS05', 'WT01', 'WB01']
        $tagArray = explode(',', $customerTags);

        // Clean up whitespace
        $cleanSkuArray = array_map('trim', $skuArray);

        // Convert arrays back to strings
        $selectedCategories = ['Men', 'Accessories'];
        $categoryFilter = implode(',', $selectedCategories); // "Men,Accessories"

        $cartSkus = ['WS03', 'WH01', 'WS05'];
        $cartSkuString = implode('|', $cartSkus); // "WS03|WH01|WS05"

        // Create formatted product list
        $productNames = ['Tank Top', 'Hoodie', 'T-Shirt'];
        $productList = implode(', ', $productNames); // "Tank Top, Hoodie, T-Shirt"

        // Handle file path operations
        $pathParts = ['media', 'catalog', 'product', 'cache'];
        $fullPath = implode('/', $pathParts); // "media/catalog/product/cache"

        return [
            'category_array' => $categoryArray,
            'sku_array' => $cleanSkuArray,
            'tag_array' => $tagArray,
            'category_filter' => $categoryFilter,
            'cart_sku_string' => $cartSkuString,
            'product_list' => $productList,
            'full_path' => $fullPath
        ];
    }

    /**
     * ============================================================================
     * ADVANCED ARRAY FUNCTIONS
     * ============================================================================
     */

    /**
     * array_reduce() - Reduce array to single value
     * Powerful for: Calculating totals, building complex structures
     */
    public function reduceExamples(): array
    {
        $orderItems = [
            ['sku' => 'WS03', 'price' => 29.00, 'qty' => 2],
            ['sku' => 'WH01', 'price' => 65.00, 'qty' => 1],
            ['sku' => 'WS05', 'price' => 25.00, 'qty' => 3]
        ];

        $products = [
            ['sku' => 'WS03', 'category' => 'Tops', 'price' => 29.00],
            ['sku' => 'WH01', 'category' => 'Hoodies', 'price' => 65.00],
            ['sku' => 'WS05', 'category' => 'Tops', 'price' => 25.00],
            ['sku' => 'WB01', 'category' => 'Bags', 'price' => 45.00]
        ];

        // Calculate order total
        $orderTotal = array_reduce($orderItems, function($carry, $item) {
            return $carry + ($item['price'] * $item['qty']);
        }, 0); // 198.00

        // Calculate total quantity
        $totalQuantity = array_reduce($orderItems, function($carry, $item) {
            return $carry + $item['qty'];
        }, 0); // 6

        // Group products by category
        $productsByCategory = array_reduce($products, function($carry, $product) {
            $category = $product['category'];
            if (!isset($carry[$category])) {
                $carry[$category] = [];
            }
            $carry[$category][] = $product;
            return $carry;
        }, []);

        // Find highest priced product
        $highestPricedProduct = array_reduce($products, function($carry, $product) {
            return ($carry === null || $product['price'] > $carry['price']) ? $product : $carry;
        });

        // Build SKU to price mapping
        $skuToPriceMap = array_reduce($products, function($carry, $product) {
            $carry[$product['sku']] = $product['price'];
            return $carry;
        }, []);

        return [
            'order_total' => $orderTotal,
            'total_quantity' => $totalQuantity,
            'products_by_category' => $productsByCategory,
            'highest_priced_product' => $highestPricedProduct,
            'sku_to_price_map' => $skuToPriceMap
        ];
    }

    /**
     * array_intersect() / array_diff() - Compare arrays
     * Important for: Finding common products, detecting changes
     */
    public function intersectDiffExamples(): array
    {
        $currentCartSkus = ['WS03', 'WH01', 'WS05', 'WT01'];
        $wishlistSkus = ['WS03', 'WS05', 'WB01', 'WT02'];
        $availableSkus = ['WS03', 'WS04', 'WS05', 'WH01', 'WB01'];

        $oldProductCategories = ['Men', 'Women', 'Accessories'];
        $newProductCategories = ['Men', 'Women', 'Kids', 'Sale'];

        // Find products in both cart and wishlist
        $commonProducts = array_intersect($currentCartSkus, $wishlistSkus); // ['WS03', 'WS05']

        // Find products in cart but not available
        $unavailableCartItems = array_diff($currentCartSkus, $availableSkus); // ['WT01']

        // Find new categories
        $newCategories = array_diff($newProductCategories, $oldProductCategories); // ['Kids', 'Sale']

        // Find removed categories
        $removedCategories = array_diff($oldProductCategories, $newProductCategories); // ['Accessories']

        // Find products available for cart (in stock and not in cart)
        $availableForCart = array_diff($availableSkus, $currentCartSkus); // ['WS04', 'WB01']

        // Find products to suggest (in wishlist and available but not in cart)
        $suggestedProducts = array_intersect($wishlistSkus, $availableForCart); // ['WB01']

        return [
            'common_products' => array_values($commonProducts),
            'unavailable_cart_items' => array_values($unavailableCartItems),
            'new_categories' => array_values($newCategories),
            'removed_categories' => array_values($removedCategories),
            'available_for_cart' => array_values($availableForCart),
            'suggested_products' => array_values($suggestedProducts)
        ];
    }

    /**
     * array_unique() / array_count_values() - Handle duplicates and count
     * Useful for: Deduplication, analytics, frequency analysis
     */
    public function uniqueCountExamples(): array
    {
        $customerOrderHistory = ['WS03', 'WH01', 'WS03', 'WS05', 'WH01', 'WS03', 'WT01'];
        $pageViews = ['product_123', 'category_5', 'product_123', 'product_456', 'category_5', 'product_123'];
        $customerGroups = [1, 2, 1, 1, 3, 2, 1, 2, 3]; // Group IDs from multiple customers

        // Get unique products ordered
        $uniqueProductsOrdered = array_unique($customerOrderHistory); // ['WS03', 'WH01', 'WS05', 'WT01']

        // Count how many times each product was ordered
        $productOrderCounts = array_count_values($customerOrderHistory);
        // ['WS03' => 3, 'WH01' => 2, 'WS05' => 1, 'WT01' => 1]

        // Analyze page view patterns
        $pageViewCounts = array_count_values($pageViews);
        // ['product_123' => 3, 'category_5' => 2, 'product_456' => 1]

        // Customer group distribution
        $groupDistribution = array_count_values($customerGroups);
        // [1 => 4, 2 => 3, 3 => 2]

        // Find most popular product
        $mostPopularProduct = array_search(max($productOrderCounts), $productOrderCounts); // 'WS03'

        // Find unique viewed products
        $uniqueProductViews = array_unique(array_filter($pageViews, function($view) {
            return strpos($view, 'product_') === 0;
        }));

        return [
            'unique_products_ordered' => array_values($uniqueProductsOrdered),
            'product_order_counts' => $productOrderCounts,
            'page_view_counts' => $pageViewCounts,
            'group_distribution' => $groupDistribution,
            'most_popular_product' => $mostPopularProduct,
            'unique_product_views' => array_values($uniqueProductViews)
        ];
    }

    /**
     * ============================================================================
     * DEMONSTRATION METHOD
     * ============================================================================
     */

    /**
     * Get all array method examples
     * Use this to see all examples at once
     */
    public function getAllExamples(): array
    {
        return [
            'basic_info' => [
                'count_examples' => $this->countExamples(),
                'empty_isset_examples' => $this->emptyIssetExamples()
            ],
            'adding_removing' => [
                'push_pop_examples' => $this->pushPopExamples(),
                'unshift_shift_examples' => $this->unshiftShiftExamples(),
                'unset_examples' => $this->unsetExamples()
            ],
            'searching' => [
                'in_array_examples' => $this->inArrayExamples(),
                'search_key_exists_examples' => $this->searchKeyExistsExamples()
            ],
            'filtering_mapping' => [
                'filter_examples' => $this->filterExamples(),
                'map_examples' => $this->mapExamples()
            ],
            'sorting' => [
                'sort_examples' => $this->sortExamples(),
                'associative_sort_examples' => $this->associativeSortExamples(),
                'custom_sort_examples' => $this->customSortExamples()
            ],
            'manipulation' => [
                'merge_examples' => $this->mergeExamples(),
                'slice_examples' => $this->sliceExamples(),
                'chunk_examples' => $this->chunkExamples()
            ],
            'keys_values' => [
                'keys_values_examples' => $this->keysValuesExamples(),
                'flip_reverse_examples' => $this->flipReverseExamples()
            ],
            'column_walk' => [
                'column_examples' => $this->columnExamples(),
                'walk_examples' => $this->walkExamples()
            ],
            'string_conversion' => [
                'explode_implode_examples' => $this->explodeImplodeExamples()
            ],
            'advanced' => [
                'reduce_examples' => $this->reduceExamples(),
                'intersect_diff_examples' => $this->intersectDiffExamples(),
                'unique_count_examples' => $this->uniqueCountExamples()
            ]
        ];
    }
}
