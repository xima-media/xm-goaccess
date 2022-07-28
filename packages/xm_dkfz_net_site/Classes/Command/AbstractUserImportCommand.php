<?php

namespace Xima\XmDkfzNetSite\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Xima\XmDkfzNetSite\Domain\Model\Dto\PhoneBookCompareResult;
use Xima\XmDkfzNetSite\Domain\Repository\ImportableGroupInterface;
use Xima\XmDkfzNetSite\Domain\Repository\ImportableUserInterface;
use Xima\XmDkfzNetSite\Utility\PhoneBookUtility;

abstract class AbstractUserImportCommand extends Command
{
    protected SymfonyStyle $io;

    protected ImportableUserInterface $userRepository;

    protected ImportableGroupInterface $groupRepository;

    protected PhoneBookCompareResult $compareResult;

    protected PhoneBookUtility $phoneBookUtility;

    public function __construct(
        ImportableUserInterface $userRepository,
        ImportableGroupInterface $groupRepository,
        string $name = null
    ) {
        parent::__construct($name);
        $this->userRepository = $userRepository;
        $this->groupRepository = $groupRepository;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $this->io = $io;
        $io->title($this->getDescription());

        $io->writeln('Reading Users from database and JSON..');

        $this->phoneBookUtility = GeneralUtility::makeInstance(PhoneBookUtility::class);
        $this->phoneBookUtility->loadJson();

        $dbUsers = $this->userRepository->findAllUsersWithDkfzId();
        $dbGroups = $this->groupRepository->findAllGroupsWithDkfzNumber();

        $io->listing([
            '<success>' . $this->phoneBookUtility->getUserCountInJson() . '</success> found in XML',
            '<success>' . count($dbUsers) . '</success> found in database',
        ]);

        $io->writeln('Comparing Users..');
        $this->compareResult = $this->phoneBookUtility->compareDbUsersWithPhoneBookUsers($dbUsers, $dbGroups);
        $io->newLine(2);

        $io->listing([
            '<success>' . count($this->compareResult->dkfzIdsToCreate) . '</success> to create',
            '<warning>' . count($this->compareResult->dkfzIdsToUpdate) . '</warning> to update',
            '<error>' . count($this->compareResult->dkfzIdsToDelete) . '</error> to delete',
            '' . count($this->compareResult->dkfzIdsToSkip) . ' to skip',
        ]);

        $this->createUsers();
        $this->updateUsers();
        $this->deleteUsers();

        $io->success('Done');

        return Command::SUCCESS;
    }

    protected function createUsers(): void
    {
        if (!count($this->compareResult->dkfzIdsToCreate)) {
            return;
        }

        $this->io->write('Creating users..');
        $phoneBookUsersToAdd = $this->phoneBookUtility->getPhoneBookUsersByIds($this->compareResult->dkfzIdsToCreate);
        $pid = $this->phoneBookUtility->getUserStoragePid($this);
        $this->userRepository->bulkInsertPhoneBookEntries($phoneBookUsersToAdd, $pid);
        $this->io->write('<success>done</success>');
        $this->io->newLine();
    }

    protected function updateUsers(): void
    {
        if (!count($this->compareResult->dkfzIdsToUpdate)) {
            return;
        }

        $this->io->write('Updating users..');
        $phoneBookUsersToUpdate = $this->phoneBookUtility->getPhoneBookUsersByIds($this->compareResult->dkfzIdsToUpdate);
        foreach ($phoneBookUsersToUpdate as $phoneBookEntry) {
            $this->userRepository->updateUserFromPhoneBookEntry($phoneBookEntry);
        }
        $this->io->write('<success>done</success>');
        $this->io->newLine();
    }

    protected function deleteUsers(): void
    {
        if (!count($this->compareResult->dkfzIdsToDelete)) {
            return;
        }

        $this->io->write('Deleting users..');
        $this->userRepository->deleteUsersByDkfzIds($this->compareResult->dkfzIdsToDelete);
        $this->io->write('<success>done</success>');
        $this->io->newLine();
    }
}
