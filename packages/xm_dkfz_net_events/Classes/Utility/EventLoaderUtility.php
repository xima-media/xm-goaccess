<?php

namespace Xima\XmDkfzNetEvents\Utility;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
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
        $client = new Client();

        try {
            $response = $client->request('GET', $url);
            $xml = $response->getBody()->getContents();
        } catch (GuzzleException $e) {
        }

        $this->convertXmlToEvents($xml ?? '');
    }

    protected function convertXmlToEvents(string $xml): void
    {
        if (!$xml) {
            return;
        }

        $crawler = new Crawler($dom);
    }
}
