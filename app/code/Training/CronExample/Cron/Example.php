<?php

namespace Training\CronExample\Cron;

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
        $this->logger->info('CronExample: Cron Works');
        sleep(3);
        $this->logger->info('CronExample: Cron Finished');
    }
}
