<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Training\ExtensionAttributesExample\Api;

use Training\ExtensionAttributesExample\Api\Data\OrderPoNumberInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Interface OrderPoNumberRepositoryInterface
 * Repository interface for Order PO Number
 */
interface OrderPoNumberRepositoryInterface
{
    /**
     * Save Order PO Number
     *
     * @param OrderPoNumberInterface $orderPoNumber
     * @return OrderPoNumberInterface
     * @throws LocalizedException
     */
    public function save(OrderPoNumberInterface $orderPoNumber): OrderPoNumberInterface;

    /**
     * Get Order PO Number by ID
     *
     * @param int $id
     * @return OrderPoNumberInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $id): OrderPoNumberInterface;

    /**
     * Get Order PO Number by Order ID
     *
     * @param int $orderId
     * @return OrderPoNumberInterface
     * @throws NoSuchEntityException
     */
    public function getByOrderId(int $orderId): OrderPoNumberInterface;

    /**
     * Delete Order PO Number
     *
     * @param OrderPoNumberInterface $orderPoNumber
     * @return bool
     * @throws LocalizedException
     */
    public function delete(OrderPoNumberInterface $orderPoNumber): bool;

    /**
     * Delete Order PO Number by ID
     *
     * @param int $id
     * @return bool
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $id): bool;

    /**
     * Delete Order PO Number by Order ID
     *
     * @param int $orderId
     * @return bool
     * @throws LocalizedException
     */
    public function deleteByOrderId(int $orderId): bool;
}
