<?php declare(strict_types=1);

namespace Xima\XmDkfzNetSite\Tca;

/***
 *
 * This file is part of the "xm_dkfz_net_site" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2022 Markus Hackel <markus.hackel@xima.de>, XIMA MEDIA GmbH
 *
 ***/
class TcaUtility
{
    public static array $colors = [
        'primary', 'green', 'magenta', 'cyan', 'gray-500', 'blue-900', 'orange', 'green-light',
    ];

    public static function getItemsForColorField(bool $prependEmptyItem = true): array
    {
        $items = [];

        if ($prependEmptyItem) {
            $items[] = ['', ''];
        }

        $ll = 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:pages.color.';

        foreach (self::$colors as $color) {
            $items[] = [
                $ll . $color, $color
            ];
        }

        return $items;
    }

    public static function getRandomColor()
    {
        return self::$colors[array_rand(self::$colors)];
    }
}
