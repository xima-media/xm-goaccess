<?php

namespace Xima\XmDkfzNetEvents\Utility;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use ReflectionClass;
use Symfony\Component\DomCrawler\Crawler;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Core\Environment;
use Xima\XmDkfzNetEvents\Domain\Model\Dto\Event;

class EventLoaderUtility
{
    /**
     * @var array<Event>
     */
    protected array $events = [];

    protected FrontendInterface $cache;

    public function __construct(FrontendInterface $cache)
    {
        $this->cache = $cache;
    }

    public function getEvents(string $url): array
    {
        $this->loadEvents($url);

        return $this->events;
    }

    public function loadEvents(string $url, bool $useCache = true): void
    {
        $cacheIdentifier = md5($url);
        $this->events = $useCache && $this->cache->has($cacheIdentifier) ? $this->cache->get($cacheIdentifier) : [];

        if (empty($this->events)) {
            $this->requestRssEvents($url);
            $this->cache->set($cacheIdentifier, $this->events, [], 86400);
        }
    }

    public function requestRssEvents(string $url): void
    {
        try {
            $xml = '';
            if (!str_starts_with($url, '/')) {
                $client = new Client(['verify' => false]);
                $response = $client->request('GET', $url);
                $xml = $response->getBody()->getContents() ?: '';
            } else {
                $filePath = Environment::getPublicPath() . $url;
                $xml = file_exists($filePath) ? file_get_contents($filePath) : '';
            }
        } catch (GuzzleException $e) {
        }

        $this->convertXmlToEvents($xml ?: '');
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
            $xmlPropertyName = strtolower($node->tagName);

            if (!$eventReflection->hasProperty($xmlPropertyName)) {
                continue;
            }

            if ($eventReflection->getProperty($xmlPropertyName)->getType()->getName() === 'string') {
                $value = $node->nodeValue ?: $node->nextSibling->nodeValue;
                $event->$xmlPropertyName = $value;
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
