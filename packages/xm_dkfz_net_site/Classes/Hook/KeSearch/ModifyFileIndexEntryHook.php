<?php

declare(strict_types=1);

namespace Xima\XmDkfzNetSite\Hook\KeSearch;

use Tpwd\KeSearch\Lib\SearchHelper;

class ModifyFileIndexEntryHook
{
    public function modifyFileIndexEntryFromContentIndexer(
        $fileObject,
        $content,
        $fileIndexerObject,
        $feGroups,
        $ttContentRow,
        $storagePid,
        $title,
        &$tags,
        $abstract,
        $additionalFields
    ): void {
        $tags = SearchHelper::addTag('file', $tags);
        $tags = SearchHelper::addTag('intranet', $tags);
    }
}
