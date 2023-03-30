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

        $links = $crawler->filter('url')->each(function (Crawler $parentCrawler) use ($sitemapUrl, $config) {
            $link = new SitemapLink($sitemapUrl);
            $link->loc = (string)$parentCrawler->children('loc')->getNode(0)?->nodeValue ?: '';
            $link->lastmod = (int)($parentCrawler->children('lastmod')->getNode(0)?->nodeValue ?: 0);

            return self::isValidLink($link, $config) ? $link : null;
        });

        return array_filter($links);
    }

    protected static function isValidLink(SitemapLink $link, array $config): bool
    {
        $linkParts = explode('.', $link->loc);
        return count($linkParts) === 1 || in_array(end($linkParts), ['html', 'php']);
    }
}
