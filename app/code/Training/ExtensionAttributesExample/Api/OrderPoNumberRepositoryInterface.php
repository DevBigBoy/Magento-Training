<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Training\ExtensionAttributesExample\Api;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Training\ExtensionAttributesExample\Api\Data\OrderPoNumberInterface;

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
     * @throws CouldNotSaveException|NoSuchEntityException
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
     * @throws CouldNotDeleteException
     */
    public function delete(OrderPoNumberInterface $orderPoNumber): bool;

    /**
     * Delete Order PO Number by ID
     *
     * @param int $id
     * @return bool
     * @throws LocalizedException
     * @throws CouldNotDeleteException
     */
    public function deleteById(int $id): bool;

    /**
     * Delete Order PO Number by Order ID
     *
     * @param int $orderId
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function deleteByOrderId(int $orderId): bool;
}
