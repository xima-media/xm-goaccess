<?php

namespace Xima\XmDkfzNetSite\EventListener;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Waldhacker\Oauth2Client\Events\BackendUserLookupEvent;
use Xima\XmDkfzNetSite\ResourceResolver\AbstractResolver;
use Xima\XmDkfzNetSite\ResourceResolver\DkfzResolver;
use Xima\XmDkfzNetSite\ResourceResolver\GitlabResolver;
use Xima\XmDkfzNetSite\UserFactory\BackendUserFactory;

class BackendUserLookup
{
    private LoggerInterface $logger;

    protected BackendUserFactory $backendUserFactory;

    protected ?array $typo3User = null;

    public function __construct(LoggerInterface $logger, BackendUserFactory $backendUserFactory)
    {
        $this->logger = $logger;
        $this->backendUserFactory = $backendUserFactory;
    }

    public function __invoke(BackendUserLookupEvent $event): void
    {
        if ($event->getTypo3User() !== null || !($event->getRemoteUser() instanceof ResourceOwnerInterface)) {
            return;
        }
        $this->logger->debug('Creating remote user from provider "' . $event->getProviderId() . '" (remote id: ' . $event->getRemoteUser()->getId() . ')');

        $resolver = $this->createResolver($event);

        $this->backendUserFactory->setResolver($resolver);

        $this->typo3User = $this->backendUserFactory->registerRemoteUser();

        if ($this->typo3User) {
            $event->setTypo3User($this->typo3User);
        }
    }

    public function getTypo3User(): ?array
    {
        return $this->typo3User;
    }

    /**
     * @throws \Exception
     */
    public function createResolver(BackendUserLookupEvent $event): AbstractResolver
    {
        // @TODO: better way to guess the Resolver class
        $resolverClass = $event->getProviderId() === 'gitlab' ? GitlabResolver::class : DkfzResolver::class;

        if (!$resolverClass) {
            throw new \Exception('No Resolver found for provider id "' . $event->getProviderId() . '"');
        }

        return GeneralUtility::makeInstance(
            $resolverClass,
            $event->getProvider(),
            $event->getRemoteUser(),
            $event->getAccessToken(),
            $event->getProviderId()
        );
    }
}
