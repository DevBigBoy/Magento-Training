<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Training\ExtensionAttributesExample\Plugin;

use Magento\Quote\Model\QuoteManagement;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Psr\Log\LoggerInterface;

/**
 * Class QuoteToOrderPlugin
 * Plugin to transfer PO Number from Quote to Order during checkout
 */
class QuoteToOrderPlugin
{
    /**
     * @var OrderExtensionFactory
     */
    private $orderExtensionFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * QuoteToOrderPlugin constructor.
     *
     * @param OrderExtensionFactory $orderExtensionFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        OrderExtensionFactory $orderExtensionFactory,
        LoggerInterface $logger
    ) {
        $this->orderExtensionFactory = $orderExtensionFactory;
        $this->logger = $logger;
    }

    /**
     * Transfer PO Number from Quote to Order after order submission
     *
     * @param QuoteManagement $subject
     * @param OrderInterface $order
     * @param CartInterface $quote
     * @return OrderInterface
     */
    public function afterSubmit(
        QuoteManagement $subject,
        OrderInterface $order,
        CartInterface $quote
    ): OrderInterface {
        try {
            // Get PO Number from quote extension attributes
            $quoteExtensionAttributes = $quote->getExtensionAttributes();
            if ($quoteExtensionAttributes === null) {
                return $order;
            }

            $poNumber = $quoteExtensionAttributes->getPoNumber();
            if (empty($poNumber)) {
                return $order;
            }

            // Set PO Number in order extension attributes
            $orderExtensionAttributes = $order->getExtensionAttributes();
            if ($orderExtensionAttributes === null) {
                $orderExtensionAttributes = $this->orderExtensionFactory->create();
            }

            // Set the PO number as string - the OrderRepositoryPlugin will convert it to proper object
            $orderExtensionAttributes->setPoNumber($poNumber);
            $order->setExtensionAttributes($orderExtensionAttributes);

            $this->logger->info(
                'PO Number transferred from quote to order',
                [
                    'quote_id' => $quote->getId(),
                    'order_id' => $order->getEntityId(),
                    'po_number' => $poNumber
                ]
            );

        } catch (\Exception $e) {
            $this->logger->error(
                'Error transferring PO Number from quote to order',
                [
                    'quote_id' => $quote->getId(),
                    'order_id' => $order->getEntityId(),
                    'exception' => $e->getMessage()
                ]
            );
        }

        return $order;
    }
}
