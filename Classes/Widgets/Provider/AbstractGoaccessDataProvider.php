<?php

namespace Xima\XmGoaccess\Widgets\Provider;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;

abstract class AbstractGoaccessDataProvider
{
    protected ExtensionConfiguration $extensionConfiguration;

    protected string $goaccessType = '';

    protected LanguageService $languageService;


    public function __construct(
        ExtensionConfiguration $extensionConfiguration,
        LanguageServiceFactory $languageServiceFactory
    ) {
        $this->extensionConfiguration = $extensionConfiguration;
        $this->languageService = $languageServiceFactory->createFromUserPreferences($GLOBALS['BE_USER']);
    }

    public function readJsonData(): array
    {
        $extConf = (array)$this->extensionConfiguration->get('xm_goaccess');

        if (!isset($extConf['json_path']) || !$extConf['json_path']) {
            throw new \Exception('Goaccess json_path is not configured', 1662881054);
        }

        $filePath = Environment::getPublicPath() . '/' . $extConf['json_path'];
        if (!file_exists($filePath)) {
            throw new \Exception('File "' . $filePath . '" not found', 1662881054);
        }

        $content = file_get_contents($filePath);

        return $content ? (array)json_decode($content) : [];
    }

    /**
     * @param string $type
     * @param int $days
     * @return array{labels: string[], hits: int[], visitors: int[], bytes: int[]}
     * @throws \Exception
     */
    public function getChartDataForType(int $days = 31): array
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

        $reverse = !is_string($rawData[0]?->data);

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
     * @return string
     */
    public function getGoaccessType(): string
    {
        return $this->goaccessType;
    }

    /**
     * @param string $goaccessType
     */
    public function setGoaccessType(string $goaccessType): void
    {
        $this->goaccessType = $goaccessType;
    }

}
