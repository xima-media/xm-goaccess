<?php

declare(strict_types=1);

namespace Xima\XmDkfzNetSite\Hook\KeSearch;

use Tpwd\KeSearch\Lib\Pluginbase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\RootlineUtility;

class AdditionalResultMarkerHook
{
    public function additionalResultMarker(
        array &$tempMarkerArray,
        array $row,
        Pluginbase $parentObject
    ): void {
        if ($row['type'] === 'page') {
            $tempMarkerArray['rootline'] = $this->getRootlineArrayForPage((int)$tempMarkerArray['orig_row']['uid']);
        }

        $tempMarkerArray['badgeLabel'] = $this->getBadgeLabelForRow($row);
    }

    protected function getRootlineArrayForPage(int $uid): array
    {
        $rootline = GeneralUtility::makeInstance(RootlineUtility::class, $uid)->get();

        return array_reverse($rootline, true);
    }

    protected function getBadgeLabelForRow(array $row): string
    {
        $badgeLabel = '';
        if ($row['type'] === 'news') {
            $badgeLabel = 'news';
        }
        if (str_starts_with($row['type'], 'file:')) {
            $badgeLabel = substr($row['type'], 5);
        }
        if ($row['type'] === 'xmkesearchremote') {
            $tags = GeneralUtility::trimExplode(',', $row['tags'], true);
            if (in_array('#wiki#', $tags)) {
                $badgeLabel = 'WIKI ITCF';
            }
            if (in_array('#dkfzwebsite#', $tags)) {
                $badgeLabel = 'dkfz.de';
            }
        }

        return $badgeLabel;
    }
}
