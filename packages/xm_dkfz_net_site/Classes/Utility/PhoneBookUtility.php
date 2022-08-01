<?php

namespace Xima\XmDkfzNetSite\Utility;

use JsonMapper;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
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

    protected bool $filterEntriesForPlaces;

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
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException
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
            if ((!$this->filterEntriesForPlaces && $entry->isUser()) || ($this->filterEntriesForPlaces && !$entry->isUser())) {
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
     * @param array<int, array{dkfz_number: string, uid: int}> $dbGroups
     * @return \Xima\XmDkfzNetSite\Domain\Model\Dto\PhoneBookCompareResult
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
     * @param array<int> $ids
     * @return array<PhoneBookEntry>
     */
    public function getPhoneBookEntriesByIds(array $ids): array
    {
        return array_filter($this->phoneBookEntries, function ($entry) use ($ids) {
            return in_array($entry->id, $ids);
        });
    }

    /**
     * @param array<string> $numbers
     * @return array<PhoneBookAbteilung>
     */
    public function getPhoneBookAbteilungenByNumbers(array $numbers): array
    {
        return array_filter($this->phoneBookAbteilungen, function ($number) use ($numbers) {
            return in_array($number, $numbers);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @param bool $filterEntriesForPlaces
     */
    public function setFilterEntriesForPlaces(bool $filterEntriesForPlaces): void
    {
        $this->filterEntriesForPlaces = $filterEntriesForPlaces;
    }

    /**
     * @param array<int, array{dkfz_number: string, uid: int}> $dbGroups
     * @return void
     */
    public function setUserGroupRelations(array $dbGroups)
    {
        foreach ($this->phoneBookEntries as $entry) {
            $dbGroupsOfUser = [];
            foreach ($dbGroups as $dbGroup) {
                if (in_array($dbGroup['dkfz_number'], $entry->getNumbersOfAbteilungen())) {
                    $dbGroupsOfUser[] = $dbGroup['uid'];
                }
            }
            $entry->usergroup = implode(',', $dbGroupsOfUser);
        }
    }
}
