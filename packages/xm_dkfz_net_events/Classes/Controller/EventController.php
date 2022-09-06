<?php

namespace Xima\XmDkfzNetEvents\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Xima\XmDkfzNetEvents\Utility\EventLoaderUtility;

class EventController extends ActionController
{
    protected EventLoaderUtility $eventLoaderUtility;

    protected ExtensionConfiguration $extensionConfiguration;

    public function __construct(EventLoaderUtility $eventLoaderUtility, ExtensionConfiguration $extensionConfiguration)
    {
        $this->eventLoaderUtility = $eventLoaderUtility;
        $this->extensionConfiguration = $extensionConfiguration;
    }

    public function latestAction(): ResponseInterface
    {
        $apiUrl = $this->getApiUrl();
        $events = $this->eventLoaderUtility->getEvents($apiUrl);

        if ($this->settings['latestEventCount'] && MathUtility::canBeInterpretedAsInteger($this->settings['latestEventCount'])) {
            $events = array_slice($events, 0, (int)$this->settings['latestEventCount']);
        }

        $this->view->assign('events', $events);

        return $this->htmlResponse();
    }

    public function listAction(): ResponseInterface
    {
        $apiUrl = $this->getApiUrl();
        $events = $this->eventLoaderUtility->getEvents($apiUrl);

        $this->view->assign('events', $events);

        return $this->htmlResponse();
    }

    protected function getApiUrl(): string
    {
        $url = (string)$this->settings['url'];
        $extConf = (array)$this->extensionConfiguration->get('xm_dkfz_net_events');

        if (isset($extConf['api_url_override']) && is_string($extConf['api_url_override']) && strlen($extConf['api_url_override'])) {
            $url = $extConf['api_url_override'];
        }

        return $url;
    }
}
