<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Training\ExtensionAttributesExample\Model\ResourceModel\OrderPoNumber;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Training\ExtensionAttributesExample\Model\OrderPoNumber;
use Training\ExtensionAttributesExample\Model\ResourceModel\OrderPoNumber as OrderPoNumberResource;

class Collection extends AbstractCollection
{
    /**
     * ID field name
     *
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'training_order_po_number_collection';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'order_po_number_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(OrderPoNumber::class, OrderPoNumberResource::class);
    }

    /**
     * Add order ID filter
     *
     * @param int|array $orderIds
     * @return $this
     */
    public function addOrderIdFilter($orderIds)
    {
        if (is_array($orderIds)) {
            $this->addFieldToFilter('order_id', ['in' => $orderIds]);
        } else {
            $this->addFieldToFilter('order_id', $orderIds);
        }
        return $this;
    }

    /**
     * Add PO Number filter
     *
     * @param string $poNumber
     * @return $this
     */
    public function addPoNumberFilter(string $poNumber)
    {
        $this->addFieldToFilter('po_number', $poNumber);
        return $this;
    }

    /**
     * Get items by order IDs
     *
     * @param array $orderIds
     * @return array
     */
    public function getItemsByOrderIds(array $orderIds): array
    {
        $this->addOrderIdFilter($orderIds);
        $items = [];
        foreach ($this->getItems() as $item) {
            $items[$item->getOrderId()] = $item;
        }
        return $items;
    }
}
