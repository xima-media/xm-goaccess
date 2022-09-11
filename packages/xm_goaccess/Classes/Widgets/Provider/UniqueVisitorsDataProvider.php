<?php

namespace Xima\XmGoaccess\Widgets\Provider;

use TYPO3\CMS\Dashboard\WidgetApi;
use TYPO3\CMS\Dashboard\Widgets\ChartDataProviderInterface;

class UniqueVisitorsDataProvider extends AbstractGoaccessDataProvider implements ChartDataProviderInterface
{

    /**
     * @throws \Exception
     * @return array{labels: string[], datasets: array<mixed>}
     */
    public function getChartData(): array
    {
        $data = $this->getChartDataForType('visitors');

        return [
            'labels' => $data['labels'],
            'datasets' => [
                [
                    'label' => 'Visitors',
                    'backgroundColor' => WidgetApi::getDefaultChartColors()[0],
                    'border' => 0,
                    'data' => $data['visitors'],
                ],
            ],
        ];
    }
}
