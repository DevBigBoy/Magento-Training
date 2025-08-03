<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Training\ExtensionAttributesExample\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Training\ExtensionAttributesExample\Api\OrderPoNumberRepositoryInterface;
use Training\ExtensionAttributesExample\Api\Data\OrderPoNumberInterfaceFactory;
use Psr\Log\LoggerInterface;

/**
 * Class OrderSaveAfterObserver
 * Observer to save PO Number after order save
 * Alternative approach to plugins for demonstration
 */
class OrderSaveAfterObserver implements ObserverInterface
{
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
     * OrderSaveAfterObserver constructor.
     *
     * @param OrderPoNumberRepositoryInterface $orderPoNumberRepository
     * @param OrderPoNumberInterfaceFactory $orderPoNumberFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        OrderPoNumberRepositoryInterface $orderPoNumberRepository,
        OrderPoNumberInterfaceFactory $orderPoNumberFactory,
        LoggerInterface $logger
    ) {
        $this->orderPoNumberRepository = $orderPoNumberRepository;
        $this->orderPoNumberFactory = $orderPoNumberFactory;
        $this->logger = $logger;
    }

    /**
     * Execute observer
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        try {
            /** @var OrderInterface $order */
            $order = $observer->getEvent()->getOrder();

            if (!$order instanceof OrderInterface) {
                return;
            }

            $extensionAttributes = $order->getExtensionAttributes();
            if ($extensionAttributes === null) {
                return;
            }

            $orderPoNumber = $extensionAttributes->getPoNumber();
            if ($orderPoNumber === null) {
                return;
            }

            // Handle both string (from quote) and object cases
            if (is_string($orderPoNumber)) {
                // Create new OrderPoNumber object from string
                $poNumberData = $this->orderPoNumberFactory->create();
                $poNumberData->setOrderId((int)$order->getEntityId());
                $poNumberData->setPoNumber($orderPoNumber);
                $this->orderPoNumberRepository->save($poNumberData);

                $this->logger->info(
                    'PO Number saved via observer (string)',
                    [
                        'order_id' => $order->getEntityId(),
                        'po_number' => $orderPoNumber
                    ]
                );
            } else {
                // Handle OrderPoNumberInterface object
                if (!$orderPoNumber->getOrderId()) {
                    $orderPoNumber->setOrderId((int)$order->getEntityId());
                }
                $this->orderPoNumberRepository->save($orderPoNumber);

                $this->logger->info(
                    'PO Number saved via observer (object)',
                    [
                        'order_id' => $order->getEntityId(),
                        'po_number' => $orderPoNumber->getPoNumber()
                    ]
                );
            }

        } catch (\Exception $e) {
            $this->logger->error(
                'Error in OrderSaveAfterObserver',
                [
                    'order_id' => $order->getEntityId() ?? 'unknown',
                    'exception' => $e->getMessage()
                ]
            );
        }
    }
}
