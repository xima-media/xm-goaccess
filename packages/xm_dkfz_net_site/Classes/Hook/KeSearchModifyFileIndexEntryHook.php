<?php
declare(strict_types=1);

namespace Xima\XmDkfzNetSite\Hook;

use Tpwd\KeSearch\Lib\SearchHelper;

class KeSearchModifyFileIndexEntryHook
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
        $tags = SearchHelper::addTag('intranet', $tags);
    }
}
