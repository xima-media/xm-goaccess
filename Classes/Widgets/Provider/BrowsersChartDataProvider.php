<?php

namespace Xima\XmGoaccess\Widgets\Provider;

use TYPO3\CMS\Dashboard\WidgetApi;
use TYPO3\CMS\Dashboard\Widgets\ChartDataProviderInterface;

class BrowsersChartDataProvider extends AbstractGoaccessDataProvider implements ChartDataProviderInterface
{
    public function getChartData(): array
    {
        $data = $this->getGoaccessChartData();

        return [
            'labels' => $data['labels'],
            'datasets' => $data['datasets'],
        ];
    }

    protected function getGoaccessChartData(): array
    {
        $data = $this->readJsonData();
        $type = 'browsers';

        $chartData = [
            'labels' => [],
            'datasets' => [['data' => []]],
        ];

        $rawData = $data[$type]->data;
        $defaultColors = WidgetApi::getDefaultChartColors();

        foreach ($rawData as $key => $browserData) {
            $chartData['labels'][] = $browserData->data;
            $chartData['datasets'][0]['data'][] = (int)$browserData->hits->percent;
            $chartData['datasets'][0]['backgroundColor'][$key] = self::hex2rgba($defaultColors[$key] ?? '#000', 0.1);
            $chartData['datasets'][0]['borderWidth'][$key] = 1;
            $chartData['datasets'][0]['borderColor'][$key] = $defaultColors[$key] ?? '#000';
        }

        return $chartData;
    }
}
