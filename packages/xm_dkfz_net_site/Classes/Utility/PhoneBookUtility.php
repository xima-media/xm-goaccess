<?php

namespace Xima\XmDkfzNetSite\Utility;

use DOMXPath;
use Symfony\Component\Console\Helper\ProgressBar;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use Xima\XmDkfzNetSite\Domain\Model\Dto\PhoneBookCompareResult;
use Xima\XmDkfzNetSite\Domain\Model\Dto\PhoneBookPerson;

class PhoneBookUtility
{
    protected ExtensionConfiguration $extensionConfiguration;

    protected ConfigurationManager $configurationManager;

    protected TypoScriptService $typoScriptService;

    protected ?DOMXPath $xpath = null;

    public function __construct(
        ExtensionConfiguration $extensionConfiguration,
        ConfigurationManager $configurationManager,
        TypoScriptService $typoScriptService
    ) {
        $this->extensionConfiguration = $extensionConfiguration;
        $this->configurationManager = $configurationManager;
        $this->typoScriptService = $typoScriptService;
    }

    public function loadXpath(): void
    {
        $url = $this->getApiUrl();
        $xml = $this->fetchXmlFromApi($url);
        $this->xpath = $this->getXpathFromXml($xml);
    }

    public function getUsersInXml()
    {
        return $this->xpath->query('//x:CPerson[x:AdAccountName[text()!=""]]');
    }

    /**
     * @return string[]
     */
    public static function getGroupIdsFromXmlAbteilungString(string $abteilung): array
    {
        preg_match('/([A-Z]{1,3}\d{1,3})(?:[\s\-])?/', $abteilung, $matches);
        if (count($matches) > 1) {
            array_shift($matches);
            return $matches;
        }
        return [];
    }

    public function getGroupIdentifierInXml(): array
    {
        $groupIdentifier = [];
        $nodes = $this->xpath->query('//x:Abteilung[text()!=""]');

        foreach ($nodes as $node) {
            $name = $node->nodeValue;
            $groupIdsOfNode = self::getGroupIdsFromXmlAbteilungString($name);
            $groupIdentifier = array_merge($groupIdentifier, $groupIdsOfNode);
        }

        return array_unique($groupIdentifier);
    }

    public function getUserStoragePid(): int
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

    public function compareFeUserWithXml(array $dbUsers, ?ProgressBar $progress): PhoneBookCompareResult
    {
        $result = new PhoneBookCompareResult();

        foreach ($dbUsers ?? [] as $dbUser) {
            $progress?->advance();

            $userNode = $this->xpath->query('//x:CPerson[x:Id[text()="' . $dbUser['dkfz_id'] . '"]]');

            // search for user id in xml and mark for update if changed (as skipped otherwise)
            if ($userNode->length === 1) {
                $nodeHash = md5($userNode->item(0)->nodeValue);
                if ($dbUser['dkfz_hash'] !== $nodeHash) {
                    $result->dkfzIdsToUpdate[] = $dbUser['dkfz_id'];
                    $result->phoneBookUsersById[$dbUser['dkfz_id']] = PhoneBookPerson::createFromXpathNode(
                        $this->xpath,
                        $userNode->item(0)
                    );
                } else {
                    $result->dkfzIdsToSkip[] = $dbUser['dkfz_id'];
                }
                continue;
            }

            // delete user from database if not found in xml
            $result->dkfzIdsToDelete[] = $dbUser['dkfz_id'];
        }

        foreach ($this->getUsersInXml() ?? [] as $xmlUserNode) {
            $userId = $this->xpath->query('x:Id', $xmlUserNode)->item(0)->nodeValue;

            // skip creation if already marked to update or to skip
            $idsToIgnore = array_merge($result->dkfzIdsToUpdate, $result->dkfzIdsToSkip);
            if (in_array($userId, $idsToIgnore, true)) {
                continue;
            }

            $result->dkfzIdsToCreate[] = $userId;
            $result->phoneBookUsersById[$userId] = PhoneBookPerson::createFromXpathNode($this->xpath, $xmlUserNode);
        }

        return $result;
    }

    public function compareFeGroupsWithXml(array $dbGroups, ?ProgressBar $progress): PhoneBookCompareResult
    {
        $result = new PhoneBookCompareResult();

        $xmlGroups = $this->getGroupIdentifierInXml();
        $dbGroupsIdentifier = array_map(function ($dbGroup) {
            return $dbGroup['dkfz_id'];
        }, $dbGroups);

        $result->dkfzIdsToDelete = array_filter($dbGroupsIdentifier, function ($identifier) use ($xmlGroups) {
            return !in_array($identifier, $xmlGroups);
        });

        $result->dkfzIdsToCreate = array_filter($xmlGroups, function ($xmlGroup) use ($dbGroupsIdentifier) {
            return !in_array($xmlGroup, $dbGroupsIdentifier);
        });

        return $result;
    }
}
