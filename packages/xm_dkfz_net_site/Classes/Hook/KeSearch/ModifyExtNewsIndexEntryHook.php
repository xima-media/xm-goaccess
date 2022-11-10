<?php

declare(strict_types=1);

namespace Xima\XmDkfzNetSite\Hook\KeSearch;

use Tpwd\KeSearch\Indexer\Types\News;
use Tpwd\KeSearch\Lib\SearchHelper;

class ModifyExtNewsIndexEntryHook
{
    public function modifyExtNewsIndexEntry(
        string &$title,
        string &$abstract,
        string &$fullContent,
        string &$params,
        string &$tags,
        array $newsRecord,
        array &$additionalFields,
        array $indexerConfig,
        array $categoryData,
        News $parentObject
    ): void {
        $tags = SearchHelper::addTag('news', $tags);
        $tags = SearchHelper::addTag('intranet', $tags);
    }
}
