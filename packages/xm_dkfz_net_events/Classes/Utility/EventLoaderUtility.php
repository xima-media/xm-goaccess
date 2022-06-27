<?php

namespace Xima\XmDkfzNetEvents\Utility;

use Xima\XmDkfzNetEvents\Domain\Model\Dto\Event;

class EventLoaderUtility
{
    /**
     * @var array<Event>
     */
    protected array $events = [];

    public function getEvents(string $url): array
    {
        $this->loadEvents($url);

        return $this->events;
    }

    public function loadEvents(string $url): void
    {
        // check cache
        $this->requestRssEvents($url);
    }

    public function requestRssEvents(string $url): void
    {
    }
}
