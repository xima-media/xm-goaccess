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
    }

    protected function getRootlineArrayForPage(int $uid): array
    {
        $rootline = GeneralUtility::makeInstance(RootlineUtility::class, $uid)->get();

        return array_reverse($rootline, true);
    }
}
