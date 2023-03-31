<?php

namespace Xima\XmKesearchRemote\Crawler;

use Xima\XmKesearchRemote\Domain\Model\Dto\SitemapLink;

interface CrawlerInterface
{
    /**
     * @param mixed[] $config
     * @return SitemapLink[]
     */
    public function convertXmlToLinks(string $xml, array $config): array;
}
