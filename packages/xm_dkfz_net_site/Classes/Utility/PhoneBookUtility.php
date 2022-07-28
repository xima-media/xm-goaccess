<?php

namespace Xima\XmDkfzNetSite\Utility;

use DOMNodeList;
use DOMXPath;
use JsonMapper;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use Xima\XmDkfzNetSite\Domain\Model\Dto\PhoneBookAbteilung;
use Xima\XmDkfzNetSite\Domain\Model\Dto\PhoneBookCompareResult;
use Xima\XmDkfzNetSite\Domain\Model\Dto\PhoneBookEntry;
use Xima\XmDkfzNetSite\Domain\Model\Dto\PhoneBookPerson;

class PhoneBookUtility
{
    protected ExtensionConfiguration $extensionConfiguration;

    protected ConfigurationManager $configurationManager;

    protected TypoScriptService $typoScriptService;

    protected LoggerInterface $logger;

    /** @var array<int, PhoneBookEntry> */
    protected array $phoneBookUser = [];

    /** @var array<int, PhoneBookEntry> */
    protected array $phoneBookPlace = [];

    /** @var array<string, PhoneBookAbteilung> */
    protected array $phoneBookAbteilungen = [];

    public function __construct(
        ExtensionConfiguration $extensionConfiguration,
        ConfigurationManager $configurationManager,
        TypoScriptService $typoScriptService,
        LoggerInterface $logger,
    ) {
        $this->extensionConfiguration = $extensionConfiguration;
        $this->configurationManager = $configurationManager;
        $this->typoScriptService = $typoScriptService;
        $this->logger = $logger;
    }

    public function loadJson(): void
    {
        $url = $this->getApiUrl();
        $jsonString = $this->fetchFileFromApi($url);
        $jsonArray = $this->decodeJsonString($jsonString);
        $phoneBookEntries = $this->mapJsonToEntryDto($jsonArray);

        $this->setAbteilungenOrdered($phoneBookEntries);
        $this->setUsersOrdered($phoneBookEntries);
        $this->setPlaccesOrdered($phoneBookEntries);
    }

    /**
     * @param array<PhoneBookEntry> $entries
     */
    protected function setUsersOrdered(array $entries): void
    {
        foreach ($entries as $entry) {
            if ($entry->isUser()) {
                $this->phoneBookUser[$entry->id] = $entry;
            }
        }
    }

    /**
     * @param array<PhoneBookEntry> $entries
     */
    protected function setPlaccesOrdered(array $entries): void
    {
        foreach ($entries as $entry) {
            if (!$entry->isUser()) {
                $this->phoneBookPlace[$entry->id] = $entry;
            }
        }
    }

    /**
     * @param array<int, mixed> $json
     * @return array<PhoneBookEntry>
     */
    protected function mapJsonToEntryDto(array $json): array
    {
        $mapper = new JsonMapper();

        try {
            $entries = $mapper->mapArray(
                $json,
                [],
                PhoneBookEntry::class
            );
        } catch (\JsonMapper_Exception $e) {
            $this->logger->error('Could not map json to PhoneBookEntry::class', ['code' => 1658818340, 'error' => $e]);
            return [];
        }

        if (!is_array($entries)) {
            $this->logger->error('Mapped PhoneBookEntrys are not valid', ['code' => 1658828352]);
            return [];
        }

        return $entries;
    }

    /**
     * @return array<int, mixed>
     */
    protected function decodeJsonString(string $jsonString): array
    {
        $jsonArray = json_decode($jsonString);

        if (!is_array($jsonArray)) {
            $this->logger->error('Decoded json is not valid', ['code' => 1658820330]);
            return [];
        }

        return $jsonArray;
    }

    public function getUserCountInJson(): int
    {
        return count($this->phoneBookUser);
    }

    /**
     * @param array<PhoneBookEntry> $entries
     * @return void
     */
    public function setAbteilungenOrdered(array $entries): void
    {
        foreach ($entries as $entry) {
            foreach ($entry->abteilung as $abteilung) {
                $this->phoneBookAbteilungen[$abteilung->nummer] = $abteilung;
            }
        }
    }

    /**
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    public function getUserStoragePid(Command $commandClass): int
    {
        $typoscript = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
        $settings = $this->typoScriptService->convertTypoScriptArrayToPlainArray($typoscript);
        return (int)$settings['plugin']['tx_bwguild']['persistence']['storagePid'];
    }

    public function getSubGroupForGroups(Command $commandClass): string
    {
        return '';
    }

    /**
     * @throws \TYPO3\CMS\Core\Exception
     */
    protected function fetchFileFromApi(string $url): string
    {
        $fileContent = file_get_contents($url);

        if (!$fileContent) {
            throw new \TYPO3\CMS\Core\Exception(
                'Could not fetch data from API ("' . $url . '")',
                1658212643
            );
        }

        return $fileContent;
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
     * @param array<int, array{dkfz_id: int, dkfz_hash: string}> $dbUsers
     * @param array<int, array{dkfz_id: string, uid: int}> $dbGroups
     * @return \Xima\XmDkfzNetSite\Domain\Model\Dto\PhoneBookCompareResult
     */
    public function compareDbUsersWithPhoneBookUsers(
        array $dbUsers,
        array $dbGroups,
    ): PhoneBookCompareResult {
        $result = new PhoneBookCompareResult();

        foreach ($dbUsers as $dbUser) {

            $entry = $this->phoneBookUser[(int)$dbUser['dkfz_id']] ?? false;

            // delete user from database if user not found
            if (!$entry) {
                $result->dkfzIdsToDelete[] = $dbUser['dkfz_id'];
                continue;
            }

            // search for user id in xml and mark for update if changed (as skipped otherwise)
            if ($dbUser['dkfz_hash'] !== $entry->getHash()) {
                $result->dkfzIdsToUpdate[] = $dbUser['dkfz_id'];
            } else {
                $result->dkfzIdsToSkip[] = $dbUser['dkfz_id'];
            }
        }

        // skip creation if already marked to update or to skip
        $idsToIgnore = array_merge($result->dkfzIdsToUpdate, $result->dkfzIdsToSkip);
        foreach ($this->phoneBookUser as $id => $phoneBookEntry) {
            if (in_array($id, $idsToIgnore, true)) {
                continue;
            }
            $result->dkfzIdsToCreate[] = $id;
        }

        return $result;
    }

    /**
     * @param array<int, array{dkfz_number: string, uid: int}> $dbGroups
     * @return \Xima\XmDkfzNetSite\Domain\Model\Dto\PhoneBookCompareResult
     */
    public function compareDbGroupsWithJson(array $dbGroups): PhoneBookCompareResult
    {
        $result = new PhoneBookCompareResult();
        $jsonGroups = array_keys($this->phoneBookAbteilungen);

        $dbGroupsIdentifier = array_map(function ($dbGroup) {
            return $dbGroup['dkfz_number'];
        }, $dbGroups);

        $result->dkfzNumbersToDelete = array_filter($dbGroupsIdentifier, function ($identifier) use ($jsonGroups) {
            return !in_array($identifier, $jsonGroups);
        });

        $result->dkfzNumbersToCreate = array_filter($jsonGroups, function ($xmlGroup) use ($dbGroupsIdentifier) {
            return !in_array($xmlGroup, $dbGroupsIdentifier);
        });

        return $result;
    }

    /**
     * @return array<string>
     */
    public function getGroupIdentifierInJson(): array
    {
        return array_keys($this->phoneBookAbteilungen);
    }

    /**
     * @param array<string|int> $ids
     * @return array<PhoneBookEntry>
     */
    public function getPhoneBookUsersByIds(array $ids): array
    {
        return array_filter($this->phoneBookUser, function ($entry) use ($ids) {
            return in_array($entry->id, $ids);
        });
    }

}
