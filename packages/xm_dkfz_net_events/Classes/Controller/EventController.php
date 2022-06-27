<?php

namespace Xima\XmDkfzNetEvents\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Xima\XmDkfzNetEvents\Utility\EventLoaderUtility;

class EventController extends ActionController
{
    protected EventLoaderUtility $eventLoaderUtility;

    public function __construct(EventLoaderUtility $eventLoaderUtility)
    {
        $this->eventLoaderUtility = $eventLoaderUtility;
    }

    public function latestAction(): ResponseInterface
    {
        $events = $this->eventLoaderUtility->getEvents();

        if ($this->settings['latestEventCount'] && MathUtility::canBeInterpretedAsInteger($this->settings['latestEventCount'])) {
            $events = array_slice($events, 0, (int)$this->settings['latestEventCount']);
        }

        $this->view->assign('events', $events);

        return $this->htmlResponse();
    }
}
