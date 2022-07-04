<?php

declare(strict_types=1);

namespace Xima\XmDkfzNetSite\Tca;

class TcaUtility
{
    public static array $colors = [
        'primary',
        'green',
        'magenta',
        'cyan',
        'gray-500',
        'blue-900',
        'orange',
        'green-light',
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
                $ll . $color,
                $color,
                'EXT:xm_dkfz_net_site/Resources/Public/Images/icon-color-' . $color . '.svg',
            ];
        }

        return $items;
    }

    public static function getRandomColor()
    {
        return self::$colors[array_rand(self::$colors)];
    }
}
