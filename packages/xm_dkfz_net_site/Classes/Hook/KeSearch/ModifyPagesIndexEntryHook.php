<?php

declare(strict_types=1);

namespace Xima\XmDkfzNetSite\Hook\KeSearch;

use Tpwd\KeSearch\Indexer\Types\Page;
use Tpwd\KeSearch\Lib\SearchHelper;

class ModifyPagesIndexEntryHook
{
    public function modifyPagesIndexEntry(
        int $uid,
        array &$pageContent,
        string &$tags,
        array $cachedPageRecords,
        array &$additionalFields,
        array $indexerConfig,
        array $indexEntryDefaultValues,
        Page $parentObject
    ): void {
        $tags = SearchHelper::addTag('page', $tags);
        $tags = SearchHelper::addTag('intranet', $tags);
    }
}
