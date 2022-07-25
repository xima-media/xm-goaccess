<?php

namespace Xima\XmDkfzNetSite\Utility;

use DOMNodeList;
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

    protected DOMXPath $xpath;

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

    /**
     * @return \DOMNodeList<\DOMNode>
     */
    public function getUsersInXml(): DOMNodeList
    {
        $nodes = $this->xpath->query('//x:CPerson[x:AdAccountName[text()!=""]]');
        return $nodes ?: new DOMNodeList();
    }

    /**
     * @return string[]
     */
    public static function getGroupIdsFromXmlAbteilungString(string $abteilung): array
    {
        preg_match_all('/([A-Z]{1,3}\d{1,3})(?:[\s\-])?/', $abteilung, $matches);
        if (count($matches) && count($matches[1])) {
            return $matches[1];
        }
        return [];
    }

    /**
     * @return array<string>
     */
    public function getGroupIdentifierInXml(): array
    {
        $groupIdentifier = [];
        $nodes = $this->xpath->query('//x:Abteilung[text()!=""]');

        if (!$nodes instanceof DOMNodeList) {
            return [];
        }

        foreach ($nodes as $node) {
            $name = (string)$node->nodeValue;
            $groupIdsOfNode = self::getGroupIdsFromXmlAbteilungString($name);
            $groupIdentifier = array_merge($groupIdentifier, $groupIdsOfNode);
        }

        return array_unique($groupIdentifier);
    }

    /**
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
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

    /**
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException
     */
    protected function getApiUrl(): string
    {
        $extConf = (array)$this->extensionConfiguration->get('xm_dkfz_net_site');
        if (!$extConf['phone_book_api_url'] || !is_string($extConf['phone_book_api_url'])) {
            return '';
        }
        return $extConf['phone_book_api_url'];
    }

    /**
     * @param array<array{dkfz_id: int, dkfz_hash: string}> $dbUsers
     * @param \Symfony\Component\Console\Helper\ProgressBar|null $progress
     * @return \Xima\XmDkfzNetSite\Domain\Model\Dto\PhoneBookCompareResult
     */
    public function compareFeUserWithXml(array $dbUsers, ?ProgressBar $progress): PhoneBookCompareResult
    {
        $result = new PhoneBookCompareResult();

        foreach ($dbUsers as $dbUser) {
            $progress?->advance();

            $userNode = $this->xpath->query('//x:CPerson[x:Id[text()="' . $dbUser['dkfz_id'] . '"]]');

            // delete user from database if node not found
            if ($userNode === false) {
                $result->dkfzIdsToDelete[] = $dbUser['dkfz_id'];
                continue;
            }

            // search for user id in xml and mark for update if changed (as skipped otherwise)
            if ($userNode->length === 1 && $userNode->item(0)) {
                $nodeDkfzId = $userNode->item(0)->nodeValue ? $userNode->item(0)->nodeValue : '';
                $nodeHash = md5($nodeDkfzId);
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

        foreach ($this->getUsersInXml() as $xmlUserNode) {
            $userIdNode = $this->xpath->query('x:Id', $xmlUserNode);

            if (!$userIdNode) {
                continue;
            }

            $userId = $userIdNode->item(0)?->nodeValue;

            // skip creation if already marked to update or to skip
            $idsToIgnore = array_merge($result->dkfzIdsToUpdate, $result->dkfzIdsToSkip);
            if (in_array($userId, $idsToIgnore, true)) {
                continue;
            }

            $result->dkfzIdsToCreate[] = (int)$userId;
            $result->phoneBookUsersById[$userId] = PhoneBookPerson::createFromXpathNode($this->xpath, $xmlUserNode);
        }

        return $result;
    }

    /**
     * @param array<int, array{dkfz_id: string, uid: int}> $dbGroups
     * @return \Xima\XmDkfzNetSite\Domain\Model\Dto\PhoneBookCompareResult
     */
    public function compareDbGroupsWithXml(array $dbGroups): PhoneBookCompareResult
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
