<?php

namespace Xima\XmDkfzNetSite\EventListener;

use Psr\Log\LoggerInterface;
use Waldhacker\Oauth2Client\Events\BackendUserLookupEvent;

class BackendUserLookup
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(BackendUserLookupEvent $event): void
    {
        $this->logger->debug('User Lookup: ' . $event->getRemoteUser()->getId());

        if ($event->getTypo3User() !== null) {
            return;
        }
    }
}
