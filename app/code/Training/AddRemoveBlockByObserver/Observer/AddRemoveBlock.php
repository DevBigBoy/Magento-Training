<?php

namespace Training\AddRemoveBlockByObserver\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Element\Template;

class AddRemoveBlock implements ObserverInterface
{

    public function execute(Observer $observer)
    {
        $layout = $observer->getLayout();

        if ($observer->getFullActionName() === 'catalog_product_view') {
            $block = $layout->getBlock('view.addto.compare');
            if ($block) {
                $layout->unsetElement($block->getNameInLayout());
            }

            $layout->addBlock(Template::class, 'training.test.block', 'product.info.addto')
            ->setTemplate('Training_AddRemoveBlockByObserver::test.phtml');

        }
    }
}
