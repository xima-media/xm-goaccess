<?php

namespace Xima\XmGoaccess\Widgets\Provider;

use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Dashboard\WidgetApi;
use TYPO3\CMS\Dashboard\Widgets\ChartDataProviderInterface;

class LineChartDataProvider extends AbstractGoaccessDataProvider implements ChartDataProviderInterface
{
    /**
     * @param int $days
     * @return array{labels: string[], hits: int[], visitors: int[], bytes: int[]}
     * @throws \Exception
     */
    public function getGoaccessChartData(int $days = 31): array
    {
        $type = $this->goaccessType;
        $data = $this->readJsonData();
        $chartData = [
            'labels' => [],
            'hits' => [],
            'visitors' => [],
            'bytes' => [],
        ];

        if (!isset($data[$type])) {
            return $chartData;
        }

        $rawData = array_slice($data[$type]->data, 0, $days);

        try {
            new \DateTime($rawData[0]?->data);
            $reverse = true;
        } catch (\Exception) {
            $reverse = false;
        }

        if ($reverse) {
            $rawData = array_reverse($rawData);
        }

        foreach ($rawData as $day) {
            try {
                $date = (new \DateTime($day->data))->format('d.m.');
            } catch (\Exception) {
                $date = $day->data;
            }

            $chartData['labels'][] = $date;
            foreach (['hits', 'visitors', 'bytes'] as $dataTypes) {
                $chartData[$dataTypes][] = $day->$dataTypes->count;
            }
        }

        return $chartData;
    }

    /**
     * @return array{labels: string[], datasets: array<mixed>}
     * @throws \Exception
     */
    public function getChartData(): array
    {
        $data = $this->getGoaccessChartData();

        $data = [
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

        $typo3Version = (int)VersionNumberUtility::convertVersionStringToArray(VersionNumberUtility::getCurrentTypo3Version())['version_main'];
        if ($typo3Version >= 12) {
            $data['datasets'][0]['fill'] = 'origin';
            $data['datasets'][1]['fill'] = 'origin';
        }

        return $data;
    }
}
