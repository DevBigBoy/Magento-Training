<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Training\ExtensionAttributesExample\Model;

use Magento\Framework\Model\AbstractModel;
use Training\ExtensionAttributesExample\Api\Data\OrderPoNumberInterface;
use Training\ExtensionAttributesExample\Model\ResourceModel\OrderPoNumber as OrderPoNumberResource;

/**
 * Class OrderPoNumber
 * Model class for Order PO Number
 */
class OrderPoNumber extends AbstractModel implements OrderPoNumberInterface
{
    /**
     * Cache tag
     */
    const string CACHE_TAG = 'training_order_po_number';

    /**
     * Cache tag
     *
     * @var string
     */
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'training_order_po_number';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'order_po_number';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(OrderPoNumberResource::class);
    }

    /**
     * Get Order ID
     *
     * @return int
     */
    public function getOrderId(): int
    {
        return (int)$this->getData(self::ORDER_ID);
    }

    /**
     * Set Order ID
     *
     * @param int $orderId
     * @return OrderPoNumberInterface
     */
    public function setOrderId(int $orderId): OrderPoNumberInterface
    {
        $this->setData(self::ORDER_ID, $orderId);
        return $this;
    }

    /**
     * Get PO Number
     *
     * @return string
     */
    public function getPoNumber(): string
    {
        return $this->getData(self::PO_NUMBER);
    }

    /**
     * Set PO Number
     *
     * @param string $poNumber
     * @return OrderPoNumberInterface
     */
    public function setPoNumber(string $poNumber): OrderPoNumberInterface
    {
        $this->setData(self::PO_NUMBER, $poNumber);
        return $this;
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
