<?php

namespace Xima\XmGoaccess\Widgets\Provider;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use Xima\XmGoaccess\Domain\Repository\MappingRepository;

abstract class AbstractGoaccessDataProvider
{
    protected ExtensionConfiguration $extensionConfiguration;

    protected string $goaccessType = '';

    protected LanguageService $languageService;

    protected MappingRepository $mappingRepository;

    public function __construct(
        ExtensionConfiguration $extensionConfiguration,
        LanguageServiceFactory $languageServiceFactory,
        MappingRepository $mappingRepository
    ) {
        $this->extensionConfiguration = $extensionConfiguration;
        $this->languageService = $languageServiceFactory->createFromUserPreferences($GLOBALS['BE_USER']);
        $this->mappingRepository = $mappingRepository;
    }

    public function readJsonData(): array
    {
        $extConf = (array)$this->extensionConfiguration->get('xm_goaccess');

        if (!isset($extConf['json_path']) || !$extConf['json_path']) {
            throw new \Exception('Goaccess json_path is not configured', 1662881054);
        }

        $filePath = str_starts_with($extConf['json_path'],
            '/') ? $extConf['json_path'] : Environment::getPublicPath() . '/' . $extConf['json_path'];
        if (!file_exists($filePath)) {
            throw new \Exception('File "' . $filePath . '" not found', 1662881054);
        }

        $content = file_get_contents($filePath);

        return $content ? (array)json_decode($content) : [];
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
            $hex = [$color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]];
        } elseif (strlen($color) == 3) {
            $hex = [$color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]];
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
            $output = 'rgba(' . implode(',', $rgb) . ',' . $opacity . ')';
        } else {
            $output = 'rgb(' . implode(',', $rgb) . ')';
        }

        //Return rgb(a) color string
        return $output;
    }
}
