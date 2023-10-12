<?php

namespace Xima\XmDkfzNetSite\Utility;

use JsonMapper;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException;
use Xima\XmDkfzNetSite\Domain\Model\Dto\PhoneBookAbteilung;
use Xima\XmDkfzNetSite\Domain\Model\Dto\PhoneBookCompareResult;
use Xima\XmDkfzNetSite\Domain\Model\Dto\PhoneBookEntry;

class PhoneBookUtility
{
    protected ExtensionConfiguration $extensionConfiguration;

    protected ConfigurationManager $configurationManager;

    protected TypoScriptService $typoScriptService;

    protected LoggerInterface $logger;

    /** @var array<int, PhoneBookEntry> */
    protected array $phoneBookEntries = [];

    /** @var array<string, PhoneBookAbteilung> */
    protected array $phoneBookAbteilungen = [];

    protected bool $filterEntriesForPlaces = false;

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

    /**
     * @throws \TYPO3\CMS\Core\Exception
     * @throws ExtensionConfigurationPathDoesNotExistException
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws \JsonException
     */
    public function loadJson(): void
    {
        $url = $this->getApiUrl();
        $jsonString = $this->fetchFileFromApi($url);
        $jsonArray = $this->decodeJsonString($jsonString);
        $phoneBookEntries = $this->mapJsonToEntryDto($jsonArray);

        $this->setAbteilungenOrdered($phoneBookEntries);
        $this->setEntriesOrdered($phoneBookEntries);
    }

    /**
     * @param array<PhoneBookEntry> $entries
     */
    protected function setEntriesOrdered(array $entries): void
    {
        foreach ($entries as $entry) {
            $filterForUser = !$this->filterEntriesForPlaces && $entry->isUser();
            $filterForPlace = $this->filterEntriesForPlaces && !$entry->isUser();
            if ($filterForUser || $filterForPlace) {
                $this->phoneBookEntries[$entry->id] = $entry;
            }
        }
    }

    /**
     * @param array<int, mixed> $json
     * @return array<PhoneBookEntry>
     * @throws \Exception
     */
    protected function mapJsonToEntryDto(array $json): array
    {
        $mapper = new JsonMapper();

        $entries = $mapper->mapArray(
            $json,
            [],
            PhoneBookEntry::class
        );

        if (!is_array($entries)) {
            throw new \Exception('Mapped PhoneBookEntries are not valid', 1659019225);
        }

        return $entries;
    }

    /**
     * @return array<int, mixed>
     * @throws \JsonException
     * @throws \Exception
     */
    protected function decodeJsonString(string $jsonString): array
    {
        $jsonArray = json_decode($jsonString, null, 512, JSON_THROW_ON_ERROR);

        if (!is_array($jsonArray)) {
            throw new \Exception('Decoded json is not valid', 1658820330);
        }

        return $jsonArray;
    }

    public function getPhoneBookEntryCount(): int
    {
        return count($this->phoneBookEntries);
    }

    /**
     * @param array<PhoneBookEntry> $entries
     */
    public function setAbteilungenOrdered(array $entries): void
    {
        foreach ($entries as $entry) {
            foreach ($entry->abteilung as $abteilung) {
                $identifier = $abteilung->getUniqueIdentifier();
                if (!$identifier) {
                    $this->logger->error('Could not set Abteilung because of missing identifier', [$abteilung]);
                }
                $this->phoneBookAbteilungen[$identifier] = $abteilung;
            }
        }
    }

    /**
     * @throws InvalidConfigurationTypeException
     */
    public function getUserStoragePid(Command $commandClass): int
    {
        $typoscript = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
        $settings = $this->typoScriptService->convertTypoScriptArrayToPlainArray($typoscript);
        return (int)$settings['plugin']['tx_bwguild']['persistence']['storagePid'];
    }

    /**
     * @throws \TYPO3\CMS\Core\Exception
     */
    protected function fetchFileFromApi(string $url): string
    {
        $url = str_starts_with('http', $url) ? $url : Environment::getPublicPath() . '/' . $url;
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
     * @throws ExtensionConfigurationPathDoesNotExistException
     * @throws ExtensionConfigurationExtensionNotConfiguredException
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
     * @return PhoneBookCompareResult
     */
    public function compareDbUsersWithPhoneBookEntries(
        array $dbUsers
    ): PhoneBookCompareResult {
        $result = new PhoneBookCompareResult();

        foreach ($dbUsers as $dbUser) {
            $entry = $this->phoneBookEntries[(int)$dbUser['dkfz_id']] ?? false;

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
        foreach ($this->phoneBookEntries as $id => $phoneBookEntry) {
            if (in_array($id, $idsToIgnore, true)) {
                continue;
            }
            $result->dkfzIdsToCreate[] = $id;
        }

        return $result;
    }

    /**
     * @param array<int, array{dkfz_number: string, uid: int, dkfz_hash: string}> $dbGroups
     * @return PhoneBookCompareResult
     */
    public function compareDbGroupsWithJson(array $dbGroups): PhoneBookCompareResult
    {
        $result = new PhoneBookCompareResult();

        foreach ($dbGroups as $dbGroup) {
            $groupIdentifier = $dbGroup['dkfz_unique_identifier'];
            $abteilung = $this->phoneBookAbteilungen[$groupIdentifier] ?? false;

            // delete group from database if group not found
            if (!$abteilung) {
                $result->dkfzGroupIdentifiersToDelete[] = $groupIdentifier;
                continue;
            }

            // search for group number in xml and mark for update if changed (as skipped otherwise)
            if ($dbGroup['dkfz_hash'] !== $abteilung->getHash()) {
                $result->dkfzGroupIdentifiersToUpdate[] = $groupIdentifier;
            } else {
                $result->dkfzGroupIdentifiersToSkip[] = $groupIdentifier;
            }
        }

        // skip creation if already marked to update or to skip
        $identifiersToIgnore = array_merge($result->dkfzGroupIdentifiersToUpdate, $result->dkfzGroupIdentifiersToSkip);
        foreach ($this->phoneBookAbteilungen as $identifier => $phoneBookAbteilung) {
            if (in_array($identifier, $identifiersToIgnore, true)) {
                continue;
            }
            $result->dkfzGroupIdentifierToCreate[] = $identifier;
        }

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
     * @param array<int> $ids
     * @return array<PhoneBookEntry>
     */
    public function getPhoneBookEntriesByIds(array $ids): array
    {
        return array_filter($this->phoneBookEntries, function ($entry) use ($ids) {
            return in_array($entry->id, $ids);
        });
    }

    public function updatePhoneBookEntry(PhoneBookEntry $entry): void
    {
        $this->phoneBookEntries[$entry->id] = $entry;
    }

    /**
     * @param array<string> $identifiers
     * @return array<PhoneBookAbteilung>
     */
    public function getPhoneBookAbteilungenByIdentifiers(array $identifiers): array
    {
        return array_filter($this->phoneBookAbteilungen, function ($groupIdentifier) use ($identifiers) {
            return in_array($groupIdentifier, $identifiers);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @param bool $filterEntriesForPlaces
     */
    public function setFilterEntriesForPlaces(bool $filterEntriesForPlaces): void
    {
        $this->phoneBookEntries = [];
        $this->filterEntriesForPlaces = $filterEntriesForPlaces;
    }

    public function getFilterEntriesForPlaces(): bool
    {
        return $this->filterEntriesForPlaces;
    }

    /**
     * @param array<int, array{dkfz_unique_identifier: string, uid: int, dkfz_hash: string}> $dbGroups
     * @param int[] $defaultUserGroups
     */
    public function setUserGroupRelations(array $dbGroups, array $defaultUserGroups): void
    {
        $dbGroupUidsByGroupIdentifier = [];
        foreach ($dbGroups as $dbGroup) {
            $dbGroupUidsByGroupIdentifier[$dbGroup['dkfz_unique_identifier']] = $dbGroup['uid'];
        }

        foreach ($this->phoneBookEntries as $entry) {
            $dbGroupsOfUser = $defaultUserGroups;
            foreach ($entry->getDkfzGroupIdentifierOfAbteilungen() as $abteilungsGroupIdentifier) {
                if (isset($dbGroupUidsByGroupIdentifier[$abteilungsGroupIdentifier])) {
                    $dbGroupsOfUser[] = $dbGroupUidsByGroupIdentifier[$abteilungsGroupIdentifier];
                }
            }
            $entry->usergroup = implode(',', $dbGroupsOfUser);
        }
    }

    /**
     * @param array<int, array{dkfz_id: int, uid: int}> $dbUsers
     */
    public function setGroupUserRelations(array $dbUsers): void
    {
        $dbUserUidsById = [];
        foreach ($dbUsers as $dbUser) {
            $dbUserUidsById[$dbUser['dkfz_id']] = $dbUser['uid'];
        }

        foreach ($this->phoneBookAbteilungen as $bookAbteilung) {
            $leitungUsers = [];
            foreach ($bookAbteilung->leitung as $phoneBookAbteilungPerson) {
                if (isset($dbUserUidsById[$phoneBookAbteilungPerson->id])) {
                    $leitungUsers[] = $dbUserUidsById[$phoneBookAbteilungPerson->id];
                }
            }
            $bookAbteilung->managers = implode(',', $leitungUsers);

            $sekretariatUsers = [];
            foreach ($bookAbteilung->sekretariat as $phoneBookAbteilungPerson) {
                if (isset($dbUserUidsById[$phoneBookAbteilungPerson->id])) {
                    $sekretariatUsers[] = $dbUserUidsById[$phoneBookAbteilungPerson->id];
                }
            }
            $bookAbteilung->secretaries = implode(',', $sekretariatUsers);

            $koordinationUsers = [];
            foreach ($bookAbteilung->koordination as $phoneBookAbteilungPerson) {
                if (isset($dbUserUidsById[$phoneBookAbteilungPerson->id])) {
                    $koordinationUsers[] = $dbUserUidsById[$phoneBookAbteilungPerson->id];
                }
            }
            $bookAbteilung->coordinators = implode(',', $koordinationUsers);

            $assistenzUsers = [];
            foreach ($bookAbteilung->assistenz as $phoneBookAbteilungPerson) {
                if (isset($dbUserUidsById[$phoneBookAbteilungPerson->id])) {
                    $assistenzUsers[] = $dbUserUidsById[$phoneBookAbteilungPerson->id];
                }
            }
            $bookAbteilung->assistants = implode(',', $assistenzUsers);
        }
    }

    public function getStorageIdentifierForGroups(): string
    {
        $extConf = (array)$this->extensionConfiguration->get('xm_dkfz_net_site');
        if (!$extConf['storage_identifier_for_imported_groups'] || !is_string($extConf['storage_identifier_for_imported_groups'])) {
            return '';
        }
        return $extConf['storage_identifier_for_imported_groups'];
    }

    public function getDkfzExtensionSetting(string $settingName): string
    {
        $extConf = (array)$this->extensionConfiguration->get('xm_dkfz_net_site');
        if (!$extConf[$settingName] || !is_string($extConf[$settingName])) {
            return '';
        }
        return $extConf[$settingName];
    }

    /**
     * @return PhoneBookEntry[]
     */
    public function getPhoneBookEntries(): array
    {
        return $this->phoneBookEntries;
    }

    /**
     * @return array<string|int, int>
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     */
    public function getApiStat(): array
    {
        $url = $this->getApiUrl();
        $filePath = realpath(Environment::getPublicPath() . '/' . $url) ?: '';
        return stat($filePath) ?: [];
    }
}
