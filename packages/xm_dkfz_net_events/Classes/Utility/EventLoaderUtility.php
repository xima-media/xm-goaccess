<?php

namespace Xima\XmDkfzNetEvents\Utility;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use ReflectionClass;
use ReflectionProperty;
use Symfony\Component\DomCrawler\Crawler;
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
            $url = str_starts_with($url, '/') ? 'https://' . $_SERVER['SERVER_NAME'] . $url : $url;
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

        $crawler = new Crawler($xml);

        $this->events = $crawler->filter('item')->each(function (Crawler $parentCrawler) {
            return self::convertItemToEvent($parentCrawler);
        });
    }

    public static function convertItemToEvent(Crawler $crawler): Event
    {
        $event = new Event();
        $eventReflection = new ReflectionClass(Event::class);

        $children = $crawler->filter('item')->children();

        foreach ($children as $key => $node) {
            $xmlPropertyName = $node->tagName;

            if (!$eventReflection->hasProperty($xmlPropertyName)) {
                continue;
            }

            if ($eventReflection->getProperty($xmlPropertyName)->getType()->getName() === 'string') {
                $event->$xmlPropertyName = $node->nodeValue;
                continue;
            }

            if ($eventReflection->getProperty($xmlPropertyName)->getType()->getName() === 'DateTime') {
                try {
                    $event->$xmlPropertyName = new \DateTime($node->nodeValue);
                } catch (\Exception) {
                }
            }
        }

        return $event;
    }
}
