<?php

namespace Training\DisableCronExample\Cron;

use Psr\Log\LoggerInterface;

class Example
{

    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    /**
     * Write to system.log
     *
     * @return void
     */
    public function execute(): void
    {
        // another Way to Disable cron
        # return;
        $this->logger->info('System: Cron is Disabled');
        sleep(3);
        $this->logger->info('System: Cron Disabled Success');
    }
}
