<?php

namespace Xima\XmGoaccess\Widgets\Provider;

use TYPO3\CMS\Dashboard\WidgetApi;
use TYPO3\CMS\Dashboard\Widgets\ChartDataProviderInterface;

class LineChartDataProvider extends AbstractGoaccessDataProvider implements ChartDataProviderInterface
{

    /**
     * @return array{labels: string[], datasets: array<mixed>}
     * @throws \Exception
     */
    public function getChartData(): array
    {
        $data = $this->getChartDataForType();

        return [
            'labels' => $data['labels'],
            'datasets' => [
                [
                    'label' => $this->languageService->sL('LLL:EXT:xm_goaccess/Resources/Private/Language/locallang.xlf:visitors'),
                    'borderColor' => WidgetApi::getDefaultChartColors()[0],
                    'backgroundColor' => self::hex2rgba(WidgetApi::getDefaultChartColors()[0], 0.1),
                    'parsing' => ['yAxisKey' => 'A'],
                    'borderWidth' => 1,
                    'data' => $data['visitors'],
                ],
                [
                    'label' => $this->languageService->sL('LLL:EXT:xm_goaccess/Resources/Private/Language/locallang.xlf:hits'),
                    'borderColor' => WidgetApi::getDefaultChartColors()[1],
                    'backgroundColor' => self::hex2rgba(WidgetApi::getDefaultChartColors()[1], 0.1),
                    'yAxisID' => 'right',
                    'borderWidth' => 1,
                    'data' => $data['hits'],
                ],
            ],
        ];
    }

    public static function hex2rgba(string $color, float $opacity): string
    {

        $default = 'rgb(0,0,0)';

        //Return default if no color provided
        if (empty($color)) {
            return $default;
        }

        //Sanitize $color if "#" is provided
        if ($color[0] == '#') {
            $color = substr($color, 1);
        }

        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
            $hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
        } elseif (strlen($color) == 3) {
            $hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
        } else {
            return $default;
        }

        //Convert hexadec to rgb
        $rgb = array_map('hexdec', $hex);

        //Check if opacity is set(rgba or rgb)
        if ($opacity) {
            if (abs($opacity) > 1) {
                $opacity = 1.0;
            }
            $output = 'rgba(' . implode(",", $rgb) . ',' . $opacity . ')';
        } else {
            $output = 'rgb(' . implode(",", $rgb) . ')';
        }

        //Return rgb(a) color string
        return $output;
    }
}
