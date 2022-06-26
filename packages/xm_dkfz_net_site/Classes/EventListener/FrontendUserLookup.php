<?php

namespace Xima\XmDkfzNetSite\EventListener;

use Psr\Log\LoggerInterface;
use Waldhacker\Oauth2Client\Events\BackendUserLookupEvent;
use Waldhacker\Oauth2Client\Events\FrontendUserLookupEvent;

class FrontendUserLookup
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(FrontendUserLookupEvent $event): void
    {
        $this->logger->debug('FE User Lookup: ' . $event->getRemoteUser()->getId());

        if ($event->getTypo3User() !== null) {
            return;
        }
    }
}
