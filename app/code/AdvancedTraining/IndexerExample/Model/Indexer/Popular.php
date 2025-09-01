<?php

declare(strict_types=1);

namespace AdvancedTraining\IndexerExample\Model\Indexer;

use AdvancedTraining\IndexerExample\Logger\Logger;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Indexer\ActionInterface as IndexerActionInterface;
use Magento\Framework\Mview\ActionInterface as MviewActionInterface;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;

class Popular implements IndexerActionInterface, MviewActionInterface
{
    private const INDEX_TABLE = 'indexer_example_product_popularity';
    private const BATCH_SIZE = 1000;

    public function __construct(
        private readonly Logger $logger,
        private readonly ResourceConnection $resourceConnection,
        private readonly OrderCollectionFactory $orderCollectionFactory,
    ) {
    }

    /**
     * Used by mview, allows process indexer in the "Update on schedule" mode
     */
    public function execute($ids)
    {
        $this->logger->info('Popular indexer executed for order IDs: ' . implode(', ', $ids));
        $this->processOrderIds($ids);
    }

    /**
     * Will take all the data and reindex
     * Will run when reindex via command line
     */
    public function executeFull()
    {
        $this->logger->info('Popular indexer executed for full reindex');

        try {
            // Clear existing index data
            $this->clearIndexTable();

            // Get all order IDs and process them
            $allOrderIds = $this->getAllOrderIds();
            $this->logger->info(sprintf('Processing %d total orders', count($allOrderIds)));

            // Process in batches to avoid memory issues
            $batches = array_chunk($allOrderIds, self::BATCH_SIZE);
            foreach ($batches as $batch) {
                $this->processOrderIds($batch);
            }

            $this->logger->info('Full reindex completed successfully');
        } catch (\Exception $e) {
            $this->logger->error('Full reindex failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Works with a set of entity changed (may be mass action)
     */
    public function executeList(array $ids)
    {
        $this->logger->info('Popular indexer executed for order IDs list: ' . implode(', ', $ids));
        $this->processOrderIds($ids);
    }

    /**
     * Works in runtime for a single entity using plugins
     */
    public function executeRow($id)
    {
        $this->logger->info('Popular indexer executed for order ID: ' . $id);
        $this->processOrderIds([$id]);
    }

    /**
     * Process order IDs and update popularity index
     */
    private function processOrderIds(array $orderIds): void
    {
        if (empty($orderIds)) {
            return;
        }

        try {
            // Get affected products from these orders
            $affectedProductIds = $this->getProductIdsFromOrders($orderIds);

            if (empty($affectedProductIds)) {
                $this->logger->info('No products found in the specified orders');
                return;
            }

            $this->logger->info(sprintf('Found %d affected products', count($affectedProductIds)));

            // Calculate popularity for affected products
            foreach ($affectedProductIds as $productId) {
                $popularityData = $this->calculateProductPopularity($productId);
                $this->updateProductPopularityIndex($productId, $popularityData);
            }

        } catch (\Exception $e) {
            $this->logger->error('Error processing order IDs: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get product IDs from order IDs
     */
    private function getProductIdsFromOrders(array $orderIds): array
    {
        $connection = $this->getConnection();

        $select = $connection->select()
            ->from(['soi' => $connection->getTableName('sales_order_item')], ['product_id'])
            ->where('soi.order_id IN (?)', $orderIds)
            ->where('soi.product_type != ?', 'configurable') // Avoid parent products
            ->distinct();

        return $connection->fetchCol($select);
    }

    /**
     * Calculate popularity metrics for a product
     */
    private function calculateProductPopularity(int $productId): array
    {
        $connection = $this->getConnection();

        // Get popularity metrics from sales data
        $select = $connection->select()
            ->from(
                ['so' => $connection->getTableName('sales_order')],
                [
                    'total_orders' => 'COUNT(DISTINCT so.entity_id)',
                    'total_qty' => 'SUM(soi.qty_ordered)',
                    'total_revenue' => 'SUM(soi.row_total)',
                    'avg_order_value' => 'AVG(soi.row_total)',
                    'last_ordered' => 'MAX(so.created_at)'
                ]
            )
            ->joinInner(
                ['soi' => $connection->getTableName('sales_order_item')],
                'so.entity_id = soi.order_id',
                []
            )
            ->where('soi.product_id = ?', $productId)
            ->where('so.state IN (?)', ['processing', 'complete']) // Only successful orders
            ->where('so.created_at >= ?', date('Y-m-d H:i:s', strtotime('-1 year'))); // Last year only

        $result = $connection->fetchRow($select);

        if (!$result || !$result['total_orders']) {
            return [
                'total_orders' => 0,
                'total_qty' => 0,
                'total_revenue' => 0.00,
                'popularity_score' => 0.00,
                'last_ordered' => null
            ];
        }

        // Calculate popularity score (you can adjust this formula)
        $popularityScore = $this->calculatePopularityScore($result);

        return [
            'total_orders' => (int)$result['total_orders'],
            'total_qty' => (int)$result['total_qty'],
            'total_revenue' => (float)$result['total_revenue'],
            'popularity_score' => $popularityScore,
            'last_ordered' => $result['last_ordered']
        ];
    }

    /**
     * Calculate popularity score based on various metrics
     */
    private function calculatePopularityScore(array $salesData): float
    {
        $orders = (int)$salesData['total_orders'];
        $qty = (int)$salesData['total_qty'];
        $revenue = (float)$salesData['total_revenue'];

        // Weighted scoring formula (adjust weights as needed)
        $orderWeight = 0.4;
        $qtyWeight = 0.3;
        $revenueWeight = 0.3;

        // Normalize values (you might want to make this more sophisticated)
        $normalizedOrders = min($orders / 10, 10); // Cap at 10
        $normalizedQty = min($qty / 50, 10); // Cap at 10
        $normalizedRevenue = min($revenue / 1000, 10); // Cap at 10

        $score = ($normalizedOrders * $orderWeight) +
            ($normalizedQty * $qtyWeight) +
            ($normalizedRevenue * $revenueWeight);

        return round($score, 2);
    }

    /**
     * Update or insert product popularity data
     */
    private function updateProductPopularityIndex(int $productId, array $popularityData): void
    {
        $connection = $this->getConnection();
        $tableName = $connection->getTableName(self::INDEX_TABLE);

        $data = [
            'product_id' => $productId,
            'total_orders' => $popularityData['total_orders'],
            'total_qty_sold' => $popularityData['total_qty'],
            'total_revenue' => $popularityData['total_revenue'],
            'popularity_score' => $popularityData['popularity_score'],
            'last_ordered_at' => $popularityData['last_ordered'],
            'updated_at' => new \Zend_Db_Expr('NOW()')
        ];

        // Use INSERT ... ON DUPLICATE KEY UPDATE for MySQL
        $connection->insertOnDuplicate($tableName, $data, array_keys($data));

        $this->logger->info(sprintf(
            'Updated popularity for product %d: score=%.2f, orders=%d, qty=%d',
            $productId,
            $popularityData['popularity_score'],
            $popularityData['total_orders'],
            $popularityData['total_qty']
        ));
    }

    /**
     * Get all order IDs for full reindex
     */
    private function getAllOrderIds(): array
    {
        $connection = $this->getConnection();

        $select = $connection->select()
            ->from($connection->getTableName('sales_order'), ['entity_id'])
            ->where('state IN (?)', ['processing', 'complete'])
            ->where('created_at >= ?', date('Y-m-d H:i:s', strtotime('-1 year')));

        return $connection->fetchCol($select);
    }

    /**
     * Clear the index table
     */
    private function clearIndexTable(): void
    {
        $connection = $this->getConnection();
        $tableName = $connection->getTableName(self::INDEX_TABLE);

        $connection->truncateTable($tableName);
        $this->logger->info('Popularity index table cleared');
    }

    /**
     * Get database connection
     */
    private function getConnection(): AdapterInterface
    {
        return $this->resourceConnection->getConnection();
    }
}
