<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Training\ExtensionAttributesExample\Model;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Training\ExtensionAttributesExample\Api\Data\OrderPoNumberInterface;
use Training\ExtensionAttributesExample\Api\Data\OrderPoNumberInterfaceFactory;
use Training\ExtensionAttributesExample\Api\OrderPoNumberRepositoryInterface;
use Training\ExtensionAttributesExample\Model\ResourceModel\OrderPoNumber as OrderPoNumberResource;

class OrderPoNumberRepository implements OrderPoNumberRepositoryInterface
{
    /**
     * @var OrderPoNumberResource
     */
    private OrderPoNumberResource $resource;

    /**
     * @var OrderPoNumberInterfaceFactory
     */
    private OrderPoNumberInterfaceFactory $orderPoNumberFactory;

    /**
     * @var array
     */
    private array $instances = [];

    /**
     * OrderPoNumberRepository constructor.
     *
     * @param OrderPoNumberResource $resource
     * @param OrderPoNumberInterfaceFactory $orderPoNumberFactory
     */
    public function __construct(
        OrderPoNumberResource $resource,
        OrderPoNumberInterfaceFactory $orderPoNumberFactory
    ) {
        $this->resource = $resource;
        $this->orderPoNumberFactory = $orderPoNumberFactory;
    }

    /**
     * Save Order PO Number
     *
     * @param OrderPoNumberInterface $orderPoNumber
     * @return OrderPoNumberInterface
     * @throws CouldNotSaveException
     */
    public function save(OrderPoNumberInterface $orderPoNumber): OrderPoNumberInterface
    {
        try {
            $this->resource->save($orderPoNumber);
            unset($this->instances[$orderPoNumber->getId()]);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the Order PO Number: %1', $exception->getMessage()),
                $exception
            );
        }

        return $orderPoNumber;
    }

    /**
     * Get Order PO Number by ID
     *
     * @param int $id
     * @return OrderPoNumberInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $id): OrderPoNumberInterface
    {
        if (!isset($this->instances[$id])) {
            /** @var OrderPoNumberInterface $orderPoNumber */
            $orderPoNumber = $this->orderPoNumberFactory->create();
            $this->resource->load($orderPoNumber, $id);

            if (!$orderPoNumber->getId()) {
                throw new NoSuchEntityException(__('Order PO Number with id "%1" does not exist.', $id));
            }

            $this->instances[$id] = $orderPoNumber;
        }

        return $this->instances[$id];
    }

    /**
     * Get Order PO Number by Order ID
     *
     * @param int $orderId
     * @return OrderPoNumberInterface
     * @throws NoSuchEntityException
     */
    public function getByOrderId(int $orderId): OrderPoNumberInterface
    {
        /** @var OrderPoNumberInterface $orderPoNumber */
        $orderPoNumber = $this->orderPoNumberFactory->create();
        $this->resource->loadByOrderId($orderPoNumber, $orderId);

        if (!$orderPoNumber->getId()) {
            throw new NoSuchEntityException(__('Order PO Number with order id "%1" does not exist.', $orderId));
        }

        return $orderPoNumber;
    }

    /**
     * Delete Order PO Number
     *
     * @param OrderPoNumberInterface $orderPoNumber
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(OrderPoNumberInterface $orderPoNumber): bool
    {
        try {
            $this->resource->delete($orderPoNumber);
            unset($this->instances[$orderPoNumber->getId()]);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __('Could not delete the Order PO Number: %1', $exception->getMessage()),
                $exception
            );
        }

        return true;
    }

    /**
     * Delete Order PO Number by ID
     *
     * @param int $id
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $id): bool
    {
        return $this->delete($this->getById($id));
    }

    /**
     * Delete Order PO Number by Order ID
     *
     * @param int $orderId
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function deleteByOrderId(int $orderId): bool
    {
        try {
            $this->resource->deleteByOrderId($orderId);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __('Could not delete the Order PO Number by order id: %1', $exception->getMessage()),
                $exception
            );
        }

        return true;
    }
}
