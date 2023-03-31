<?php

namespace Xima\XmDkfzNetSite\Crawler;

use Symfony\Component\DomCrawler\Crawler;
use Xima\XmKesearchRemote\Crawler\CrawlerInterface;
use Xima\XmKesearchRemote\Domain\Model\Dto\SitemapLink;

class DkfzWikiCrawler implements CrawlerInterface
{
    public function convertXmlToLinks(string $xml, array $config): array
    {
        if (!$xml) {
            return [];
        }

        $sitemapUrl = $config['tx_xmkesearchremote_sitemap'] ?? '';

        $crawler = new Crawler($xml);

        $links = $crawler->filter('site')->each(function (Crawler $parentCrawler) use ($sitemapUrl, $config) {
            $link = new SitemapLink($sitemapUrl);
            $link->loc = (string)$parentCrawler->children('link')->getNode(0)?->nodeValue ?: '';
            $link->lastmod = (int)($parentCrawler->children('lastmod')->getNode(0)?->nodeValue ?: 0);
            return self::isValidLink($link, $config) ? $link : null;
        });

        return array_filter($links);
    }

    /**
     * @param mixed[] $config
     */
    protected static function isValidLink(SitemapLink $link, array $config): bool
    {
        $languageUid = (int)$config['tx_xmkesearchremote_language'];

        if ($languageUid === 0 && !str_ends_with($link->loc, '/en')) {
            return true;
        }

        if ($languageUid === 1 && str_ends_with('-en', $link->loc)) {
            return true;
        }

        return true;
    }
}
