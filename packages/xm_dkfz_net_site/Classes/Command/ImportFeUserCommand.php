<?php

namespace Xima\XmDkfzNetSite\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Xima\XmDkfzNetSite\Domain\Repository\UserGroupRepository;
use Xima\XmDkfzNetSite\Domain\Repository\UserRepository;
use Xima\XmDkfzNetSite\Utility\PhoneBookUtility;

class ImportFeUserCommand extends Command
{
    protected SymfonyStyle $io;

    protected OutputInterface $output;

    protected UserRepository $userRepository;

    protected UserGroupRepository $groupRepository;

    public function __construct(
        UserRepository $userRepository,
        UserGroupRepository $groupRepository,
        string $name = null
    ) {
        parent::__construct($name);
        $this->userRepository = $userRepository;
        $this->groupRepository = $groupRepository;
    }

    protected function configure(): void
    {
        $this->setDescription('Import DKFZ user from phone book API');
        $this->setHelp('Reads users from API and updates the corresponding fe_users');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->output = $output;
        $io = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());

        $io->writeln('Reading Users from database and XML..');

        $phoneBookUtility = GeneralUtility::makeInstance(PhoneBookUtility::class);
        $phoneBookUtility->loadXpath();

        $xmlUsers = $phoneBookUtility->getUsersInXml();
        $dbUsers = $this->userRepository->findAllUsersWithDkfzId();
        $dbGroups = $this->groupRepository->findAllGroupsWithDkfzId();

        $io->listing([
            '<success>' . count($xmlUsers) . '</success> found in XML',
            '<success>' . count($dbUsers) . '</success> found in database',
        ]);

        $io->writeln('Comparing Users..');
        $progress = $io->createProgressBar(count($dbUsers));
        $progress->setFormat('%current%/%max% [%bar%] %percent%%');
        $compareResult = $phoneBookUtility->compareFeUserWithXml($dbUsers, $progress);
        $compareResult->addFeUserGroupRelationToPhoneBookUsers($dbGroups);
        $progress->finish();
        $io->newLine(2);

        $io->listing([
            '<success>' . count($compareResult->dkfzIdsToCreate) . '</success> to create',
            '<warning>' . count($compareResult->dkfzIdsToUpdate) . '</warning> to update',
            '<error>' . count($compareResult->dkfzIdsToDelete) . '</error> to delete',
            '' . count($compareResult->dkfzIdsToSkip) . ' to skip',
        ]);

        if (count($compareResult->dkfzIdsToCreate)) {
            $io->write('Creating users..');
            $phoneBookUsersToAdd = $compareResult->getPhoneBookPersonsForAction('create');
            $pid = $phoneBookUtility->getUserStoragePid();
            $this->userRepository->bulkInsertFromPhoneBook($phoneBookUsersToAdd, $pid);
            $io->write('<success>done</success>');
            $io->newLine();
        }

        if (count($compareResult->dkfzIdsToUpdate)) {
            $io->write('Updating users..');
            $phoneBookUsersToUpdate = $compareResult->getPhoneBookPersonsForAction('update');
            foreach ($phoneBookUsersToUpdate ?? [] as $phoneBookPerson) {
                $this->userRepository->updateUserFromPhoneBook($phoneBookPerson);
            }
            $io->write('<success>done</success>');
            $io->newLine();
        }

        if (count($compareResult->dkfzIdsToDelete)) {
            $io->write('Deleting users..');
            $this->userRepository->deleteUserByDkfzIds($compareResult->dkfzIdsToDelete);
            $io->write('<success>done</success>');
            $io->newLine();
        }

        $io->success('Done');

        return Command::SUCCESS;
    }
}
