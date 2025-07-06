<?php

namespace Training\ObserverExample\Observer\Frontend;


use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Training\ObserverExample\Logger\Logger;

class Example implements ObserverInterface
{

    private Logger $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        $this->logger->info('Event Triggered, From Frontend');
    }
}
