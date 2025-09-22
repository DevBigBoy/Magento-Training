<?php

namespace Training\CronConfigPath\Cron;

use Psr\Log\LoggerInterface;

class Example
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * Write to system.log
     *
     * @return void
     */
    public function execute()
    {
        $this->logger->info('Cron Works:  Training Cron Config Path');
    }
}
