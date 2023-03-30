<?php

namespace Xima\XmKesearchRemote\Crawler;

use Symfony\Component\DomCrawler\Crawler;
use Xima\XmKesearchRemote\Domain\Model\Dto\SitemapLink;

class SitemapCrawler implements CrawlerInterface
{

    public function convertXmlToLinks(string $xml, array $config): array
    {
        if (!$xml) {
            return [];
        }

        $sitemapUrl = $config['tx_xmkesearchremote_sitemap'] ?? '';

        $crawler = new Crawler($xml);

        return $crawler->filter('url')->each(function (Crawler $parentCrawler) use ($sitemapUrl) {
            $link = new SitemapLink($sitemapUrl);
            $link->loc = (string)$parentCrawler->children('loc')->getNode(0)?->nodeValue ?: '';
            $link->lastmod = (int)($parentCrawler->children('lastmod')->getNode(0)?->nodeValue ?: 0);
            return $link;
        });
    }

    public function filterLinks(array $links): array
    {
        return array_filter($links, function ($link) {
            $linkParts = explode('.', $link->loc);
            return count($linkParts) === 1 || in_array(end($linkParts), ['html', 'php']);
        });
    }
}
