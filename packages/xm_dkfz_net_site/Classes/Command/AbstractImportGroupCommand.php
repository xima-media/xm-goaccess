<?php

namespace Xima\XmDkfzNetSite\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Resource\StorageRepository;
use Xima\XmDkfzNetSite\Domain\Model\Dto\PhoneBookCompareResult;
use Xima\XmDkfzNetSite\Domain\Repository\ImportableGroupInterface;
use Xima\XmDkfzNetSite\Domain\Repository\ImportableUserInterface;
use Xima\XmDkfzNetSite\Utility\PhoneBookUtility;

abstract class AbstractImportGroupCommand extends Command
{
    protected SymfonyStyle $io;

    protected OutputInterface $output;

    protected PhoneBookCompareResult $compareResult;

    protected PhoneBookUtility $phoneBookUtility;

    protected StorageRepository $storageRepository;

    protected ImportableGroupInterface $groupRepository;

    protected ImportableUserInterface $userRepository;

    public function __construct(
        ImportableGroupInterface $groupRepository,
        PhoneBookUtility $phoneBookUtility,
        StorageRepository $storageRepository,
        ImportableUserInterface $userRepository,
        string $name = null
    ) {
        parent::__construct($name);
        $this->groupRepository = $groupRepository;
        $this->phoneBookUtility = $phoneBookUtility;
        $this->storageRepository = $storageRepository;
        $this->userRepository = $userRepository;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->output = $output;
        $io = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());

        $io->writeln('Reading Groups from database and JSON..');

        $this->phoneBookUtility->loadJson();

        $apiGroups = $this->phoneBookUtility->getGroupIdentifierInJson();
        $dbGroups = $this->groupRepository->findAllGroupsWithDkfzGroupIdentifier();
        $dbUsers = $this->userRepository->findAllUsersWithDkfzId();
        $this->phoneBookUtility->setGroupUserRelations($dbUsers);

        $io->listing([
            '<success>' . count($apiGroups) . '</success> found in PhoneBook (JSON)',
            '<success>' . count($dbGroups) . '</success> found in database',
        ]);

        $io->writeln('Comparing Groups..');
        $this->compareResult = $this->phoneBookUtility->compareDbGroupsWithJson($dbGroups);

        $io->listing([
            '<success>' . count($this->compareResult->dkfzGroupIdentifierToCreate) . '</success> to create',
            '<warning>' . count($this->compareResult->dkfzGroupIdentifiersToUpdate) . '</warning> to update',
            '<error>' . count($this->compareResult->dkfzGroupIdentifiersToDelete) . '</error> to delete',
            '' . count($this->compareResult->dkfzGroupIdentifiersToSkip) . ' to skip',
        ]);

        if (count($this->compareResult->dkfzGroupIdentifierToCreate)) {
            $io->write('Creating groups..');
            $fileMounts = $this->getAndCreateFileMountsForGroups();
            $phoneBookAbteilungenToCreate = $this->phoneBookUtility->getPhoneBookAbteilungenByIdentifiers($this->compareResult->dkfzGroupIdentifierToCreate);
            $pid = $this->phoneBookUtility->getUserStoragePid($this);
            $this->groupRepository->bulkInsertPhoneBookAbteilungen($phoneBookAbteilungenToCreate, $pid, $fileMounts);
            $io->write('<success>done</success>');
            $io->newLine();
        }

        if (count($this->compareResult->dkfzGroupIdentifiersToUpdate)) {
            $io->write('Updating groups..');
            $phoneBookAbteilungenToUpdate = $this->phoneBookUtility->getPhoneBookAbteilungenByIdentifiers($this->compareResult->dkfzGroupIdentifiersToUpdate);
            foreach ($phoneBookAbteilungenToUpdate as $bookAbteilung) {
                $this->groupRepository->updateFromPhoneBookEntry($bookAbteilung);
            }
            $io->write('<success>done</success>');
            $io->newLine();
        }

        if (count($this->compareResult->dkfzGroupIdentifiersToDelete)) {
            $io->write('Deleting groups..');
            $this->groupRepository->deleteByDkfzGroupIdentifiers($this->compareResult->dkfzGroupIdentifiersToDelete);
            $io->write('<success>done</success>');
            $io->newLine();
        }

        $io->success('Done');

        return Command::SUCCESS;
    }

    /**
     * @return array<int, array{title: string, uid: int}>
     */
    protected function getAndCreateFileMountsForGroups(): array
    {
        return [];
    }

    /**
     * @return array<int, array{title: string, uid:int}>
     */
    protected function getAndCreateNewsPagesForGroups(): array
    {
        return [];
    }
}
