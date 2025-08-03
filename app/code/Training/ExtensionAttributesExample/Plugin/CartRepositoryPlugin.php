<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Training\ExtensionAttributesExample\Plugin;

use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Api\Data\CartSearchResultsInterface;
use Magento\Quote\Api\Data\CartExtensionFactory;
use Psr\Log\LoggerInterface;

/**
 * Class CartRepositoryPlugin
 * Plugin to handle Quote/Cart PO Number extension attributes
 */
class CartRepositoryPlugin
{
    /**
     * @var CartExtensionFactory
     */
    private $cartExtensionFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * CartRepositoryPlugin constructor.
     *
     * @param CartExtensionFactory $cartExtensionFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        CartExtensionFactory $cartExtensionFactory,
        LoggerInterface $logger
    ) {
        $this->cartExtensionFactory = $cartExtensionFactory;
        $this->logger = $logger;
    }

    /**
     * Load PO Number extension attribute after getting cart
     *
     * @param CartRepositoryInterface $subject
     * @param CartInterface $cart
     * @return CartInterface
     */
    public function afterGet(CartRepositoryInterface $subject, CartInterface $cart): CartInterface
    {
        $this->loadPoNumberExtensionAttribute($cart);
        return $cart;
    }

    /**
     * Load PO Number extension attribute after getting active cart
     *
     * @param CartRepositoryInterface $subject
     * @param CartInterface $cart
     * @return CartInterface
     */
    public function afterGetActive(CartRepositoryInterface $subject, CartInterface $cart): CartInterface
    {
        $this->loadPoNumberExtensionAttribute($cart);
        return $cart;
    }

    /**
     * Load PO Number extension attribute after getting active cart for customer
     *
     * @param CartRepositoryInterface $subject
     * @param CartInterface $cart
     * @return CartInterface
     */
    public function afterGetActiveForCustomer(CartRepositoryInterface $subject, CartInterface $cart): CartInterface
    {
        $this->loadPoNumberExtensionAttribute($cart);
        return $cart;
    }

    /**
     * Load PO Number extension attributes after getting cart list
     *
     * @param CartRepositoryInterface $subject
     * @param CartSearchResultsInterface $searchResult
     * @return CartSearchResultsInterface
     */
    public function afterGetList(
        CartRepositoryInterface $subject,
        CartSearchResultsInterface $searchResult
    ): CartSearchResultsInterface {
        foreach ($searchResult->getItems() as $cart) {
            $this->loadPoNumberExtensionAttribute($cart);
        }
        return $searchResult;
    }

    /**
     * Save PO Number extension attribute after saving cart
     *
     * @param CartRepositoryInterface $subject
     * @param CartInterface $cart
     * @return CartInterface
     */
    public function afterSave(CartRepositoryInterface $subject, CartInterface $cart): CartInterface
    {
        $this->savePoNumberExtensionAttribute($cart);
        return $cart;
    }

    /**
     * Load PO Number extension attribute for cart
     *
     * @param CartInterface $cart
     * @return void
     */
    private function loadPoNumberExtensionAttribute(CartInterface $cart): void
    {
        try {
            $extensionAttributes = $cart->getExtensionAttributes();
            if ($extensionAttributes === null) {
                $extensionAttributes = $this->cartExtensionFactory->create();
            }

            // Load PO Number from quote table (if stored there)
            // For this example, we'll load from the quote's additional data
            $poNumber = $cart->getData('po_number');
            if ($poNumber) {
                $extensionAttributes->setPoNumber($poNumber);
                $cart->setExtensionAttributes($extensionAttributes);
            }

        } catch (\Exception $e) {
            $this->logger->error(
                'Error loading PO Number for cart: ' . $cart->getId(),
                ['exception' => $e->getMessage()]
            );
        }
    }

    /**
     * Save PO Number extension attribute for cart
     *
     * @param CartInterface $cart
     * @return void
     */
    private function savePoNumberExtensionAttribute(CartInterface $cart): void
    {
        try {
            $extensionAttributes = $cart->getExtensionAttributes();
            if ($extensionAttributes === null) {
                return;
            }

            $poNumber = $extensionAttributes->getPoNumber();
            if ($poNumber !== null) {
                // Save PO Number to quote table's additional data
                $cart->setData('po_number', $poNumber);

                $this->logger->info(
                    'PO Number saved to cart',
                    [
                        'cart_id' => $cart->getId(),
                        'po_number' => $poNumber
                    ]
                );
            }

        } catch (\Exception $e) {
            $this->logger->error(
                'Error saving PO Number for cart: ' . $cart->getId(),
                ['exception' => $e->getMessage()]
            );
        }
    }
}
