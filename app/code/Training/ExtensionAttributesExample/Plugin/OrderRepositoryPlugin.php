<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Training\ExtensionAttributesExample\Plugin;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Training\ExtensionAttributesExample\Api\OrderPoNumberRepositoryInterface;
use Training\ExtensionAttributesExample\Api\Data\OrderPoNumberInterfaceFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

/**
 * Class OrderRepositoryPlugin
 * Plugin to handle Order PO Number extension attributes
 */
class OrderRepositoryPlugin
{
    /**
     * @var OrderExtensionFactory
     */
    private $orderExtensionFactory;

    /**
     * @var OrderPoNumberRepositoryInterface
     */
    private $orderPoNumberRepository;

    /**
     * @var OrderPoNumberInterfaceFactory
     */
    private $orderPoNumberFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * OrderRepositoryPlugin constructor.
     *
     * @param OrderExtensionFactory $orderExtensionFactory
     * @param OrderPoNumberRepositoryInterface $orderPoNumberRepository
     * @param OrderPoNumberInterfaceFactory $orderPoNumberFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        OrderExtensionFactory $orderExtensionFactory,
        OrderPoNumberRepositoryInterface $orderPoNumberRepository,
        OrderPoNumberInterfaceFactory $orderPoNumberFactory,
        LoggerInterface $logger
    ) {
        $this->orderExtensionFactory = $orderExtensionFactory;
        $this->orderPoNumberRepository = $orderPoNumberRepository;
        $this->orderPoNumberFactory = $orderPoNumberFactory;
        $this->logger = $logger;
    }

    /**
     * Load PO Number extension attribute after getting order
     *
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $order
     * @return OrderInterface
     */
    public function afterGet(OrderRepositoryInterface $subject, OrderInterface $order): OrderInterface
    {
        $this->loadPoNumberExtensionAttribute($order);
        return $order;
    }

    /**
     * Load PO Number extension attributes after getting order list
     *
     * @param OrderRepositoryInterface $subject
     * @param OrderSearchResultInterface $searchResult
     * @return OrderSearchResultInterface
     */
    public function afterGetList(
        OrderRepositoryInterface $subject,
        OrderSearchResultInterface $searchResult
    ): OrderSearchResultInterface {
        foreach ($searchResult->getItems() as $order) {
            $this->loadPoNumberExtensionAttribute($order);
        }
        return $searchResult;
    }

    /**
     * Save PO Number extension attribute before saving order
     *
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $order
     * @return array
     */
    public function beforeSave(OrderRepositoryInterface $subject, OrderInterface $order): array
    {
        // No need to modify the order before save, just return original parameters
        return [$order];
    }

    /**
     * Save PO Number extension attribute after saving order
     *
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $order
     * @return OrderInterface
     */
    public function afterSave(OrderRepositoryInterface $subject, OrderInterface $order): OrderInterface
    {
        $this->savePoNumberExtensionAttribute($order);
        return $order;
    }

    /**
     * Load PO Number extension attribute for order
     *
     * @param OrderInterface $order
     * @return void
     */
    private function loadPoNumberExtensionAttribute(OrderInterface $order): void
    {
        try {
            $extensionAttributes = $order->getExtensionAttributes();
            if ($extensionAttributes === null) {
                $extensionAttributes = $this->orderExtensionFactory->create();
            }

            // Try to load existing PO Number
            $orderPoNumber = $this->orderPoNumberRepository->getByOrderId((int)$order->getEntityId());
            $extensionAttributes->setPoNumber($orderPoNumber);
            $order->setExtensionAttributes($extensionAttributes);

        } catch (NoSuchEntityException $e) {
            // No PO Number exists for this order - this is fine, just leave it empty
            $extensionAttributes = $order->getExtensionAttributes();
            if ($extensionAttributes === null) {
                $extensionAttributes = $this->orderExtensionFactory->create();
                $order->setExtensionAttributes($extensionAttributes);
            }
        } catch (\Exception $e) {
            $this->logger->error(
                'Error loading PO Number for order: ' . $order->getEntityId(),
                ['exception' => $e->getMessage()]
            );
        }
    }

    /**
     * Save PO Number extension attribute for order
     *
     * @param OrderInterface $order
     * @return void
     */
    private function savePoNumberExtensionAttribute(OrderInterface $order): void
    {
        try {
            $extensionAttributes = $order->getExtensionAttributes();
            if ($extensionAttributes === null) {
                return;
            }

            $orderPoNumber = $extensionAttributes->getPoNumber();
            if ($orderPoNumber === null) {
                return;
            }

            // If it's a string (from quote), create a new OrderPoNumber object
            if (is_string($orderPoNumber)) {
                $poNumberData = $this->orderPoNumberFactory->create();
                $poNumberData->setOrderId((int)$order->getEntityId());
                $poNumberData->setPoNumber($orderPoNumber);
                $this->orderPoNumberRepository->save($poNumberData);
            } else {
                // If it's already an OrderPoNumberInterface object
                if (!$orderPoNumber->getOrderId()) {
                    $orderPoNumber->setOrderId((int)$order->getEntityId());
                }
                $this->orderPoNumberRepository->save($orderPoNumber);
            }

        } catch (\Exception $e) {
            $this->logger->error(
                'Error saving PO Number for order: ' . $order->getEntityId(),
                ['exception' => $e->getMessage()]
            );
        }
    }
}
