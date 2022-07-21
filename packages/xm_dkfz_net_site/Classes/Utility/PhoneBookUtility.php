<?php

namespace Xima\XmDkfzNetSite\Utility;

use DOMXPath;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use Xima\XmDkfzNetSite\Domain\Model\User;

class PhoneBookUtility
{
    protected ExtensionConfiguration $extensionConfiguration;

    protected ConfigurationManager $configurationManager;

    protected TypoScriptService $typoScriptService;

    public function __construct(
        ExtensionConfiguration $extensionConfiguration,
        ConfigurationManager $configurationManager,
        TypoScriptService $typoScriptService
    ) {
        $this->extensionConfiguration = $extensionConfiguration;
        $this->configurationManager = $configurationManager;
        $this->typoScriptService = $typoScriptService;
    }

    public function getXpath(): DOMXPath
    {
        $url = $this->getApiUrl();
        $xml = $this->fetchXmlFromApi($url);
        return $this->getXpathFromXml($xml);
    }

    protected function getUserStoragePid(): int
    {
        $typoscript = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
        $settings = $this->typoScriptService->convertTypoScriptArrayToPlainArray($typoscript);
        return (int)$settings['plugin']['tx_bwguild']['persistence']['storagePid'];
    }

    protected function getXpathFromXml(string $xml): DOMXPath
    {
        $doc = new \DOMDocument();
        $doc->loadXML($xml);
        $xpath = new DOMXPath($doc);
        $xpath->registerNamespace('x', 'http://schemas.datacontract.org/2004/07/TeleMailMvc.Models');
        return $xpath;
    }

    /**
     * @throws \TYPO3\CMS\Core\Exception
     */
    protected function fetchXmlFromApi(string $url): string
    {
        $xmlContent = file_get_contents($url);

        if (!$xmlContent) {
            throw new \TYPO3\CMS\Core\Exception(
                'Could not fetch XML from API ("' . $url . '")',
                1658212643
            );
        }

        return $xmlContent;
    }

    protected function getApiUrl(): string
    {
        $extConf = (array)$this->extensionConfiguration->get('xm_dkfz_net_site');
        return $extConf['phone_book_api_url'] ?? '';
    }

    protected function compareFeUserWithXml(array $dbUser)
    {

    }

}
