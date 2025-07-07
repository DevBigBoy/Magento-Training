<?php

namespace Training\DispatchExample\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Training\DispatchExample\Block\Example;

class ContentExample implements ObserverInterface
{
    public function execute(Observer $observer): void
    {
        /** @var \Magento\Framework\View\Result\Page $page */
        $page = $observer->getData('page');

        $page->getLayout()->addBlock(Example::class, 'example_block', 'content');
    }
}
