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

        if ($this->settings['maxItems'] && MathUtility::canBeInterpretedAsInteger($this->settings['maxItems'])) {
            $events = array_slice($events, 0, (int)$this->settings['maxItems']);
        }

        $this->view->assign('events', $events);

        $this->addCacheTag();

        return $this->htmlResponse();
    }

    public function listAction(): ResponseInterface
    {
        $apiUrl = $this->getApiUrl();
        $events = $this->eventLoaderUtility->getEvents($apiUrl);

        if ($this->settings['maxItems'] && MathUtility::canBeInterpretedAsInteger($this->settings['maxItems'])) {
            $events = array_slice($events, 0, (int)$this->settings['maxItems']);
        }

        $header = $this->configurationManager->getContentObject()?->data['header'] ?? '';

        $this->view->assign('header', $header);
        $this->view->assign('events', $events);

        $this->addCacheTag();

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

    protected function addCacheTag(): void
    {
        // Add cache tag
        if (!empty($GLOBALS['TSFE']) && is_object($GLOBALS['TSFE'])) {
            static $cacheTagsSet = false;
            $typoScriptFrontendController = $GLOBALS['TSFE'];
            if (!$cacheTagsSet) {
                $typoScriptFrontendController->addCacheTags(['dkfz_events']);
                $cacheTagsSet = true;
            }
        }
    }
}
