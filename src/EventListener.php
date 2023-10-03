<?php

namespace App\EventListener;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Psr\Log\LoggerInterface;

class DoctrineFlushLoggerListener
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $this->logger->info('EntityManager::flush() was called.');
    }
}