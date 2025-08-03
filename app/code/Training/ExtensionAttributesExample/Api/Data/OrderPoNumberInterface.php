<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Training\ExtensionAttributesExample\Api\Data;

/**
 * Interface OrderPoNumberInterface
 * Data interface for Order PO Number extension attribute
 */
interface OrderPoNumberInterface
{
    /**
     * Constants for keys of a data array
     */
    const string ID = 'id';
    const string ORDER_ID = 'order_id';
    const string PO_NUMBER = 'po_number';

    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return \Training\ExtensionAttributesExample\Api\Data\OrderPoNumberInterface
     */
    public function setId($id);

    /**
     * Get Order ID
     *
     * @return int
     */
    public function getOrderId(): int;

    /**
     * Set Order ID
     *
     * @param int $orderId
     * @return \Training\ExtensionAttributesExample\Api\Data\OrderPoNumberInterface
     */
    public function setOrderId(int $orderId): OrderPoNumberInterface;

    /**
     * Get PO Number
     *
     * @return string
     */
    public function getPoNumber(): string;

    /**
     * Set PO Number
     *
     * @param string $poNumber
     * @return \Training\ExtensionAttributesExample\Api\Data\OrderPoNumberInterface
     */
    public function setPoNumber(string $poNumber): OrderPoNumberInterface;
}
