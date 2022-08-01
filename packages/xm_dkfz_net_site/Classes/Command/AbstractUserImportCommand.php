<?php

namespace Xima\XmDkfzNetSite\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Xima\XmDkfzNetSite\Domain\Model\Dto\PhoneBookCompareResult;
use Xima\XmDkfzNetSite\Domain\Repository\ContactRepository;
use Xima\XmDkfzNetSite\Domain\Repository\ImportableGroupInterface;
use Xima\XmDkfzNetSite\Domain\Repository\ImportableUserInterface;
use Xima\XmDkfzNetSite\Domain\Repository\PlaceRepository;
use Xima\XmDkfzNetSite\Utility\PhoneBookUtility;

abstract class AbstractUserImportCommand extends Command
{
    protected SymfonyStyle $io;

    protected ImportableUserInterface $userRepository;

    protected ImportableGroupInterface $groupRepository;

    protected ContactRepository $contactRepository;

    protected PhoneBookCompareResult $compareResult;

    protected PhoneBookUtility $phoneBookUtility;

    public function __construct(
        ImportableUserInterface $userRepository,
        ImportableGroupInterface $groupRepository,
        PhoneBookUtility $phoneBookUtility,
        ContactRepository $contactRepository,
        string $name = null
    ) {
        parent::__construct($name);
        $this->userRepository = $userRepository;
        $this->groupRepository = $groupRepository;
        $this->phoneBookUtility = $phoneBookUtility;
        $this->contactRepository = $contactRepository;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $this->io = $io;
        $io->title($this->getDescription());

        $io->writeln('Reading Users from database and JSON..');

        $filterEntriesForPlaces = $this->userRepository instanceof PlaceRepository;
        $this->phoneBookUtility->setFilterEntriesForPlaces($filterEntriesForPlaces);
        $this->phoneBookUtility->loadJson();

        $dbUsers = $this->userRepository->findAllUsersWithDkfzId();
        $dbGroups = $this->groupRepository->findAllGroupsWithDkfzNumber();
        $this->phoneBookUtility->setUserGroupRelations($dbGroups);

        $io->listing([
            '<success>' . $this->phoneBookUtility->getPhoneBookEntryCount() . '</success> found in XML',
            '<success>' . count($dbUsers) . '</success> found in database',
        ]);

        $this->io->writeln('Comparing Users..');
        $this->compareResult = $this->phoneBookUtility->compareDbUsersWithPhoneBookEntries($dbUsers);
        $this->io->newLine(1);

        $io->listing([
            '<success>' . count($this->compareResult->dkfzIdsToCreate) . '</success> to create',
            '<warning>' . count($this->compareResult->dkfzIdsToUpdate) . '</warning> to update',
            '<error>' . count($this->compareResult->dkfzIdsToDelete) . '</error> to delete',
            '' . count($this->compareResult->dkfzIdsToSkip) . ' to skip',
        ]);

        $this->createUsers();
        $this->createContacts();
        $this->updateUsers();
        $this->updateContacts();
        $this->deleteContacts();
        $this->deleteUsers();

        $io->success('Done');

        return Command::SUCCESS;
    }

    protected function createUsers(): void
    {
        if (!count($this->compareResult->dkfzIdsToCreate)) {
            return;
        }

        $this->io->write('Creating entries..');
        $phoneBookEntriesToAdd = $this->phoneBookUtility->getPhoneBookEntriesByIds($this->compareResult->dkfzIdsToCreate);
        $pid = $this->phoneBookUtility->getUserStoragePid($this);
        $this->userRepository->bulkInsertPhoneBookEntries($phoneBookEntriesToAdd, $pid);
        $this->io->write('<success>done</success>');
        $this->io->newLine();
    }

    protected function createContacts(): void
    {
        if (!count($this->compareResult->dkfzIdsToCreate)) {
            return;
        }

        $this->io->write('Creating contacts for entries..');
        $phoneBookEntriesToAdd = $this->phoneBookUtility->getPhoneBookEntriesByIds($this->compareResult->dkfzIdsToCreate);
        $pid = $this->phoneBookUtility->getUserStoragePid($this);
        $dbUsers = $this->userRepository->findAllUsersWithDkfzId();
        $this->contactRepository->bulkInsertPhoneBookEntries($phoneBookEntriesToAdd, $pid, $dbUsers);
        $this->io->write('<success>done</success>');
        $this->io->newLine();
    }

    protected function updateUsers(): void
    {
        if (!count($this->compareResult->dkfzIdsToUpdate)) {
            return;
        }

        $this->io->write('Updating entries..');
        $phoneBookEntriesToUpdate = $this->phoneBookUtility->getPhoneBookEntriesByIds($this->compareResult->dkfzIdsToUpdate);
        foreach ($phoneBookEntriesToUpdate as $phoneBookEntry) {
            $this->userRepository->updateUserFromPhoneBookEntry($phoneBookEntry);
        }
        $this->io->write('<success>done</success>');
        $this->io->newLine();
    }

    public function updateContacts(): void
    {
        if (!count($this->compareResult->dkfzIdsToUpdate)) {
            return;
        }

        $tableName = $this->phoneBookUtility->getFilterEntriesForPlaces() ? 'tx_xmdkfznetsite_domain_model_place' : 'fe_users';
        $this->io->write('Updating (delete + create new) contacts for entries (' . $tableName . ')..');
        $dbUsers = $this->userRepository->findByDkfzIds($this->compareResult->dkfzIdsToUpdate);
        $this->contactRepository->deleteByDkfzUserIds($dbUsers, $tableName);
        $pid = $this->phoneBookUtility->getUserStoragePid($this);
        $phoneBookEntriesToUpdate = $this->phoneBookUtility->getPhoneBookEntriesByIds($this->compareResult->dkfzIdsToUpdate);
        $this->contactRepository->bulkInsertPhoneBookEntries($phoneBookEntriesToUpdate, $pid, $dbUsers);
        $this->io->write('<success>done</success>');
        $this->io->newLine();
    }

    protected function deleteUsers(): void
    {
        if (!count($this->compareResult->dkfzIdsToDelete)) {
            return;
        }

        $this->io->write('Deleting entries..');
        $this->userRepository->deleteUsersByDkfzIds($this->compareResult->dkfzIdsToDelete);
        $this->io->write('<success>done</success>');
        $this->io->newLine();
    }

    protected function deleteContacts(): void
    {
        if (!count($this->compareResult->dkfzIdsToDelete)) {
            return;
        }

        $tableName = $this->phoneBookUtility->getFilterEntriesForPlaces() ? 'tx_xmdkfznetsite_domain_model_place' : 'fe_users';
        $this->io->write('Deleting contacts for entries (' . $tableName . ')..');
        $dbUsers = $this->userRepository->findByDkfzIds($this->compareResult->dkfzIdsToDelete);
        $this->contactRepository->deleteByDkfzUserIds($dbUsers, $tableName);
        $this->io->write('<success>done</success>');
        $this->io->newLine();
    }
}
